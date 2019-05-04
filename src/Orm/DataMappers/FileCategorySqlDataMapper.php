<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Framework\Orm\DataMappers\IdGeneratorUserTrait;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

class FileCategorySqlDataMapper extends SqlDataMapper implements IFileCategoryDataMapper
{
    const USER_GROUP_IDS = 'user_group_ids';

    use IdGeneratorUserTrait;

    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a File Category entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'file_categories',
                [
                    'id'         => [$entity->getId(), \PDO::PARAM_STR],
                    'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'name'       => [$entity->getName(), \PDO::PARAM_STR],
                    'is_public'  => [$entity->isPublic(), \PDO::PARAM_BOOL],
                ]
            );

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->addUserGroups($entity);
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a File Category entity.');
        }

        $query = (new QueryBuilder())
            ->update('file_categories', 'file_categories', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->deleteUserGroups($entity);
    }

    /**
     * @return Entity[]
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        $sql = $query->getSql();

        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        $query = $this->getBaseQuery()
            ->limit($pageSize)
            ->offset($limitFrom);

        foreach ($orders as $order) {
            $query->addOrderBy($order);
        }

        foreach ($conditions as $condition) {
            $query->andWhere($condition);
        }

        $replaceCount = 1;

        $sql = $query->getSql();
        $sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $sql, $replaceCount);

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('fc.id = :file_category_id');

        $parameters = [
            'file_category_id' => [$id, \PDO::PARAM_STR],
        ];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $userGroupId
     *
     * @return Entity[]
     */
    public function getByUserGroupId(string $userGroupId): array
    {
        $query = $this->getBaseQuery();
        $query = $this->joinUserGroups($query);
        $query = $query->andWhere('ugfc2.user_group_id = :user_group_id');

        $parameters = ['ugfc2.user_group_id' => [$userGroupId, \PDO::PARAM_STR]];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a File Category entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'file_categories',
                'file_categories',
                [
                    'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'name'       => [$entity->getName(), \PDO::PARAM_STR],
                    'is_public'  => [$entity->isPublic(), \PDO::PARAM_BOOL],
                ]
            )
            ->where('id = ?')
            ->andWhere('deleted = 0')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->deleteUserGroups($entity);
        $this->addUserGroups($entity);
    }

    /**
     * @param Entity $entity
     */
    protected function deleteUserGroups(Entity $entity)
    {
        $query = (new QueryBuilder())
            ->delete('user_groups_file_categories')
            ->where('file_category_id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    protected function addUserGroups(Entity $entity)
    {
        $idGenerator = $this->getIdGenerator();

        foreach ($entity->getUserGroups() as $userGroup) {
            $query = (new QueryBuilder())
                ->insert(
                    'user_groups_file_categories',
                    [
                        'id'               => [$idGenerator->generate($userGroup), \PDO::PARAM_STR],
                        'user_group_id'    => [$userGroup->getId(), \PDO::PARAM_STR],
                        'file_category_id' => [$entity->getId(), \PDO::PARAM_STR],
                    ]
                );

            $sql = $query->getSql();

            $statement = $this->writeConnection->prepare($sql);
            $statement->bindValues($query->getParameters());
            $statement->execute();
        }
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $userGroups = $this->getUserGroups($hash);

        return new Entity(
            $hash['id'],
            $hash['identifier'],
            $hash['name'],
            (bool)$hash['is_public'],
            $userGroups
        );
    }

    /**
     * @param array $hash
     *
     * @return array
     */
    private function getUserGroups(array $hash): array
    {
        if (empty($hash[static::USER_GROUP_IDS])) {
            return [];
        }

        if (is_array($hash[static::USER_GROUP_IDS])) {
            return $hash[static::USER_GROUP_IDS];
        }

        $userGroups = [];
        foreach (explode(',', $hash[static::USER_GROUP_IDS]) as $id) {
            $userGroups[] = new UserGroup((string)$id, '', '');
        }

        return $userGroups;
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'fc.id',
                'fc.identifier',
                'fc.name',
                'fc.is_public',
                'GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids'
            )
            ->from('file_categories', 'fc')
            ->leftJoin('user_groups_file_categories', 'ugfc', 'ugfc.file_category_id = fc.id')
            ->where('fc.deleted = 0')
            ->groupBy('fc.id');

        return $query;
    }

    /**
     * @param SelectQuery $query
     *
     * @return SelectQuery
     */
    private function joinUserGroups(SelectQuery $query): SelectQuery
    {
        $query->innerJoin(
            'user_groups_file_categories',
            'ugfc2',
            'fc.id = ugfc2.file_category_id'
        );

        return $query;
    }
}
