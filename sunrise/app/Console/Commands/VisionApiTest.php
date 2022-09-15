<?php namespace App\Console\Commands;

use App\Services\GoogleVision;
use Google\Cloud\Vision\V1\FaceAnnotation;
use Illuminate\Console\Command;

class VisionApiTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vision:api:test { input : Input image } { output : Output image file }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google Vision API for finding winner faces.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var GoogleVision $gv */
        $gv = app(GoogleVision::class);
        $image = \Image::make($this->argument('input'));
        $found = $gv->cropFace($image);
        $found->save($this->argument('output'));
    }
}
