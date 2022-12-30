<?php

namespace Database\Factories;

use App\DataAccess\Eloquent;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Enums\Post\Status;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * @var string 生成するEloquentモデル名
     */
    protected $model = Eloquent\Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'title' => $faker->realText(30),
            'body' => $faker->realText(300),
            'status' => $faker->randomElement(Status::toArray()),
        ];
    }
}
