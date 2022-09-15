<?php

namespace App\Http\Misc;

class Paginator
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * Paginator constructor.
     * @param $perPage
     */
    public function __construct($perPage)
    {
        $request = request();
        $this->page = max((int)$request->get('page', 1), 1);
        $this->perPage = (int)$request->get('perPage', $perPage);
        $this->offset = ($this->page - 1) * $this->perPage;
        $this->limit = $this->perPage;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $val
     * @return float|int
     */
    public function setLimit(int $val)
    {
        return $this->limit = $val;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $val
     * @return float|int
     */
    public function setOffset(int $val)
    {
        return $this->offset = $val;
    }

    /**
     * @return int|mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int|mixed
     */
    public function getPerPage()
    {
        return $this->perPage;
    }
}