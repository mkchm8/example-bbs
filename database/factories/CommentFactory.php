<?php

namespace Database\Factories;

use App\DataAccess\Eloquent;
use App\Domain\Entities;
use App\Enums\Domains\Comment\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{
    protected $model = Eloquent\Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'id' => $faker->unique()->randomDigitNotZero(),
            'post_id' => $faker->unique()->randomDigitNotZero(),
            'title' => $faker->realText(Entities\Comment::TITLE_MAX_LENGTH),
            'body' => $faker->realText(Entities\Comment::MAX_LENGTH),
            'status' => $faker->randomElement(Status::toArray()),
            'created_at' => $faker->dateTime(),
            'updated_at' => $faker->dateTime(),
        ];
    }
}
