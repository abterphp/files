<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class FileCategory extends HeaderFactory
{
    public const GROUP_NAME       = 'fileCategory-name';
    public const GROUP_IS_PUBLIC  = 'fileCategory-is-public';
    public const GROUP_IDENTIFIER = 'fileCategory-identifier';

    private const HEADER_NAME       = 'files:fileCategoryName';
    private const HEADER_IS_PUBLIC  = 'files:fileCategoryIsPublic';
    private const HEADER_IDENTIFIER = 'files:fileCategoryIdentifier';

    /** @var array */
    protected $headers = [
        self::GROUP_NAME       => self::HEADER_NAME,
        self::GROUP_IS_PUBLIC  => self::HEADER_IS_PUBLIC,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_NAME       => 'name',
        self::GROUP_IDENTIFIER => 'identifier',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_NAME       => 'file_categories.name',
        self::GROUP_IDENTIFIER => 'file_categories.identifier',
    ];
}
