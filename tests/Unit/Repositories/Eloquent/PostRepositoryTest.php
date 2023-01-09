<?php

namespace Tests\Unit\Repositories\Eloquent;

use App\Domain\Entities\Comment;
use App\Domain\Entities\Post;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\DataAccess\Eloquent;
use App\Domain\Enums;

class PostRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @var Eloquent\Post $postEloquent  */
    protected Eloquent\Post $postEloquent;

    /** @var PostRepositoryInterface  */
    protected PostRepositoryInterface $postRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->postEloquent = new Eloquent\Post();
        $this->postRepository = new PostRepository($this->postEloquent);
    }

    /**
     * @return void
     */
    public function test_findListWithComments()
    {
        // given
        $conditions = ['postStatus' => 1, 'commentStatus' => 1];

        Eloquent\Post::factory()->count(2)
            ->has(Eloquent\Comment::factory()->count(3))
            ->create();

        // when
        $posts = $this->postRepository->findListWithComments($conditions);

        // then
        $this->assertDatabaseCount('posts', 2);
        $this->assertDatabaseCount('comments', 6);

        $posts->each(
            function (Post $post) use ($conditions) {
                $this->assertEquals($post->status, $conditions['postStatus']);
                $post->comments->each(
                    fn(Comment $comment) => $this->assertEquals($comment->status, $conditions['commentStatus'])
                );
            }
        );
    }
}
