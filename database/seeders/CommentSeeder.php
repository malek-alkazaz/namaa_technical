<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            [
                'content' => 'content 1',
                'article_id' => Article::latest()->first()->id,
                'user_id' => User::where('type_id',2)->first()->id,
            ],
            [
                'content' => 'content 2',
                'article_id' => Article::latest()->first()->id,
                'user_id' => User::where('type_id',2)->first()->id,
            ],
            [
                'content' => 'content 3',
                'article_id' => Article::latest()->first()->id,
                'user_id' => User::where('type_id',2)->first()->id,
            ],
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }
    }
}
