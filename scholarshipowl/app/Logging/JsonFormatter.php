<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;

/**
 * Class JsonFormatter
 * @package App\Logging
 */
class JsonFormatter extends BaseJsonFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $record = [
            'time' => $record['datetime']->format('Y-m-d H:i:s'),
            'severity' => $record['level_name'],
            'ip' => request()->server('REMOTE_ADDR'),
            'message' => $record['message']
        ];

        if (!empty($record['extra'])) {
            $record['payload']['extra'] = $record['extra'];
        }

        if (!empty($record['context'])) {
            $record['payload']['context'] = $record['context'];
        }

        return $this->toJson($this->normalize($record), true) . ($this->appendNewline ? "\n" : '');
    }

}