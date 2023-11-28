<?php

namespace Database\Factories;

use App\Models\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class PollFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Poll::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->sentence().' ?',
            'options' => json_encode($this->faker->words()),
            'poll_index' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Poll $poll) {
            $maxPollIndex = DB::table('polls')
                ->where('presentation_id', $poll->presentation_id)
                ->max('poll_index');

            $poll->poll_index = $maxPollIndex !== null ? $maxPollIndex + 1 : 0;
            $poll->save();
        });
    }
}
