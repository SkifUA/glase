<?php
/**
 * Created by PhpStorm.
 * User: valeriy
 * Date: 08.10.16
 * Time: 19:18
 */

namespace Application\Model;


use Application\Exception\GridException;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

class Grid
{
    const DEFAULT_DATA_TIME_FORMAT = 'd-M-Y H:i:s';
    const DEFAULT_ROWS_COUNT = [10, 20, 30, 40, 50];
    /**
     * @var object EntityManager
     */
    protected $em;

    /**
     * @var object AuthStorage
     */
    protected $authStorage;

    /**
     * @var object
     */
    protected $entity;

    /**
     * @var array
     */
    protected $availableColumns;

    /**
     * @var array
     */
    protected $aliasColumns;

    /**
     * @var array
     */
    protected $orderColumns;

    /**
     * @var array
     */
    protected $allColumns;

    /**
     * Grid constructor.
     * @param $entity
     * @param $em
     * @param $authStorage
     */
    public function __construct($entity, $em, $authStorage)
    {
        $this->entity = $entity;
        $this->em = $em;
        $this->authStorage = $authStorage;
    }

    /**
     * @param $entity
     * @param null $text
     * @param string $column
     * @param string $order
     * @param null $search
     * @return Paginator
     */
    public function getListByParameters(
        $text=null,
        $column='id',
        $order='ASC',
        $search=[]
    )
    {
        $nik = 'g';
        $entity = $this->entity->getClass();

        $query = $this->em->createQueryBuilder()
            ->select($nik)
            ->from($entity, $nik);
        if ($text) {
            foreach ($search as $key => $value) {
                $query = $query->orWhere($nik . '.' . $value . ' LIKE :text' . $key);
                $query = $query->setParameter('text' . $key, '%' . $text . '%');
            }
        }

        $query = $query->orderBy($nik . '.' . $column, $order)
            ->getQuery()
            ->getResult();
        
        return $this->getPaginator($query);
    }

    /**
     * @param $query
     * @return Paginator
     */
    public function getPaginator($query)
    {
        return new Paginator(new ArrayAdapter($query));
    }

    /**
     * @param array $aliases
     * @return array
     * @throws GridException
     */
    public function getAliasColumns($aliases=[])
    {
        if (!is_array($aliases)) {
            throw new GridException('Data aliases is not array');
        }

        if ($this->aliasColumns) {
            return $this->aliasColumns;
        }

        $result = [];
        foreach ($this->getAllColumns() as $key=>$value) {
            $result[$key] = $key;

            if (isset($aliases[$key])) {
                $result[$key] = $aliases[$key];
            }
        }
        $this->aliasColumn = $result;
        return $result;
    }

    /**
     * @return array
     */
    public function getAvailableColumns()
    {
        if ($this->availableColumns) {
            return $this->availableColumns;
        }
        $allowColumn = $this->getAllowColumns();

        $result = [];
        foreach ($this->getAllColumns() as $key=>$value) {
            if (array_key_exists($key, $allowColumn)) {
                $result[] = $key;
            }
        }

        $this->availableColumn = $result;

        return $result;
    }

    /**
     * @return mixed
     * make list allow column by role
     */
    protected function getAllowColumns()
    {
        return $this->entity->getArrayCopy();
    }

    /**
     * @return array
     */
    protected function getAllColumns()
    {
        if (!$this->allColumns) {
            $this->allColumns = $this->entity->getArrayCopy();
        }
        return $this->allColumns;
    }

    /**
     * @param array $order
     * @return array
     * @throws GridException
     */
    public function getOrderColumns($order=[], $noShow=[])
    {
        if (!is_array($order)) {
            throw new GridException('Order columns is not array');
        }

        $result = [];
        $queue = [];
        foreach ($this->getAvailableColumns() as $key=>$value) {

            if (in_array($value, $noShow)) {
                continue;
            }

            if (in_array($value, $result)) {
                continue;
            }

            if (array_key_exists($key, $order)) {
                $queue[] = $value;
                $result[] = $order[$key];
                continue;
            }
            $result = array_merge($result, $queue);
            $queue = [];
            $result[] = $value;
        }
        $this->orderColumns = $result;
        return $result;
    }

    /**
     * @param $column
     * @param $order
     * @return string
     */
    static function getIdActiveSpanForColumns($column, $order)
    {
        return $column . ucfirst(strtolower($order));
    }

    /**
     * @param $entity
     */
    protected function setEntity($entity)
    {
        $this->entity = $entity;
    }

}