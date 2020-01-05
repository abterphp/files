<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class File extends HeaderFactory
{
    public const GROUP_FILENAME    = 'file-filename';
    public const GROUP_CATEGORY    = 'file-category';
    public const GROUP_DESCRIPTION = 'file-description';
    public const GROUP_UPLOADED_AT = 'file-uploaded-at';

    private const HEADER_PUBLIC_NAME = 'files:filePublicName';
    private const HEADER_CATEGORY    = 'files:fileCategory';
    private const HEADER_DESCRIPTION = 'files:fileDescription';
    private const HEADER_UPLOADED_AT = 'files:fileUploadedAt';

    /** @var array */
    protected $headers = [
        self::GROUP_FILENAME    => self::HEADER_PUBLIC_NAME,
        self::GROUP_CATEGORY    => self::HEADER_CATEGORY,
        self::GROUP_DESCRIPTION => self::HEADER_DESCRIPTION,
        self::GROUP_UPLOADED_AT => self::HEADER_UPLOADED_AT,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_FILENAME => 'public-name',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_FILENAME => 'files.public_name',
    ];
}
