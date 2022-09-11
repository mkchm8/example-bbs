<?php

namespace App\Domain\Entities;

use App\Domain\Enums\Post\Status;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class Post extends DomainEntity
{
    /** @var int コメント数上限 */
    const COMMENT_COUNT_LIMIT = 10;

    protected int $id;
    protected string $title;
    protected string $body;
    protected int $status;
    protected Collection $comments;
    protected Carbon $createdAt;
    protected Carbon $updatedAt;

    /**
     * 新規の投稿を作成する
     *
     * @param string $title
     * @param string $body
     * @return Post
     */
    public static function create(string $title, string $body): Post
    {
        return new Post([
            'title' => $title,
            'body' => $body,
            'status' => Status::Unapproved,
            'createdAt' => Carbon::now(),
            'updatedAt' => Carbon::now(),
        ]);
    }

    /**
     * DBなどの既存データからインスタンスを再構成する
     *
     * @param int $id
     * @param string $title
     * @param string $body
     * @param int $status
     * @param Collection $comments
     * @param Carbon $createdAt
     * @param Carbon $updatedAt
     * @return Post
     */
    #[Pure] public static function reConstruct(int $id, string $title, string $body, int $status, Collection $comments, Carbon $createdAt, Carbon $updatedAt): Post
    {
        return new Post([
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'status' => $status,
            'comments' => $comments,
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
        ]);
    }

    /**
     * コメント数が上限に達しているかどうかを返す
     *
     * @return bool
     */
    public function hasFullComment()
    {
        return count($this->comments) >= self::COMMENT_COUNT_LIMIT;
    }

    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }
}
