<?php

namespace App\Domain\Entities;

use App\Domain\Enums\Comment\Status;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\Pure;

class Comment extends DomainEntity
{
    const TITLE_MAX_LENGTH = 20;
    const MAX_LENGTH = 300;

    protected int $id;
    protected int $postId;
    protected string $title;
    protected string $body;
    protected string $status;

    /**
     * 新規のコメントを作成する
     *
     * @param int $postId
     * @param string $title
     * @param string $body
     * @return Comment
     */
    public static function create(int $postId, string $title, string $body): Comment
    {
        return new Comment([
            'postId' => $postId,
            'title' => $title,
            'body' => $body,
            'status' => Status::Unapproved->value,
            'createdAt' => Carbon::now(),
            'updatedAt' => Carbon::now(),
        ]);
    }

    /**
     * DBなどの既存データからインスタンスを再構成する
     *
     * @param int $id
     * @param int $postId
     * @param string $title
     * @param string $body
     * @param int $status
     * @param Carbon $createdAt
     * @param Carbon $updatedAt
     * @return Comment
     */
    #[Pure] public static function reConstruct(int $id, int $postId, string $title, string $body, int $status, Carbon $createdAt, Carbon $updatedAt): Comment
    {
        return new Comment([
            'id' => $id,
            'postId' => $postId,
            'title' => $title,
            'body' => $body,
            'status' => $status,
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
        ]);
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
    public function getPostId()
    {
        return $this->postId;
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
}
