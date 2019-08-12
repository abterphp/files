<?php

declare(strict_types=1);

namespace AbterPhp\Files\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use DateTime;

class File implements IStringerEntity
{
    const DATE_FORMAT = 'Y-m-d';

    /** @var string */
    protected $id;

    /** @var string */
    protected $filesystemName;

    /** @var string */
    protected $oldFilesystemName;

    /** @var string */
    protected $publicName;

    /** @var string */
    protected $mime;

    /** @var string */
    protected $description;

    /** @var DateTime */
    protected $uploadedAt;

    /** @var FileCategory */
    protected $category;

    /** @var bool */
    protected $writable;

    /** @var string|null */
    protected $content;

    /**
     * File constructor.
     *
     * @param string            $id
     * @param string            $filesystemName
     * @param string            $publicName
     * @param string            $mime
     * @param string            $description
     * @param FileCategory|null $category
     * @param DateTime|null     $uploadedAt
     * @param bool              $writable
     * @param string|null       $content
     *
     * @throws \Exception
     */
    public function __construct(
        string $id,
        string $filesystemName,
        string $publicName,
        string $mime,
        string $description,
        FileCategory $category = null,
        DateTime $uploadedAt = null,
        bool $writable = false,
        ?string $content = null
    ) {
        $this->id                = $id;
        $this->filesystemName    = $filesystemName;
        $this->oldFilesystemName = $filesystemName;
        $this->publicName        = $publicName;
        $this->mime              = $mime;
        $this->description       = $description;
        $this->category          = $category;
        $this->uploadedAt        = $uploadedAt ?: new DateTime();
        $this->writable          = $writable;
        $this->content           = $content;
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
     * @return string
     */
    public function getFilesystemName(): string
    {
        return $this->filesystemName;
    }

    /**
     * @param string $filesystemName
     *
     * @return File
     */
    public function setFilesystemName(string $filesystemName): File
    {
        $this->filesystemName = $filesystemName;

        return $this;
    }

    /**
     * @return string
     */
    public function getOldFilesystemName(): string
    {
        return $this->oldFilesystemName;
    }

    /**
     * @return bool
     */
    public function isFileUploaded(): bool
    {
        return $this->oldFilesystemName !== $this->filesystemName;
    }

    /**
     * @return string
     */
    public function getPublicName(): string
    {
        return $this->publicName;
    }

    /**
     * @param string $publicName
     *
     * @return File
     */
    public function setPublicName(string $publicName): File
    {
        $this->publicName = $publicName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     *
     * @return File
     */
    public function setMime(string $mime): File
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return File
     */
    public function setDescription(string $description): File
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return FileCategory
     */
    public function getCategory(): FileCategory
    {
        if (null === $this->category) {
            throw new \RuntimeException('Category is missing from file');
        }

        return $this->category;
    }

    /**
     * @param FileCategory $category
     *
     * @return File
     */
    public function setCategory(FileCategory $category): File
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUploadedAt(): DateTime
    {
        return $this->uploadedAt;
    }

    /**
     * @param DateTime $uploadedAt
     *
     * @return File
     */
    public function setUploadedAt(DateTime $uploadedAt): File
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @param bool $writable
     *
     * @return $this
     */
    public function setWritable(bool $writable): File
    {
        $this->writable = $writable;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        return !($this->content === null);
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (!$this->publicName) {
            return '#' . $this->getId();
        }

        return $this->publicName;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        $data = [
            'id'          => $this->getId(),
            'name'        => $this->getPublicName(),
            'mime'        => $this->getMime(),
            'description' => $this->getDescription(),
            'category_id' => $this->getCategory()->getId(),
            'uploaded_at' => $this->getUploadedAt()->format(\DateTime::ISO8601),
        ];

        if ($this->hasContent()) {
            $data['data'] = $this->getContent();
        }

        return json_encode($data);
    }
}
