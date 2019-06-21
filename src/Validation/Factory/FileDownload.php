<?php

declare(strict_types=1);

namespace AbterPhp\Files\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class FileDownload extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('user_id')
            ->uuid()
            ->required();

        $validator
            ->field('file_id')
            ->uuid()
            ->required();

        return $validator;
    }
}
