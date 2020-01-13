<?php

declare(strict_types=1);

namespace AbterPhp\Files\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Forbidden;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileCategoryTest extends TestCase
{
    /** @var FileCategory - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory($this, ['forbidden' => new Forbidden()]);

        $this->sut = new FileCategory($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'           => [
                [],
                false,
            ],
            'valid-data'           => [
                [
                    'name' => 'foo',
                ],
                true,
            ],
            'invalid-id-present'   => [
                [
                    'id'   => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'name' => 'foo',
                ],
                false,
            ],
            'invalid-name-missing' => [
                [
                    'id' => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                ],
                false,
            ],
            'invalid-name-empty'   => [
                [
                    'id'   => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'name' => '',
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
