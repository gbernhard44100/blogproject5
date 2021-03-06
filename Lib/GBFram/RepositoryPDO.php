<?php

namespace Lib\GBFram;

class RepositoryPDO extends Repository
{

    protected $table = '';

    public function __construct(Application $app)
    {
        parent::__construct($app, PDOFactory::getMysqlConnexion($app->name()));
    }

    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException('L\'argument n\'est pas une instance de Entity');
        }

        if (static::class != $entity->getRepository()) {
            throw new \InvalidArgumentException('L\'entité en argument ne peut pas être manipuler par ce repository');
        }

        $query1 = 'INSERT INTO ' . $this->table . '(';
        $query2 = 'VALUES(';

        $reflect = new \ReflectionClass($entity);
        foreach ($reflect->getProperties() as $attribut) {
            if (!in_array($attribut->name, array('id', 'repository', 'erreurs'))) {
                $attribut->setAccessible(true);
                $query1 .= $attribut->name . ', ';
                $query2 .= '?,';
                if (is_bool($attribut->getValue($entity))) {
                    $values[] = (int) $attribut->getValue($entity);
                } else {
                    $values[] = $attribut->getValue($entity);
                }
            }
        }
        $query1 = substr($query1, 0, -2) . ') ';
        $query2 = substr($query2, 0, -1) . ')';

        $query = $query1 . $query2;
        $request = $this->dao->prepare($query);
        $request->execute($values);
    }

    /**
     * Return from a database all the entities in a range defined by the input parameters
     */
    public function getList(array $keys = [], int $offset = -1, int $limit = -1, string $sortingAttribut = 'id', bool $side = false)
    {
        $finalQuery = 'SELECT * From ' . $this->table;

        $specificQuery = ' WHERE 1 ';
        $values = [];
        foreach ($keys as $key => $value) {
            if (!empty($key) && !empty($value)) {
                $specificQuery .= 'AND ' . $key . ' = ? ';
                $values[] = $value;
            }
        }
        $finalQuery .= $specificQuery;

        $finalQuery .= ' ORDER by ' . $sortingAttribut;

        $thisSide = ' ASC';
        if ($side) {
            $thisSide = ' DESC';
        }
        $finalQuery .= $thisSide;

        if (!($offset = -1 || $limit = -1)) {
            $finalQuery .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
        }
        $request = $this->dao->prepare($finalQuery);

        $request->execute($values);

        $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'App\\' . $this->app()->name() . '\Entity\\' . ucfirst($this->table));
        $entities = $request->fetchAll();
        return $entities;
    }

    public function getUnique($id)
    {
        $request = $this->dao->prepare('SELECT * FROM ' . $this->table . ' WHERE id= :id');
        $request->bindValue(':id', $id, \PDO::PARAM_INT);
        $request->execute();
        $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'App\\' . $this->app()->name() . '\Entity\\' . ucfirst($this->table));

        $theEntity = $request->fetch();
        return $theEntity;
    }

    public function upDate(Entity $entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException('L\'argument n\'est pas une instance de Entity');
        }

        if (static::class != $entity->getRepository()) {
            throw new \InvalidArgumentException('L\'entité en argument ne peut pas être manipuler par ce repository');
        }

        $query1 = 'UPDATE ' . $this->table . ' SET ';
        $query2 = ' WHERE id= ?';

        $reflect = new \ReflectionClass($entity);
        foreach ($reflect->getProperties() as $attribut) {
            if (!in_array($attribut->name, array('id', 'repository', 'erreurs'))) {
                $attribut->setAccessible(true);
                $query1 .= $attribut->name . ' = ?, ';
                if (is_bool($attribut->getValue($entity))) {
                    $values[] = (int) $attribut->getValue($entity);
                } else {
                    $values[] = $attribut->getValue($entity);
                }
            }
        }
        $query1 = substr($query1, 0, -2);
        $query = $query1 . $query2;
        $values[] = $entity->id();
        $request = $this->dao->prepare($query);
        $request->execute($values);
    }

    public function delete($id)
    {
        $request = $this->dao->prepare('DELETE FROM ' . $this->table . ' WHERE id= :id');
        $request->bindValue(':id', $id, \PDO::PARAM_INT);
        $request->execute();
    }

}
