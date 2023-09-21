<?php

namespace App\Services\V1\Entity;

use App\Models\Article;
use App\Enums\ArticleStatus;
use Illuminate\Http\Request;
use App\Common\SharedMessage;
use App\Services\V1\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ArticleResource;
use App\Jobs\NewArticleNotificationJob;


class ArticleService extends BaseService{

    protected $model = Article::class;
    protected $resource = ArticleResource::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     */
    protected function save($data){
        $article = Article::create([
            'title' => strip_tags($data['title']),
            'body' => strip_tags($data['body']),
            'status' => ArticleStatus::Pending,
            'approved_by' => null,
            'user_id' => Auth::id(),
        ]);

        NewArticleNotificationJob::dispatch($article)->onQueue('emails');
        return $article;
    }

    /**
     * Display a listing of the resource.
     *
     * @return SharedMessage
     */
    public function index(): SharedMessage{
        $articles = Cache::remember('articles', 60, function () {
            return Article::where('status','accepted')->orderBy('created_at', 'desc')->get();
        });
        return new SharedMessage(__('success.show_successful'), ArticleResource::collection($articles), true, null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return SharedMessage
     */
    public function show($article): SharedMessage{
        if(Auth::user()->role->name === 'admin'){
            return new SharedMessage(__('success.show_successful'), new ArticleResource($article), true, null, 200);
        }
        elseif($article->user_id === Auth::id() && $article->status->value !== 'accepted'){
            return new SharedMessage(__('success.show_successful'), new ArticleResource($article), true, null, 200);
        }
        elseif($article->status->value === 'accepted')
        {
            if($article->user_id !== Auth::id()){
                $article->view();
            }
            return new SharedMessage(__('success.show_successful'), new ArticleResource($article), true, null, 200);
        }else{
            return new SharedMessage(__('error.show_fail'), [], true, null, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Article  $article
     * @param  Request  $request
     * @return SharedMessage
     */
    public function update(array $data, $article): SharedMessage
    {
        if($article->user_id === Auth::id()){
            $article->update($data);
        }
        Cache::forget('articles');
        Cache::forget('popular_articles');
        return new SharedMessage(__('success.update_successful'), new ArticleResource($article), true, null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return SharedMessage
     */
    public function destroy($article): SharedMessage{
        if($article->user_id === Auth::id()){
            $result = $article->delete();
            if ($result) {
                Cache::forget('articles');
                Cache::forget('popular_articles');
                return new SharedMessage(__('success.delete_successful'), [], true, null, 200);
            }
        }
        return new SharedMessage(__('success.delete_fail'), [], false, null, 400);
    }

    /**
     * Fetch articles that need to be reviewed
     *
     * @param  \App\Models\Article  $article
     * @param  Request  $request
     * @return SharedMessage
     */
    public function approve(Request $request, Article $article): SharedMessage {
        if (Auth::user()->role->name !== 'admin') {
            return response(['message' => 'Unauthorized'], 401);
        }else{
            $article->status = ArticleStatus::getValue($request['status']);
            $article->approved_by = Auth::id();
            $article->save();

            Cache::forget('articles');
            Cache::forget('popular_articles');
            return new SharedMessage(__('success.Article approved'), new ArticleResource($article), true, null, 200);
        }
    }

    /**
     * Fetch articles that need to be reviewed
     *
     * @return SharedMessage
     */
    public function review(): SharedMessage {
        $articles = Article::where('status',ArticleStatus::Pending)->get();
        return new SharedMessage(__('success.show_successful'), ArticleResource::collection($articles), true, null, 200);
    }

    /**
     *
     * @param  Request  $request
     * @return SharedMessage
     */
    public function search(Request $request): SharedMessage {
        $query = $request->input('query');
        $articles = (new Article)->search($query);
        return new SharedMessage(__('success.search_successful'), ArticleResource::collection($articles), true, null, 200);
    }

    /**
     * Fetch Popular Article who have the most views
     *
     * @return SharedMessage
     */
    public function popularArticle(): SharedMessage {
        $articles = Cache::remember('popular_articles', 60, function () {
            // return Article::orderBy('views', 'desc')->take(30)->get();
            return Article::where('status','accepted')->orderBy('views', 'desc')->get();
        });
        return new SharedMessage(__('success.show_successful'), ArticleResource::collection($articles), true, null, 200);
    }

    /**
     * Fetch Article with Specific statuses
     *
     * @param  Request  $status of Article
     * @return SharedMessage
     */
    public function getArticleWithStatus(Request $request): SharedMessage {
        if(Auth::user()->role->name === 'admin'){
            $articles = Article::where('status',$request->status)->get();
            return new SharedMessage(__('success.show_successful'), ArticleResource::collection($articles), true, null, 200);
        }elseif(Auth::user()->role->name === 'user'){
            $articles = Article::where('user_id',Auth::id())->where('status',$request->status)->get();
            return new SharedMessage(__('success.show_successful'), ArticleResource::collection($articles), true, null, 200);
        }
    }
}
