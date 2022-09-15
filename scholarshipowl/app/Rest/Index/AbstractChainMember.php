<?php namespace App\Rest\Index;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use ScholarshipOwl\Doctrine\ORM\ChainMemberInterface;

abstract class AbstractChainMember implements ChainMemberInterface
{
    use ValidatesRequests;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @inheritdoc
     */
    abstract public function handle(QueryBuilder $qb) : QueryBuilder;

    /**
     * AbstractChainMember constructor.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request|null
     */
    protected function getRequest()
    {
        return $this->request;
    }
}
