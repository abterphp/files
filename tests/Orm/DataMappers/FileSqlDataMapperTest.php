<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMapper;

use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\DataMappers\FileSqlDataMapper;
use AbterPhp\Framework\Orm\DataMappers\SqlTestCase;

class FileSqlDataMapperTest extends SqlTestCase
{
    /** @var FileSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new FileSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testDelete()
    {
        $id                 = '5bc63ac6-b3cd-41f0-bbc6-81a4568179db';
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 'aa961686-d042-4b43-8b0a-163d80a29166';
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $categoryIsPublic   = false;
        $uploadedAt         = new \DateTime();

        $sql    = 'UPDATE files AS files SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_STR]];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $category = new FileCategory($categoryId, $categoryIdentifier, $categoryName, $categoryIsPublic);
        $entity   = new File($id, $filesystemName, $publicName, $description, $category, $uploadedAt);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id                 = '54d0ff01-f6b7-4058-9fcd-40f847cf2aef';
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 'fc14a949-03cc-4d7a-8a71-7ee31d4d3be2';
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 WHERE (files.deleted = 0) GROUP BY files.id'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id                 = '456cdb27-c8e8-4ab5-84c0-2d20d470521f';
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 'd6ba660f-d131-4dfa-825a-81e7f3f69fcb';
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 WHERE (files.deleted = 0) AND (files.id = :file_id) GROUP BY files.id'; // phpcs:ignore
        $values       = ['file_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByUserId()
    {
        $userId = '673459fb-1f34-4339-8436-3fff0774fcf1';

        $id                 = '5574edba-c3c3-4bed-be07-72921add67b4';
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = '09da12c0-e92b-4a08-a83e-6cb573a8cf79';
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 INNER JOIN user_groups_file_categories AS ugfc ON file_categories.id = ugfc.file_category_id AND file_categories.deleted = 0 INNER JOIN user_groups AS user_groups ON user_groups.id = ugfc.user_group_id AND user_groups.deleted = 0 WHERE (files.deleted = 0) AND (user_groups.user_id = :user_id) GROUP BY files.id'; // phpcs:ignore
        $values       = ['user_id' => [$userId, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByUserId($userId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdate()
    {
        $id                 = '542260d0-25be-4088-9253-9ad2bef63ac2';
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = '29a22dcb-c4dd-4d2a-8a50-b282ed0b9b0b';
        $categoryIdentifier = 'quux';
        $categoryName       = 'qux';
        $categoryIsPublic   = false;
        $uploadedAt         = new \DateTime();

        $sql    = 'UPDATE files AS files SET filesystem_name = ?, public_name = ?, description = ?, uploaded_at = ?, file_category_id = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$filesystemName, \PDO::PARAM_STR],
            [$publicName, \PDO::PARAM_STR],
            [$description, \PDO::PARAM_STR],
            [$uploadedAt->format(File::DATE_FORMAT), \PDO::PARAM_STR],
            [$categoryId, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $category = new FileCategory($categoryId, $categoryIdentifier, $categoryName, $categoryIsPublic);
        $entity   = new File($id, $filesystemName, $publicName, $description, $category, $uploadedAt);

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param File  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $uploadedAt = $entity->getUploadedAt()->format(File::DATE_FORMAT);

        $this->assertInstanceOf(File::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['filesystem_name'], $entity->getFilesystemName());
        $this->assertSame($expectedData['public_name'], $entity->getPublicName());
        $this->assertSame($expectedData['file_category_id'], $entity->getCategory()->getId());
        $this->assertSame($expectedData['description'], $entity->getDescription());
        $this->assertSame($expectedData['uploaded_at'], $uploadedAt);
        $this->assertSame($expectedData['file_category_name'], $entity->getCategory()->getName());
        $this->assertSame($expectedData['file_category_identifier'], $entity->getCategory()->getIdentifier());
    }
}
