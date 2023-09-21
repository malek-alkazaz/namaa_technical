<?php

namespace App\Http\Controllers\V1\Api\article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\V1\Entity\ArticleService;
use App\Http\Controllers\V1\Api\ApiController;
use App\Http\Requests\V1\Api\Article\StoreArticleRequest;
use App\Http\Requests\V1\Api\Article\UpdateArticleRequest;

class ArticleController extends ApiController
{
    protected $modelService = ArticleService::class;
    protected $storeRequest = StoreArticleRequest::class;
    protected $updateRequest = UpdateArticleRequest::class;
    protected $model =  Article::class;

    public function __construct(protected ArticleService $articleService){ $this->service = $articleService; }

    public function review(): JsonResponse{
        return $this->handleSharedMessage($this->articleService->review());
    }

    /**
     * approve article
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function approve(Request $request ,Article $article): JsonResponse{
        return $this->handleSharedMessage($this->articleService->approve($request , $article));
    }

    /**
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse{
        return $this->handleSharedMessage($this->articleService->search($request));
    }

    public function popularArticle(): JsonResponse{
        return $this->handleSharedMessage($this->articleService->popularArticle());
    }

    /**
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getArticleWithStatus(Request $request): JsonResponse{
        return $this->handleSharedMessage($this->articleService->getArticleWithStatus($request));
    }
}
