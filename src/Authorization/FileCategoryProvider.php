<?php

declare(strict_types=1);

namespace AbterPhp\Files\Authorization;

use AbterPhp\Admin\Authorization\PolicyProviderTrait;
use Casbin\Exceptions\CasbinException;
use Casbin\Model\Model;
use Casbin\Persist\Adapter as CasbinAdapter;
use AbterPhp\Files\Databases\Queries\FileCategoryAuthLoader;

class FileCategoryProvider implements CasbinAdapter
{
    use PolicyProviderTrait;

    const PREFIX = 'file_category';

    /**
     * FileCategory constructor.
     *
     * @param FileCategoryAuthLoader $fileCategoryAuth
     */
    public function __construct(FileCategoryAuthLoader $fileCategoryAuth)
    {
        $this->authQueries = $fileCategoryAuth;
        $this->prefix      = static::PREFIX;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Model $model
     *
     * @return bool
     */
    public function savePolicy($model)
    {
        return true;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     *
     * @return void
     */
    public function addPolicy($sec, $ptype, $rule)
    {
        return;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     *
     * @return int
     */
    public function removePolicy($sec, $ptype, $rule)
    {
        $count = 0;

        return $count;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param       $sec
     * @param       $ptype
     * @param       $fieldIndex
     * @param mixed ...$fieldValues
     *
     * @throws CasbinException
     */
    public function removeFilteredPolicy($sec, $ptype, $fieldIndex, ...$fieldValues)
    {
        throw new CasbinException('not implemented');
    }
}
