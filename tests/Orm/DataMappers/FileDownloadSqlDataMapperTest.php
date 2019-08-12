<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload;
use AbterPhp\Files\Orm\DataMappers\FileDownloadSqlDataMapper;
use AbterPhp\Framework\Orm\DataMappers\SqlTestCase;

class FileDownloadSqlDataMapperTest extends SqlTestCase
{
    /** @var FileDownloadSqlDataMapper */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileDownloadSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId       = '97fe8788-b98c-483d-ab8a-e0fea4d4c54c';
        $fileId       = 'ea65ff5a-8a6d-430f-9ee1-ba96e3dcea65';
        $userId       = 'bd5edd79-4dac-4de3-b670-d93ea2b8b14e';
        $downloadedAt = new \DateTime();

        $sql    = 'INSERT INTO file_downloads (id, file_id, user_id, downloaded_at) VALUES (?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$nextId, \PDO::PARAM_STR],
            [$fileId, \PDO::PARAM_STR],
            [$userId, \PDO::PARAM_STR],
            [$downloadedAt->format(FileDownload::DATE_FORMAT), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $entity = $this->createEntity($nextId, $fileId, $userId, $downloadedAt);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id           = '4ec776f2-dcb1-421a-9073-226c01cfe28d';
        $fileId       = '13bcd299-12a9-484a-8a4c-0b745a11d726';
        $userId       = '4c178710-2c4a-4658-90ea-491aadc3c32b';
        $downloadedAt = new \DateTime();

        $sql    = 'UPDATE file_downloads AS file_downloads SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_STR]];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $entity = $this->createEntity($id, $fileId, $userId, $downloadedAt);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id             = '0302b961-700f-4bbf-8d33-74c7b679cbf5';
        $fileId         = '658ee9e7-9a0e-44a3-832c-9dd2acebcd64';
        $userId         = '80f00d82-4832-4764-b74e-8ad90b7af2f1';
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $mime           = 'text/yax';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, files.mime AS mime, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'mime'            => $mime,
                'username'        => $userName,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id             = '2a73fd3f-bb4d-4a13-b762-fbe36944170d';
        $fileId         = '87366ea2-3bb2-41ef-b716-1817636074ba';
        $userId         = '3aac6c0d-f327-4645-b322-85a7d0fbd86c';
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $mime           = 'text/yax';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, files.mime AS mime, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0) AND (file_downloads.id = :file_download_id)'; // phpcs:ignore
        $values       = ['file_download_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'mime'            => $mime,
                'username'        => $userName,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id           = '78683847-4e00-423c-8e47-e0026bc83e85';
        $fileId       = '45ffd14e-69cc-4d40-a97a-a7504a481521';
        $userId       = '0515c6bb-0ab4-4375-a990-bfa103ba9447';
        $downloadedAt = new \DateTime();

        $sql    = 'UPDATE file_downloads AS file_downloads SET file_id = ?, user_id = ?, downloaded_at = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [
            [$fileId, \PDO::PARAM_STR],
            [$userId, \PDO::PARAM_STR],
            [$downloadedAt->format(FileDownload::DATE_FORMAT), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $entity = $this->createEntity($id, $fileId, $userId, $downloadedAt);

        $this->sut->update($entity);
    }

    public function testGetByFileId()
    {
        $id             = '7756106a-f150-4328-9bfe-c807ab6189a5';
        $fileId         = '31efab70-fa54-4feb-9855-a25cdadd2143';
        $userId         = '50946095-b41a-4c2d-8091-fd4bc740a28c';
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $mime           = 'text/yax';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, files.mime AS mime, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0) AND (file_id = :file_id)'; // phpcs:ignore
        $values       = ['file_id' => [$fileId, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'mime'            => $mime,
                'username'        => $userName,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByFileId($fileId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetByUserId()
    {
        $id             = 'f222f33a-2fbd-4552-abdf-577f95731e36';
        $fileId         = '80fda718-4285-48a3-b8d6-86902a275b5f';
        $userId         = '1bde0bc1-175e-45bf-a64a-80dab37ed760';
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $mime           = 'text/yax';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, files.mime AS mime, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0) AND (user_id = :user_id)'; // phpcs:ignore
        $values       = ['user_id' => [$userId, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'mime'            => $mime,
                'username'        => $userName,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByUserId($userId);

        $this->assertCollection($expectedData, $actualResult);
    }

    /**
     * @param string    $id
     * @param string    $fileId
     * @param string    $userId
     * @param \DateTime $downloadedAt
     *
     * @return FileDownload
     * @throws \Exception
     */
    protected function createEntity(string $id, string $fileId, string $userId, \DateTime $downloadedAt)
    {
        $file         = new File($fileId, '', '', '', '');
        $userLanguage = new UserLanguage('', '', '');
        $user         = new User($userId, '', '', '', true, true, $userLanguage);

        return new FileDownload($id, $file, $user, $downloadedAt);
    }

    /**
     * @param array        $expectedData
     * @param FileDownload $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $downloadedAt = $entity->getDownloadedAt()->format(FileDownload::DATE_FORMAT);

        $this->assertInstanceOf(FileDownload::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['file_id'], $entity->getFile()->getId());
        $this->assertSame($expectedData['user_id'], $entity->getUser()->getId());
        $this->assertSame($expectedData['downloaded_at'], $downloadedAt);
        $this->assertSame($expectedData['filesystem_name'], $entity->getFile()->getFilesystemName());
        $this->assertSame($expectedData['public_name'], $entity->getFile()->getPublicName());
        $this->assertSame($expectedData['mime'], $entity->getFile()->getMime());
        $this->assertSame($expectedData['username'], $entity->getUser()->getUsername());
    }
}
