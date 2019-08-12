<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table;

use AbterPhp\Admin\Grid\Factory\TableFactory;
use AbterPhp\Admin\Grid\Factory\Table\BodyFactory;
use AbterPhp\Files\Grid\Factory\Table\Header\FileCategory as HeaderFactory;

class FileCategory extends TableFactory
{
    /**
     * FileCategory constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param BodyFactory   $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, BodyFactory $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
