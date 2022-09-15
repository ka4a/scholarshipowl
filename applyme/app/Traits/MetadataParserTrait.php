<?php

namespace App\Traits;

use Throwable;

trait MetadataParserTrait
{
    /**
     * Parse and eval the given string.
     *
     * @param  string  $value
     * @return string
     */
    public function evalString($value)
    {
        try {
            return is_null($value)
                ? null
                : stripslashes(eval("return \"" . addslashes($value) . "\";"));
        } catch(Throwable $t) {
            // @TODO Add a notification
            return $this->name;
        }
    }

    /**
     * Parse the date marker.
     *
     * @param  string  $value
     * @return string
     */
    public function parseDate($value)
    {
        return str_replace("%date%", date("Y"), $value);
    }

}
