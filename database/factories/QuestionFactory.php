<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->paragraph(1),
            'question' => $this->faker->sentence,
            'position' => $this->faker->randomElement(range(1, 100)),
            'answer_type' => $this->faker->randomElement(range(1, 5)),
        ];
    }
}
