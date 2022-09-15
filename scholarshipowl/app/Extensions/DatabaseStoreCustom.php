<?php

namespace App\Extensions;

use Illuminate\Cache\DatabaseStore;
use Illuminate\Contracts\Cache\Store;

class DatabaseStoreCustom extends DatabaseStore implements Store
{
    /**
     * Store an item in the cache if the key does not exist.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  \DateTimeInterface|\DateInterval|float|int  $minutes
     * @return bool
     */
    public function add($key, $value, $minutes)
    {
        if (is_null($this->get($key))) {
            $key = $this->prefix.$key;
            $value = serialize($value);
            $expiration = $this->getTime() + (int) ($minutes * 60);
            try {
                $this->table()->insert(compact('key', 'value', 'expiration'));
            } catch (\Exception $e) {
                return false;
            }

            return true;
        }

        return false;
    }
}
