<?php

declare(strict_types=1);

namespace AbterPhp\Files\Domain\Entities;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class FileCategory implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isPublic;

    /** @var UserGroup[] */
    protected $userGroups;

    /**
     * @param string $id
     * @param string $identifier
     * @param string $name
     * @param bool   $isPublic
     * @param array  $userGroups
     */
    public function __construct(string $id, string $identifier, string $name, bool $isPublic, array $userGroups = [])
    {
        $this->id         = $id;
        $this->identifier = $identifier;
        $this->name       = $name;
        $this->isPublic   = $isPublic;
        $this->userGroups = $userGroups;
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
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier(string $identifier): FileCategory
    {
        $this->identifier = $identifier;

        return $this;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): FileCategory
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     *
     * @return $this
     */
    public function setIsPublic(bool $isPublic): FileCategory
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return UserGroup[]
     */
    public function getUserGroups(): array
    {
        return $this->userGroups;
    }

    /**
     * @param UserGroup[] $userGroups
     *
     * @return $this
     */
    public function setUserGroups(array $userGroups): FileCategory
    {
        $this->userGroups = $userGroups;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
