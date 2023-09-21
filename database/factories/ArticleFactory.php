<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;
use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'         => $this->faker->title(),
            'body'          => $this->faker->paragraphs(3, true),
            'status'        => ArticleStatus::Pending,
            'approved_by'   => null,
            'user_id'       => User::where('type_id',1)->first()->id,
        ];
    }
}
