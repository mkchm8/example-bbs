<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\Domain\LimitException;
use App\Http\Requests\Web\Comment\StoreRequest;
use App\Usecases\CommentApplicationService;
use App\Usecases\PostApplicationService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CommentController extends WebBaseController
{
    /**
     * @param Request $request
     * @param PostApplicationService $postApplicationService
     * @return View
     */
    public function create(Request $request, PostApplicationService $postApplicationService): View
    {
        $postId = (int) $request->route('post_id');
        $post = $postApplicationService->getByIdWithApprovedComments($postId);

        return view('web.posts.comments.create', ['post' => $post]);
    }

    /**
     * @param StoreRequest $request
     * @param CommentApplicationService $commentApplicationService
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, CommentApplicationService $commentApplicationService): RedirectResponse
    {
        $postId = (int) $request->route('post_id');
        $inputs = $request->only(['title', 'body']);

        try {
            $commentApplicationService->create($postId, $inputs);
            session()->flash('flash_message_ok', 'コメントの投稿が完了しました');
        } catch (LimitException $registerException) {
            session()->flash(
                'flash_message_ng',
                $registerException->getMessage()
            );
        }

        return redirect()->route('web.post.comment.create', $postId);
    }
}
