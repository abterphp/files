<?php

declare(strict_types=1);

namespace AbterPhp\Files\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
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
            ]
        );

        $this->sut = new File($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorSuccessProvider(): array
    {
        return [
            'empty-data'                          => [
                [],
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
            ],
        ];
    }

    /**
     * @dataProvider createValidatorSuccessProvider
     *
     * @param array $data
     */
    public function testCreateValidatorSuccess(array $data)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertTrue($actualResult);
    }

    /**
     * @return array
     */
    public function createValidatorFailureProvider(): array
    {
        return [
            'invalid-id-present'        => [
                [
                    'id'          => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
            ],
            'invalid-category-not-uuid' => [
                [
                    'category_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b05',
                ],
            ],
        ];
    }

    /**
     * @dataProvider createValidatorFailureProvider
     *
     * @param array $data
     */
    public function testCreateValidatorFailure(array $data)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertFalse($actualResult);
    }
}
