<?php namespace App\Services;

use App\Entity\Cms;
use App\Entity\Repository\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;

class CmsService
{

    const CACHE_TTL = 180 * 60;
    const CACHE_TAGS = ['cms'];
    const CACHE_KEY_ENTITY_URL = 'cms.entity.url.%s';

    const DEFAULT_TITLE = "ScholarshipOwl - hundreds of scholarships one click away";
    const DEFAULT_AUTHOR = "ScholarshipOwl";
    const DEFAULT_KEYWORDS = "students, education, scholarship consultants, apply for scholarship, graduate debt free".
        ", financial aid, account managers";
    const DEFAULT_DESCRIPTION = "Scholarship Owl is a collection of dedicated professionals looking to make finding".
        " money easier for students.";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \SplObjectStorage
     */
    protected $entities;

    /**
     * @var \Illuminate\Cache\TaggedCache
     */
    protected $cache;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * CmsService constructor.
     *
     * @param EntityManager $entityManager
     * @param Repository    $cache
     */
    public function __construct(EntityManager $entityManager, Repository $cache)
    {
        $this->cache = $cache->tags(static::CACHE_TAGS);
        $this->entities = new \SplObjectStorage();
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository(Cms::class);
    }

    /**
     * Clear CMS caches
     */
    public function clear()
    {
        $this->entities = new \SplObjectStorage();
        $this->cache->flush();
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->entity() ? $this->entity()->getTitle() : static::DEFAULT_TITLE;
    }

    /**
     * @return string
     */
    public function author()
    {
        return $this->entity() ? $this->entity()->getAuthor() : static::DEFAULT_AUTHOR;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->entity() ? $this->entity()->getDescription() : static::DEFAULT_DESCRIPTION;
    }

    /**
     * @return string
     */
    public function keywords()
    {
        return $this->entity() ? $this->entity()->getKeywords() : static::DEFAULT_KEYWORDS;
    }

    /**
     * @param Request|null $request
     *
     * @return Cms|null
     */
    public function entity(Request $request = null)
    {
        /** @var Request $request */
        $request = $request ?: \Request::getFacadeRoot();

        if (!isset($this->entities[$request])) {
            $this->entities[$request] = $this->findByUrl($this->formatUrl($request->path()));
        }

        return $this->entities[$request];
    }

    /**
     * @param string $url
     *
     * @return Cms|null
     */
    public function findByUrl(string $url)
    {
        $key = sprintf(static::CACHE_KEY_ENTITY_URL, $url);

        if (!$this->cache->has($key)) {
            $this->cache->put($key, $this->repository->findOneBy(['url' => $url]), static::CACHE_TTL);
        }

        return $this->cache->get($key);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function formatUrl(string $url)
    {
        return '/' . trim($url, '/');
    }
}
