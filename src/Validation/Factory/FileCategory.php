<?php

declare(strict_types=1);

namespace AbterPhp\Files\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class FileCategory extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->uuid();

        $validator
            ->field('name')
            ->required();

        return $validator;
    }
}
