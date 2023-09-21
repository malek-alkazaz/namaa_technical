<?php

namespace App\Services\V1\Entity;

use App\Models\Comment;
use App\Common\SharedMessage;
use App\Services\V1\BaseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;


class CommentService extends BaseService{

    protected $model = Comment::class;
    protected $resource = CommentResource::class;

    protected function save($data){
        return Comment::create([
            'content' => strip_tags($data['content']),
            'article_id' => strip_tags($data['article_id']),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Comment  $comment
     * @param  Request  $request
     * @return SharedMessage
     */
    public function update(array $data, $comment): SharedMessage
    {
        if($comment->user_id === Auth::id()){
            $comment->update($data);
            return new SharedMessage(__('success.update_successful'), new CommentResource($comment), true, null, 200);
        }
        return new SharedMessage(__('error.Unauthorized '), [], true, null, 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return SharedMessage
     */
    public function destroy($comment): SharedMessage{
        if($comment->user_id === Auth::id()){
            $result = $comment->delete();
            if ($result) {
                return new SharedMessage(__('success.delete_successful'), [], true, null, 200);
            }
            return new SharedMessage(__('success.delete_fail'), [], false, null, 400);
        }
        return new SharedMessage(__('error.Unauthorized '), [], true, null, 401);
    }
}
