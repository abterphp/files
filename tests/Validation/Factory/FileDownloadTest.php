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

class FileDownloadTest extends TestCase
{
    /** @var FileDownload - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            ['forbidden' => new Forbidden(), 'uuid' => new Uuid()]
        );

        $this->sut = new FileDownload($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'               => [
                [],
                false,
            ],
            'valid-data'               => [
                [
                    'user_id' => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'file_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
                true,
            ],
            'invalid-user-id-present'  => [
                [
                    'id'      => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'user_id' => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'file_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
                false,
            ],
            'invalid-user-id-not-uuid' => [
                [
                    'user_id' => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'file_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
                false,
            ],
            'invalid-user-id-missing'  => [
                [
                    'file_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
                false,
            ],
            'invalid-user-id-empty'    => [
                [
                    'user_id' => '',
                    'file_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b051',
                ],
                false,
            ],
            'invalid-file-id-not-uuid' => [
                [
                    'user_id' => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'file_id' => '69da7b0b-8315-43c9-8f5d-a6a5ea09b05',
                ],
                false,
            ],
            'invalid-file-missing'     => [
                [
                    'user_id' => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                ],
                false,
            ],
            'invalid-file-empty'       => [
                [
                    'user_id' => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'file_id' => '',
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
