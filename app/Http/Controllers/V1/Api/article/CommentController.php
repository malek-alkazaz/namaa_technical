<?php

namespace App\Http\Controllers\V1\Api\article;

use App\Models\Comment;
use App\Services\V1\Entity\CommentService;
use App\Http\Controllers\V1\Api\ApiController;
use App\Http\Requests\V1\Api\Comment\StoreCommentRequest;
use App\Http\Requests\V1\Api\Comment\UpdateCommentRequest;

class CommentController extends ApiController
{
    protected $modelService = CommentService::class;
    protected $storeRequest = StoreCommentRequest::class;
    protected $updateRequest = UpdateCommentRequest::class;
    protected $model =  Comment::class;
}
