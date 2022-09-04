<?php

namespace Tests\Unit\Repositories\Eloquent;

use App\Domain\Entities;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\PostRepositoryInterface;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\TestCase;
use Mockery as m;
use Mockery\MockInterface as i;
use App\DataAccess\Eloquent;

class PostRepositoryTest extends TestCase
{
    /** @var Eloquent\Post|i  */
    protected Eloquent\Post|i $postEloquent;

    /** @var PostRepositoryInterface|i  */
    protected PostRepositoryInterface|i $postRepository;

    /** @var Generator */
    protected Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->postEloquent = m::mock(Eloquent\Post::class)->makePartial();
        $this->postRepository = m::mock(PostRepository::class, [$this->postEloquent])->shouldAllowMockingProtectedMethods()->makePartial();
    }

    /**
     * TODO: EloquentをモックせずにDBを使用したテストをする（UnitテストではなくなるのでIntegrationディレクトリを作成して移動することを検討）
     *
     * @return void
     */
    public function test_findListWithComments()
    {
        $conditions = ['postStatus' => 1, 'commentStatus' => 1];

        // TODO: Postが0件の時とCommentが0件の時のテストを追加
        $postId = $this->faker->unique()->randomDigitNotZero();
        $postCollection = Eloquent\Post::factory()
            ->count(2)
            ->make([
                'id' => $postId,
                'status' => Entities\Post::APPROVED,
                'comments' => Eloquent\Comment::factory([
                    'post_id' => $postId
                ])->count(3)->make(),
            ]);

        $this->postEloquent->shouldReceive('with->filterStatus->get')
            ->once()
            ->andReturn($postCollection);

        $this->postRepository->shouldReceive('toCommentDomainEntity')
            ->times(2 * 3)
            ->andReturnNull();

        $this->postRepository->shouldReceive('toPostDomainEntity')
            ->times(2)
            ->andReturnNull();

        $actual = $this->postRepository->findListWithComments($conditions);

        $this->assertTrue($actual instanceof Collection);
    }

    /**
     * TODO: 親クラスに定義する
     * @return mixed
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
