<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Repository\EntityRepository;
use App\Entity\UnsubscribedEmail;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\AdapterInterface;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;
use Symfony\Component\HttpFoundation\Response;
use Wilgucki\Csv\Writer;

class UnsubscribeEmailService
{

    const UNSUBSCRIBED_FILENAME = 'unsubscribed.csv';

    const UNSUBSCRIBED_CLOUD_PATH = 'ul/' . self::UNSUBSCRIBED_FILENAME;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Writer
     */
    protected $writer;

    /**
     * UnsubscribeEmailService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->writer = app('writer');
    }

    /**
     * @return string
     */
    public function updateCsvList()
    {
        $csv = $this->generateCsvList();
        $this->cloudUpdate($csv);

        return $this->cloudCsv();
    }

    /**
     * @return string
     */
    public function cloudCsv()
    {
        return \Storage::public(static::UNSUBSCRIBED_CLOUD_PATH);
    }

    /**
     * @return string
     */
    protected function generateCsvList()
    {
        $file = storage_path(sprintf('framework/cache/%s', static::UNSUBSCRIBED_FILENAME));
        $csv = $this->writer->create($file);

        $unsubscribedQuery = $this->em->getRepository(UnsubscribedEmail::class)
            ->createQueryBuilder('u')
            ->select('u.email')
            ->getQuery();

        $this->writeQueryToCsv($csv, $unsubscribedQuery);

        $csv->close();

        return $file;
    }

    /**
     * @param Writer $csv
     * @param Query  $query
     * @param string $key
     */
    protected function writeQueryToCsv(Writer $csv, Query $query, $key = 'email')
    {
        foreach (QueryIterator::create($query, 10000, 0, Query::HYDRATE_ARRAY) as $emails) {
            $csv->writeAll(array_filter(array_map(
                function($data) use ($key) {
                    return isset($data[$key]) ? [$data[$key]] : null;
                },
                $emails
            )));

            $this->em->clear();
        }
    }

    /**
     * @param $file
     */
    private function cloudUpdate($file)
    {
        /** @var \League\Flysystem\Filesystem $gcs */
        $gcs = \Storage::disk('gcs')->getDriver();

        $gcs->put(static::UNSUBSCRIBED_CLOUD_PATH, file_get_contents($file), [
            'visibility'                => AdapterInterface::VISIBILITY_PUBLIC,
            'metadata'                  => [
                'cacheControl'          => 'no-cache',
                'contentDisposition'    => 'attachment;',
            ]
        ]);
    }
}
