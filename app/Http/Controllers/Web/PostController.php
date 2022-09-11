<?php

namespace App\Http\Controllers\Web;

use App\Usecases\PostApplicationService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PostController extends WebBaseController
{
    /**
     * @param Request $request
     * @param PostApplicationService $postApplicationService
     * @return View
     */
    public function index(Request $request, PostApplicationService $postApplicationService): View
    {
        $result = $postApplicationService->getApprovedListWithComments();

        return view('web.posts.index', ['posts' => $result]);
    }
}
