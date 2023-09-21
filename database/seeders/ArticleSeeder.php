<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Enums\ArticleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create 30 test Artical
        Article::factory()
            ->count(20)
            ->state(function () {
                return [
                    'user_id' => User::where('type_id',2)->first()->id,
                    'status'  => ArticleStatus::Pending,
                ];
            })
            ->create();


        // Create 10 test Artical
        Article::factory()
            ->count(10)
            ->state(function () {
                return [
                    'user_id' => User::where('type_id',1)->first()->id,
                    'status'  => ArticleStatus::Accepted,
                    'approved_by' => User::where('type_id',2)->first()->id,
                ];
            })
            ->create();

        // Create 10 test Artical
        Article::factory()
        ->count(10)
        ->state(function () {
            return [
                'user_id' => User::where('type_id',1)->first()->id,
                'status'  => ArticleStatus::Rejected,
                'approved_by' => User::where('type_id',2)->first()->id,
            ];
        })
        ->create();


        // $articles = [
        //     [
        //         'title' => 'article 1',
        //         'body' => 'body 11',
        //         'status' => ArticleStatus::Pending,
        //         'approved_by' => null,
        //         'user_id' => User::where('type_id',2)->first()->id,
        //     ],
        //     [
        //         'title' => 'article 2',
        //         'body' => 'body 22',
        //         'status' => ArticleStatus::Accepted,
        //         'approved_by' => User::where('type_id',1)->first()->id,
        //         'user_id' => User::where('type_id',2)->first()->id,
        //     ],
        //     [
        //         'title' => 'article 3',
        //         'body' => 'body 33',
        //         'status' => ArticleStatus::Accepted,
        //         'approved_by' =>User::where('type_id',1)->first()->id,
        //         'user_id' => User::where('type_id',2)->first()->id,
        //     ],
        //     [
        //         'title' => 'article 4',
        //         'body' => 'body 44',
        //         'status' => ArticleStatus::Rejected,
        //         'approved_by' =>User::where('type_id',1)->first()->id,
        //         'user_id' => User::where('type_id',2)->first()->id,
        //     ],
        // ];

        // foreach ($articles as $article) {
        //     Article::create($article);
        // }
    }
}
