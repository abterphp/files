<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Filters;

use AbterPhp\Framework\Grid\Component\Filters;
use AbterPhp\Framework\Grid\Filter\ExactFilter;
use AbterPhp\Framework\Grid\Filter\LikeFilter;

class FileCategory extends Filters
{
    /**
     * FileCategory constructor.
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(array $intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct($intents, $attributes, $tag);

        $this->nodes[] = new ExactFilter('identifier', 'files:fileCategoryIdentifier');

        $this->nodes[] = new LikeFilter('name', 'files:fileCategoryName');
    }
}
