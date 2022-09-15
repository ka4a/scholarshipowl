<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

/**
 * Class StdoutFormatter
 * @package Monolog\Formatter
 */
class StdoutFormatter extends LineFormatter
{
    /**
     * @inheritDoc
     */
    public function __construct($format = null, $dateFormat = null)
    {
        $this->includeStacktraces = true;
        parent::__construct($format, $dateFormat, true, true);
    }

    protected function replaceNewlines($str)
    {
        if ($this->allowInlineLineBreaks) {
            $str = str_replace(array("\r\n", "\r", "\n"), '\n', $str);
            // replace multiple spaces with a single one
            $str = preg_replace('!\s+!', ' ', $str);
        }

        return $str;
    }
}
