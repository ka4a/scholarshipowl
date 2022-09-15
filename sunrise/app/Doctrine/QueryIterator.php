<?php namespace App\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class QueryIterator implements \Iterator, \Countable
{
    /**
     * @var Query
     */
    protected $query;

    /**
     * @var int
     */
    protected $start = 0;

    /**
     * @var int
     */
    protected $hydrate = Query::HYDRATE_OBJECT;

    /**
     * @var int
     */
    protected $limit = 1000;

    /**
     * @var bool
     */
    private $valid = false;

    /**
     * @var array
     */
    private $current = false;

    /**
     * QueryIterator constructor.
     *
     * @param Query $query
     * @param int   $limit
     * @param int   $start
     * @param int   $hydrate
     */
    public function __construct(Query $query, $limit = 1000, $start = 0, $hydrate = Query::HYDRATE_OBJECT)
    {
        $this->query = $query;
        $this->limit = $limit;
        $this->start = $start;
        $this->hydrate = $hydrate;
    }

    /**
     * @param Query $query
     * @param int   $limit
     * @param int   $start
     * @param int   $hydrate
     *
     * @return static
     */
    public static function create(Query $query, $limit = 1000, $start = 0, $hydrate = Query::HYDRATE_OBJECT)
    {
        return new static($query, $limit, $start, $hydrate);
    }

    /**
     * @return array
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->start = 0;
        $this->updateCurrent();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->start;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->start += $this->limit;
        $this->updateCurrent();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     * @return int
     */
    public function count()
    {
        return (new Paginator($this->query, false))->count();
    }

    /**
     * @return array
     */
    protected function updateCurrent()
    {
        $result = $this->query
            ->setMaxResults($this->limit)
            ->setFirstResult($this->start)
            ->getResult($this->hydrate);

        $this->valid = !empty($result);
        $this->current = !empty($result) ? $result : false;
    }
}
