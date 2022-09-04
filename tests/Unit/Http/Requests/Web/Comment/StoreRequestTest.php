<?php

namespace Tests\Unit\Http\Requests\Web\Comment;

use App\Domain\Entities\Comment;
use App\Http\Requests\Web\Comment\StoreRequest;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreRequestTest extends TestCase
{
    /** @var StoreRequest */
    protected StoreRequest $formRequest;

    /** @var Generator */
    protected Generator $faker;

    /**
     * @param string|null $name
     * @param array $data
     * @param int|string $dataName
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
        $this->formRequest = new StoreRequest();
    }

    /**
     * @param array $data
     * @param bool $expected
     *
     * @dataProvider validationDataProvider
     * @return void
     */
    public function testValidate(array $data, bool $expected)
    {
        $rules = $this->formRequest->rules();

        $validator = Validator::make($data, $rules);
        $actual = $validator->passes();

        $this->assertSame($actual, $expected);
    }

    /**
     * @return array[]
     */
    public function validationDataProvider(): array
    {
        return [
            'ok' => [
                'data' => [
                    'title' => str_repeat('a', Comment::TITLE_MAX_LENGTH),
                    'body' => str_repeat('a', Comment::MAX_LENGTH),
                ],
                'expected' => true,
            ],
            'ng:タイトルは必須' => [
                'data' => [
                    'title' => '',
                    'body' => $this->faker->text(Comment::MAX_LENGTH),
                ],
                'expected' => false,
            ],
            'ng:本文は必須' => [
                'data' => [
                    'title' => $this->faker->text(Comment::TITLE_MAX_LENGTH),
                    'body' => '',
                ],
                'expected' => false,
            ],
            'ng:タイトル文字数オーバー' => [
                'data' => [
                    'title' => str_repeat('a', Comment::TITLE_MAX_LENGTH + 1),
                    'body' => $this->faker->text(Comment::MAX_LENGTH),
                ],
                'expected' => false,
            ],
            'ng:本文文字数オーバー' => [
                'data' => [
                    'title' => $this->faker->text(Comment::TITLE_MAX_LENGTH),
                    'body' => str_repeat('a', Comment::MAX_LENGTH + 1),
                ],
                'expected' => false,
            ],
        ];
    }
}
