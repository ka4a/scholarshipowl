<?php namespace App\Doctrine\Extensions\Uploadable;

use Illuminate\Support\Facades\Storage;

class PrivateListener extends AbstractListener
{
    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk()
    {
        return Storage::disk();
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return false;
    }
}
