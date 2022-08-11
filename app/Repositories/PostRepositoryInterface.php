<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface PostRepositoryInterface
{
    /**
     * 投稿リストをコメント付きで取得する
     *
     * @param array $conditions
     * @return Collection
     */
    public function findListWithComments(array $conditions): Collection;
}
