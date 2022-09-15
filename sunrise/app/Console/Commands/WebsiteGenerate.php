<?php

namespace App\Console\Commands;

use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\State;
use App\Transformers\ScholarshipTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class WebsiteGenerate extends Command
{
    const JSON_SCHOLARSHIP_FILE = '/config/scholarship.json';
    const JSON_STATES_FILE = '/config/states.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:generate {template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate website and publish it to web. Generated websites placed at: /storage/websites';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /** @var RestRepository $templates */
        $templates = $this->em->getRepository(ScholarshipTemplate::class);
        /** @var ScholarshipTemplate $template */
        $template = $templates->findById($this->argument('template'));
        $domain = $template->getWebsite()->getDomain();
        $path = storage_path(sprintf('websites/%s', $domain));

        $this->warn(sprintf('Started generating website: %s', $domain));

        /**
         * Prepare dir for website compilation.
         */
        @rmdir($path);
        $this->recurse_copy('./barn-template', $path);

        /**
         * Prepare website configurations.
         */
        @mkdir($path.'/config');
        $this->generateScholarshipConfig($template, $path);
        $this->generateStatesConfig($path);

        /**
         * Install NPM dependencies
         */
        $this->info('Installing yarn requirements');
        echo shell_exec("cd $path && yarn install");

        /**
         * Build Nuxt.JS website
         */
        $this->info('Building nuxt website');
        echo shell_exec("cd $path && yarn generate");

        $this->warn('Website generation finished!');
    }

    /**
     * @param ScholarshipTemplate $template
     * @param string $path
     */
    protected function generateScholarshipConfig(ScholarshipTemplate $template, $path)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $template->getPublished()->first();
        $json = (new ScholarshipTransformer())->transform($scholarship);
        $this->put($path.static::JSON_SCHOLARSHIP_FILE, json_encode($json));
    }

    /**
     * Prepare website static dictionary tables, like states.
     *
     * @param string $path
     * @return bool|int
     */
    protected function generateStatesConfig($path)
    {
        $states = [];

        /** @var State $state */
        foreach ($this->em->getRepository(State::class)->findAll() as $state) {
            $states[$state->getId()] = [
                'abbreviation'  => $state->getAbbreviation(),
                'name'          => $state->getName(),
            ];
        }

        $this->put($path.static::JSON_STATES_FILE, json_encode($states));
    }

    /**
     * @param string $path
     * @param string $content
     */
    private function put($path, $content)
    {
        if (false === file_put_contents($path, $content)) {
            throw new \RuntimeException("Can't put file into path: ".$path);
        }
    }

    /**
     * Recursively copy directories with native PHP.
     *
     * @param string $src
     * @param string $dst
     */
    private function recurse_copy($src,$dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    @copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
