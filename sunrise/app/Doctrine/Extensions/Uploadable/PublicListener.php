<?php namespace App\Doctrine\Extensions\Uploadable;

use Illuminate\Support\Facades\Storage;

class PublicListener extends AbstractListener
{
    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk()
    {
        return Storage::cloud();
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return true;
    }
}
