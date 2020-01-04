<?php

declare(strict_types=1);

namespace AbterPhp\Files\Domain\Entities;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use DateTime;

class FileDownload implements IStringerEntity
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var string */
    protected $id;

    /** @var File */
    protected $file;

    /** @var User */
    protected $user;

    /** @var DateTime */
    protected $downloadedAt;

    /**
     * @param string        $id
     * @param File          $file
     * @param User          $user
     * @param DateTime|null $downloadedAt
     */
    public function __construct(string $id, File $file, User $user, DateTime $downloadedAt = null)
    {
        $this->id           = $id;
        $this->file         = $file;
        $this->user         = $user;
        $this->downloadedAt = $downloadedAt ?: new DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return DateTime
     */
    public function getDownloadedAt(): DateTime
    {
        return $this->downloadedAt;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '#' . $this->getId();
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        return [
            'id'            => $this->getId(),
            'file'          => [
                'id' => $this->getFile()->getId(),
            ],
            'user'          => [
                'id' => $this->getUser()->getId(),
            ],
            'downloaded_at' => $this->getDownloadedAt(),
        ];
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toData());
    }
}
