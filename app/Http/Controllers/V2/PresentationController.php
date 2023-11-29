<?php

namespace App\Http\Controllers\V2;

use GuzzleHttp\Client;
use App\Helper\ArrayHelper;
use App\Models\Presentation;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Here V2 API proxies POST /presentations and GET /presentations/{presentation_id}
 */
class PresentationController extends Controller
{
    public function create(Request $request, Client $client)
    {
        try {
            $this->validate($request, [
                'current_poll_index' => 'required|integer',
                'polls' => 'required|array',
                'polls.*.poll_id' => 'required|uuid',
                'polls.*.question' => 'required|string',
                'polls.*.options' => 'required|array',
                'polls.*.options.*.key' => 'required|string',
                'polls.*.options.*.value' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors and return HTTP 400 response
            return response()->json(['error' => $e->errors()], 400);
        }

        $data = $request->only(['current_poll_index', 'polls']);

        $url = 'https://infra.devskills.app/api/interactive-presentation/v4/presentations';

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        // Create same records in our database
        $presentation = Presentation::create([
            'current_poll_index' => $data['current_poll_index'],
        ]);

        foreach ($data['polls'] as $key => $poll) {
            Poll::create([
                'presentation_id' => $presentation->id,
                'poll_index' => (int) $key,
                'id' => $poll['poll_id'],
                'question' => $poll['question'],
                'question' => $poll['question'],
                'options' => json_encode($poll['options']),
            ]);
        }

        // Forward the response from the external API to the client
        return response($response->getBody(), $response->getStatusCode())
            ->header('Content-Type', 'application/json');
    }

    public function get(string $id, Client $client)
    {
        $url = 'https://infra.devskills.app/api/interactive-presentation/v4/presentations/'.$id;

        $response = $client->get($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        // Forward the response from the external API to the client
        return response($response->getBody(), $response->getStatusCode())
            ->header('Content-Type', 'application/json');
    }

    public function current(string $id, ArrayHelper $arrayHelper)
    {
        $presentation = Presentation::find($id);

        if (!$presentation) {
            return response(json_encode([
                'message' => 'Presentation not found.',
            ]), 404)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        if (count($presentation->polls) === 0) {
            return response(json_encode([
                'message' => 'Presentation has no polls.',
            ]), 422)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        $currentPoll = collect($presentation->polls)->firstWhere('poll_index', $presentation->current_poll_index);
        $optionsArray = json_decode($currentPoll->options, true);

        return response(json_encode([
            'poll_id' => $currentPoll->id,
            'question' => $currentPoll->question,
            'options' => $arrayHelper->assignLetters($optionsArray),
        ]), 200)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    public function next(string $id, ArrayHelper $arrayHelper)
    {
        $presentation = Presentation::find($id);

        if (!$presentation) {
            return response(json_encode([
                'message' => 'Presentation not found.',
            ]), 404)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        if ($presentation->current_poll_index + 1 >= count($presentation->polls)) {
            return response(json_encode([
                'message' => 'Presentation has no more polls.',
            ]), 422)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        $presentation->increment('current_poll_index');

        if (count($presentation->polls) === 0) {
            return response(json_encode([
                'message' => 'Presentation has no polls.',
            ]), 422)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        $currentPoll = collect($presentation->polls)->firstWhere('poll_index', $presentation->current_poll_index);
        $optionsArray = json_decode($currentPoll->options, true);

        return response(json_encode([
            'poll_id' => $currentPoll->id,
            'question' => $currentPoll->question,
            'options' => $arrayHelper->assignLetters($optionsArray),
        ]), 200)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}
