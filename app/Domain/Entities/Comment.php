<?php

namespace App\Domain\Entities;

use App\Domain\Enums\Comment\Status;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\Pure;

class Comment extends DomainEntity
{
    /** @var int タイトル文字数上限 */
    const TITLE_MAX_LENGTH = 20;

    /** @var int 本文文字数上限 */
    const MAX_LENGTH = 300;

    public function __construct(
        public readonly ?int $id,
        public readonly int $postId,
        public readonly string $title,
        public readonly string $body,
        public readonly int $status,
        public readonly Carbon $createdAt,
        public readonly Carbon $updatedAt,
    ) {}

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
        return new self(
            id: null,
            postId: $postId,
            title:  $title,
            body:  $body,
            status:  Status::Unapproved->value,
            createdAt:  Carbon::now(),
            updatedAt:  Carbon::now(),
        );
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
    public static function reConstruct(int $id, int $postId, string $title, string $body, int $status, Carbon $createdAt, Carbon $updatedAt): Comment
    {
        return new self(
            id: $id,
            postId: $postId,
            title:  $title,
            body:  $body,
            status:  $status,
            createdAt:  $createdAt,
            updatedAt:  $updatedAt,
        );
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->$key;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
