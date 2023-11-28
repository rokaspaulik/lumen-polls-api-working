<?php

namespace App\Http\Controllers\V1;

use App\Helper\ArrayHelper;
use App\Models\Presentation;

class PresentationController extends Controller
{
    public function create()
    {
        $presentation = Presentation::create();

        return response(json_encode([
            'presentation_id' => $presentation->id,
        ]), 201)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    public function get(string $id, ArrayHelper $arrayHelper)
    {
        $presentation = Presentation::find($id);
        if (!$presentation) {
            return response(json_encode([
                'message' => 'Presentation not found.',
            ]), 404)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        $polls = [];

        foreach ($presentation->polls as $poll) {
            $optionsArray = json_decode($poll['options'], true);

            $polls[] = [
                'poll_id' => $poll['id'],
                'question' => $poll['question'],
                'options' => $arrayHelper->assignLetters($optionsArray),
            ];
        }

        return response(json_encode([
            'current_poll_index' => $presentation->current_poll_index,
            'polls' => $polls,
        ]), 200)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
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
