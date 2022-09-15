<?php

namespace App\Console\Commands;

use App\Entity\Scholarship;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;

class ScholarshipRandomImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:generate:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add random image to scholarships';

    protected $imagePath = '/assets/img/scholarship/image/';

    protected $localImagePath = null;

    protected $number = 0;

    protected $imagePaths = [];

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->localImagePath = public_path() . $this->imagePath;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Looking for images...");
        $images = glob("{$this->localImagePath}*.{jpg,png,bmp}", GLOB_BRACE);

        $image_count = count($images);
        $this->info("Found " . $image_count . " images.");
        $this->info("Uploading images to the cloud...");

        foreach ($images as $image) {
            $name = substr($image, strrpos($image, '/') + 1);
            $path = '/scholarship/image/' . $name;
            array_push($this->imagePaths, $path);
            \Storage::disk('gcs')->put($path, file_get_contents($image), Filesystem::VISIBILITY_PUBLIC);
        }

        $this->info("Upload finished");
        $this->info("Updating database...");

        $scholarships = \EntityManager::getRepository(Scholarship::class)->findAll();

        /** @var Scholarship $scholarship */
        foreach ($scholarships as $scholarship) {
            $this->number = mt_rand(0, $image_count - 1);
            $filename = $this->imagePaths[$this->number];
            $scholarship->setImage($filename);
        }
        \EntityManager::flush();

        $this->info("done.");
    }
}
