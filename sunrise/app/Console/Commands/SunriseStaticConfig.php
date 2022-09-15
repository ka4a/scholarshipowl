<?php

namespace App\Console\Commands;

use App\Entities\Country;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class SunriseStaticConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunrise:static-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        /** @var Country[] $countries */
        $countries = $this->em->getRepository(Country::class)->findAll();

        $countriesData = [];
        foreach ($countries as $country) {
            $countriesData[$country->getId()] = [
                'name' => $country->getName(),
                'abbreviation' => $country->getAbbreviation(),
            ];
        }

        $countriesFile = resource_path('admin/countries.json');
        if (!file_put_contents($countriesFile, json_encode($countriesData))) {
            throw new \RuntimeException('Failed to save countries file!');
        }

        $this->info(sprintf('Generated countries JSON: %s', $countriesFile));
    }
}
