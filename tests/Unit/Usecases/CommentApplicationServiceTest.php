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

    /**
     * @param string|null $name
     * @param array $data
     * @param $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
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
        $this->commentRepository->shouldReceive('create')
            ->once()
            ->with($postId, $data['title'], $data['body'])
            ->andReturnNull();

        $this->postRepository->shouldReceive('findByIdWithComments')
            ->once()
            ->with($postId)
            ->andReturn($this->post);

        $this->post->shouldReceive('hasFullComment')
            ->once()
            ->andReturn(false);

        $this->service->create($postId, $data);

        // TODO: 例外が発生しないことでテストOKとしているが、利便性とテスタビリティを考慮し、登録完了時にCommentEntityを返すように変更する
        $this->assertTrue(true);
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
        return [
            'ok' => [
                'postId' => $this->faker->unique()->randomDigitNotNull(),
                'data' => [
                    'title' => $this->faker->realText(Entities\Comment::TITLE_MAX_LENGTH),
                    'body' => $this->faker->realText(Entities\Comment::MAX_LENGTH)
                ],
            ],
        ];
    }
}
