<?php

declare(strict_types=1);

namespace AbterPhp\Files\Validation\Factory\Api;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Base64;
use AbterPhp\Framework\Validation\Rules\Forbidden;
use AbterPhp\Framework\Validation\Rules\Uuid;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /** @var File - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            [
                'forbidden' => new Forbidden(),
                'uuid'      => new Uuid(),
                'base64'    => new Base64(),
            ]
        );

        $this->sut = new File($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                          => [
                [],
                false,
            ],
            'valid-data'                          => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                true,
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'description' => 'foo',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                true,
            ],
            'invalid-id-present'                  => [
                [
                    'id'          => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-description-missing'         => [
                [
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-description-empty'           => [
                [
                    'description' => '',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-category-not-uuid'           => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b05',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-data'                        => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => '()*!@#$%)',
                    'name'        => 'bar',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-name-missing'                => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-name-empty'                  => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => '',
                    'mime'        => 'text/plain',
                ],
                false,
            ],
            'invalid-mime-missing'                => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                ],
                false,
            ],
            'invalid-mime-empty'                  => [
                [
                    'description' => 'foo',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                    'data'        => 'aGVsbG8sIHdvcmxkIQ==',
                    'name'        => 'bar',
                    'mime'        => '',
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider createValidatorProvider
     *
     * @param array $data
     * @param bool  $expectedResult
     */
    public function testCreateValidator(array $data, bool $expectedResult)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
