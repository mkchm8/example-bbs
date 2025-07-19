<?php

namespace Tests\Unit\Usecases;

use App\Domain\Entities;
use App\Exceptions\Domain\LimitException;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Usecases\CommentApplicationService;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Mockery\MockInterface as i;

class CommentApplicationServiceTest extends TestCase
{
    /** @var CommentApplicationService  */
    protected CommentApplicationService $service;

    /** @var PostRepositoryInterface|i  */
    protected PostRepositoryInterface|i $postRepository;

    /** @var CommentRepositoryInterface|i */
    protected CommentRepositoryInterface|i $commentRepository;

    /** @var Entities\Post|i */
    protected Entities\Post|i $post;

    /** @var \Faker\Generator */
    protected \Faker\Generator $faker;

    /**
     * @param string|null $name
     * @param array $data
     * @param $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->postRepository = m::mock(PostRepositoryInterface::class)->makePartial();
        $this->commentRepository = m::mock(CommentRepositoryInterface::class)->makePartial();
        $this->post = m::mock('alias:' . Entities\Post::class)->makePartial();
        $this->service = new CommentApplicationService($this->postRepository, $this->commentRepository);
    }

    /**
     * @param int $postId
     * @param array $data
     *
     * @return void
     * @throws LimitException
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @dataProvider provideCommentData
     */
    public function test_コメント登録OK(int $postId, array $data)
    {
        $expectedComment = m::mock(Entities\Comment::class);

        $this->commentRepository->shouldReceive('create')
            ->once()
            ->with($postId, $data['title'], $data['body'])
            ->andReturn($expectedComment);

        $this->postRepository->shouldReceive('findByIdWithComments')
            ->once()
            ->with($postId)
            ->andReturn($this->post);

        $this->post->shouldReceive('hasFullComment')
            ->once()
            ->andReturn(false);

        $result = $this->service->create($postId, $data);

        $this->assertSame($expectedComment, $result);
    }

    /**
     * @dataProvider provideCommentData
     * @return void
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_上限を超えてコメントを登録しようとすると例外が発生する($postId, $data)
    {
        $this->commentRepository->shouldReceive('create')->never();

        $this->postRepository->shouldReceive('findByIdWithComments')
            ->once()
            ->with($postId)
            ->andReturn($this->post);

        $this->post->shouldReceive('hasFullComment')
            ->once()
            ->andReturn(true);

        $this->expectException(LimitException::class);
        $this->service->create($postId, $data);
    }

    public function provideCommentData()
    {
        $faker = Factory::create();
        return [
            'ok' => [
                'postId' => $faker->unique()->randomDigitNotNull(),
                'data' => [
                    'title' => $faker->realText(Entities\Comment::TITLE_MAX_LENGTH),
                    'body' => $faker->realText(Entities\Comment::MAX_LENGTH)
                ],
            ],
        ];
    }
}
