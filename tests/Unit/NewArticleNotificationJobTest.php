<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Mail;
use App\Jobs\NewArticleNotificationJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\ArticleReviewNotification;
use Tests\TestCase;

class NewArticleNotificationJobTest extends TestCase
{
    use RefreshDatabase;
    protected $article;

    public function test_new_article_notification_job()
    {
        Mail::fake();


        $type = Type::create(['type' => 'user']);
        $role = Role::create(['name' => 'user']);
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '123456789',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $type = Type::create(['type' => 'admin']);
        $role = Role::create(['name' => 'admin']);
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Doe',
            'phone' => '999999999',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $article = Article::factory()->create([
            'body' => 'This is a test article',
            'title' => 'Test Article',
            'status' => 'pending',
            'approved_by' => null,
            'user_id' => $user->id,
        ]);

        $job = new NewArticleNotificationJob($article);
        $job->handle();

        Mail::assertSent(ArticleReviewNotification::class, function ($mail) use ($admin, $article) {
            return $mail->hasTo($admin->email) &&
                   $mail->article->id === $article->id;
        });
    }
}
