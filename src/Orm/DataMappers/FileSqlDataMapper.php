<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\Conditions\ConditionFactory;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

class FileSqlDataMapper extends SqlDataMapper implements IFileDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a File entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'files',
                [
                    'id'               => [$entity->getId(), \PDO::PARAM_STR],
                    'filesystem_name'  => [$entity->getFilesystemName(), \PDO::PARAM_STR],
                    'public_name'      => [$entity->getPublicName(), \PDO::PARAM_STR],
                    'mime'             => [$entity->getMime(), \PDO::PARAM_STR],
                    'description'      => [$entity->getDescription(), \PDO::PARAM_STR],
                    'file_category_id' => [$entity->getCategory()->getId(), \PDO::PARAM_STR],
                    'uploaded_at'      => [$entity->getUploadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
                ]
            );

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a File entity.');
        }

        $query = (new QueryBuilder())
            ->update('files', 'files', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
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
     * @return Entity
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('files.id = :file_id');

        $params = [
            'file_id' => [$id, \PDO::PARAM_STR],
        ];
        $sql    = $query->getSql();

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $filesystemName
     *
     * @return Entity
     */
    public function getByFilesystemName(string $filesystemName): Entity
    {
        $query = $this->getBaseQuery()->andWhere('files.filesystem_name = :filesystem_name');

        $params = [
            'filesystem_name' => [$filesystemName, \PDO::PARAM_STR],
        ];
        $sql    = $query->getSql();

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $filesystemName
     *
     * @return Entity
     */
    public function getPublicByFilesystemName(string $filesystemName): Entity
    {
        $query = $this->getBaseQuery()
            ->andWhere('files.filesystem_name = :filesystem_name')
            ->andWhere('file_categories.is_public = 1');

        $params = [
            'filesystem_name' => [$filesystemName, \PDO::PARAM_STR],
        ];
        $sql    = $query->getSql();

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getPublicByCategoryIdentifiers(array $identifiers): array
    {
        if (count($identifiers) === 0) {
            return [];
        }

        $conditions = new ConditionFactory();
        $query      = $this
            ->withUser($this->withUserGroup($this->getBaseQuery()))
            ->andWhere($conditions->in('file_categories.identifier', $identifiers));

        $sql    = $query->getSql();
        $params = $query->getParameters();

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param string $userId
     *
     * @return Entity[]
     */
    public function getByUserId(string $userId): array
    {
        $query = $this
            ->withUserGroup($this->getBaseQuery())
            ->andWhere('user_groups.user_id = :user_id');

        $sql    = $query->getSql();
        $params = [
            'user_id' => [$userId, \PDO::PARAM_STR],
        ];

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param Entity $entity
     *
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function update($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a File entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'files',
                'files',
                [
                    'filesystem_name'  => [$entity->getFilesystemName(), \PDO::PARAM_STR],
                    'public_name'      => [$entity->getPublicName(), \PDO::PARAM_STR],
                    'mime'             => [$entity->getMime(), \PDO::PARAM_STR],
                    'description'      => [$entity->getDescription(), \PDO::PARAM_STR],
                    'uploaded_at'      => [$entity->getUploadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
                    'file_category_id' => [$entity->getCategory()->getId(), \PDO::PARAM_STR],
                ]
            )
            ->where('id = ?')
            ->andWhere('deleted = 0')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity|object
     * @throws \Exception
     */
    protected function loadEntity(array $hash)
    {
        $category = new FileCategory(
            $hash['file_category_id'],
            (string)$hash['file_category_identifier'],
            (string)$hash['file_category_name'],
            (bool)$hash['file_category_name']
        );

        try {
            $uploadedAt = new \DateTime((string)$hash['uploaded_at']);
        } catch (\Exception $e) {
            $uploadedAt = null;
        }

        return new Entity(
            $hash['id'],
            $hash['filesystem_name'],
            $hash['public_name'],
            $hash['mime'],
            $hash['description'],
            $category,
            $uploadedAt,
            true
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'files.id',
                'files.filesystem_name',
                'files.public_name',
                'files.mime',
                'files.file_category_id',
                'files.description',
                'files.uploaded_at',
                'file_categories.name AS file_category_name',
                'file_categories.identifier AS file_category_identifier'
            )
            ->from('files')
            ->innerJoin(
                'file_categories',
                'file_categories',
                'file_categories.id = files.file_category_id AND file_categories.deleted =0'
            )
            ->where('files.deleted = 0')
            ->groupBy('files.id');

        return $query;
    }

    /**
     * @param SelectQuery $selectQuery
     *
     * @return SelectQuery
     */
    private function withUserGroup(SelectQuery $selectQuery): SelectQuery
    {
        /** @var SelectQuery $query */
        $selectQuery
            ->innerJoin(
                'user_groups_file_categories',
                'ugfc',
                'file_categories.id = ugfc.file_category_id AND file_categories.deleted = 0'
            )
            ->innerJoin(
                'user_groups',
                'user_groups',
                'user_groups.id = ugfc.user_group_id AND user_groups.deleted = 0'
            );

        return $selectQuery;
    }

    /**
     * @param SelectQuery $selectQuery
     *
     * @return SelectQuery
     */
    private function withUser(SelectQuery $selectQuery): SelectQuery
    {
        /** @var SelectQuery $query */
        $selectQuery
            ->innerJoin(
                'users',
                'users',
                'users.user_group_id = user_groups.id AND users.deleted = 0'
            );

        return $selectQuery;
    }
}
