<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Filters;

use AbterPhp\Framework\Grid\Component\Filters;

class FileDownload extends Filters
{
    /**
     * FileDownload constructor.
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(array $intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct($intents, $attributes, $tag);
    }
}
