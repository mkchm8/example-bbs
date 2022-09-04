<?php

namespace App\Usecases;

use App\Exceptions\Domain\LimitException;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\PostRepositoryInterface;

class CommentApplicationService
{
    /**
     * @var PostRepositoryInterface
     */
    protected PostRepositoryInterface $postRepository;

    /**
     * @var CommentRepositoryInterface
     */
    protected CommentRepositoryInterface $commentRepository;

    public function __construct(PostRepositoryInterface $postRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * コメントを投稿する
     * TODO: 利便性とテスタビリティを考慮し、登録完了時にCommentEntityを返すように変更する
     *
     * @throws LimitException
     */
    public function create(int $postId, array $inputs)
    {
        $post = $this->postRepository->findByIdWithComments($postId);
        if ($post->hasFullComment()) {
            throw new LimitException('コメント数が上限に達しているため、登録できませんでした');
        }

        $this->commentRepository->create($postId, $inputs['title'], $inputs['body']);
    }
}
