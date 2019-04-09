<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class FileCategory extends HeaderFactory
{
    const GROUP_ID         = 'fileCategory-id';
    const GROUP_IDENTIFIER = 'fileCategory-identifier';
    const GROUP_NAME       = 'fileCategory-name';
    const GROUP_IS_PUBLIC  = 'fileCategory-is-public';

    const HEADER_ID         = 'files:fileCategoryId';
    const HEADER_IDENTIFIER = 'files:fileCategoryIdentifier';
    const HEADER_NAME       = 'files:fileCategoryName';
    const HEADER_IS_PUBLIC  = 'files:fileCategoryIsPublic';

    /** @var array */
    protected $headers = [
        self::GROUP_ID         => self::HEADER_ID,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_NAME       => self::HEADER_NAME,
        self::GROUP_IS_PUBLIC  => self::HEADER_IS_PUBLIC,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID         => 'id',
        self::GROUP_IDENTIFIER => 'identifier',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID         => 'file_categories.id',
        self::GROUP_IDENTIFIER => 'file_categories.identifier',
    ];
}
