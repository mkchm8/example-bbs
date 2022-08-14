<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Domain\Entities;

interface PostRepositoryInterface
{
    /**
     * 投稿リストをコメント付きで取得する
     *
     * @param array $conditions
     * @return Collection
     */
    public function findListWithComments(array $conditions): Collection;

    /**
     * 投稿をコメント付きで取得する
     *
     * @param int $postId
     * @return Entities\Post
     */
    public function findByIdWithComments(int $postId): Entities\Post;
}
