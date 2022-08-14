<?php

namespace App\Http\Controllers\Web;

use App\Usecases\PostApplicationService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class CommentController extends WebBaseController
{
    public function create(Request $request, PostApplicationService $postApplicationService): View
    {
        $postId = (int) $request->route('post_id');
        $post = $postApplicationService->getByIdWithComments($postId);

        return view('web.posts.comments.create', ['post' => $post]);
    }
}
