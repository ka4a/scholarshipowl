<?php namespace App\Facades;

class Storage extends \Illuminate\Support\Facades\Storage
{

    /**
     * Provides public path for cloud storage resource
     *
     * @param string $path
     *
     * @return string
     */
    public static function public(string $path)
    {
        return 'https://storage.googleapis.com/'.\Config::get('filesystems.disks.gcs.bucket') .'/'. trim($path, '/');
    }

}
