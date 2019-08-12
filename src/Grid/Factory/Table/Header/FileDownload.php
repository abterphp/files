<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class FileDownload extends HeaderFactory
{
    const GROUP_FILE          = 'fileDownload-file';
    const GROUP_USER          = 'fileDownload-user';
    const GROUP_DOWNLOADED_AT = 'fileDownload-downloaded-at';

    const HEADER_FILE          = 'files:fileDownloadFile';
    const HEADER_USER          = 'files:fileDownloadUser';
    const HEADER_DOWNLOADED_AT = 'files:fileDownloadDownloadedAt';

    /** @var array */
    protected $headers = [
        self::GROUP_FILE          => self::HEADER_FILE,
        self::GROUP_USER          => self::HEADER_USER,
        self::GROUP_DOWNLOADED_AT => self::HEADER_DOWNLOADED_AT,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_FILE          => 'filename',
        self::GROUP_USER          => 'username',
        self::GROUP_DOWNLOADED_AT => 'downloaded_at',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_FILE          => 'files.public_name',
        self::GROUP_DOWNLOADED_AT => 'users.username',
    ];
}
