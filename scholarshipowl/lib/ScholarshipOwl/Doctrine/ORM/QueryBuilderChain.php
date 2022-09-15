<?php namespace ScholarshipOwl\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

class QueryBuilderChain
{
    /**
     * @var array|ChainMemberInterface[]
     */
    protected $members = [];

    /**
     * Add processing member to chain.
     *
     * @param ChainMemberInterface|callable $member
     * @param int|null                      $priority
     *
     * @return $this|QueryBuilderChain
     */
    public function add($member, int $priority = null)
    {
        if (!is_callable($member) && !$member instanceof ChainMemberInterface) {
            throw new \InvalidArgumentException(
                sprintf('Member should be callable or implement %s', ChainMemberInterface::class)
            );
        }

        $priority = $member instanceof ChainMemberInterface && !$priority ? $member::DEFAULT_PRIORITY : $priority;
        $priority = $priority === null ? max(array_keys($this->members)) + 1 : $priority;

        $this->members[$priority] = $member;

        return $this;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return QueryBuilder
     */
    public function process(QueryBuilder $queryBuilder) : QueryBuilder
    {
        ksort($this->members);
        foreach ($this->members as $member) {
            if ($member instanceof ChainMemberInterface) {
                $queryBuilder = $member->handle($queryBuilder);
            } elseif (is_callable($member)) {
                if (!($queryBuilder = call_user_func($member, $queryBuilder)) instanceof QueryBuilder) {
                    throw new \RuntimeException(sprintf('Callable return not QueryBuilder after process!'));
                }
            } else {
                throw new \InvalidArgumentException('Unprocessible chain member!');
            }
        }

        return $queryBuilder;
    }
}
