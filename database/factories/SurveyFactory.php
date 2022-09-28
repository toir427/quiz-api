<?php

namespace Database\Factories;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Survey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ageFrom = rand(2, 60);
        $ageTo = rand($ageFrom, 120);
        $format = 'Y-m-d H:i:m';
        $dateFrom = new \DateTime();
        $dateTo = clone $dateFrom;
        $dateTo->add(new \DateInterval("P1M"));

        return [
            'title' => $this->faker->paragraph(1),
            'description' => $this->faker->text,
            'date_from' => $dateFrom->format($format),
            'date_to' => $dateTo->format($format),
            'status' => 1,
            'gender' => rand(1, 3),
            'age_from' => $ageFrom,
            'age_to' => $ageTo,
            'user_id' => User::orderBy("id")->first()->id,
        ];
    }
}
