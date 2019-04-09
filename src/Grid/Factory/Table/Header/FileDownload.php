<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class FileDownload extends HeaderFactory
{
    const GROUP_ID            = 'fileDownload-id';
    const GROUP_FILE          = 'fileDownload-file';
    const GROUP_USER          = 'fileDownload-user';
    const GROUP_DOWNLOADED_AT = 'fileDownload-downloaded-at';

    const HEADER_ID            = 'files:fileDownloadId';
    const HEADER_FILE          = 'files:fileDownloadFile';
    const HEADER_USER          = 'files:fileDownloadUser';
    const HEADER_DOWNLOADED_AT = 'files:fileDownloadDownloadedAt';

    /** @var array */
    protected $headers = [
        self::GROUP_ID            => self::HEADER_ID,
        self::GROUP_FILE          => self::HEADER_FILE,
        self::GROUP_USER          => self::HEADER_USER,
        self::GROUP_DOWNLOADED_AT => self::HEADER_DOWNLOADED_AT,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID => 'id',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID => 'file_downloads.id',
    ];
}
