<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Writes logs with rotation in separate file for each severity level.
 *
 * Class SeverityRotatingFileHandler
 * @package App\Logging
 */
class SeverityRotatingFileHandler extends StreamHandler
{
    const FILE_PER_DAY = 'Y-m-d';
    const FILE_PER_MONTH = 'Y-m';
    const FILE_PER_YEAR = 'Y';

    const MAX_FILES_DEBUG = 3;
    const MAX_FILES_INFO = 10;
    const MAX_FILES_NOTICE = 10;
    const MAX_FILES_WARNING = 10;
    const MAX_FILES_ERROR = 30;
    const MAX_FILES_ALERT = 10;
    const MAX_FILES_EMERGENCY = 10;

    protected $filename;
    protected $filesUnlimited;
    protected $mustRotate;
    protected $nextRotation;
    protected $filenameFormat;
    protected $dateFormat;

    /**
     * @param string $filename
     * @param int $filesUnlimited TRUE - do not remove old files
     * @param int $level The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble  Whether the messages that are handled can bubble up the stack or not
     * @param int|null $filePermission Optional file permissions (default (0644) are only for owner read/write)
     * @param Boolean $useLocking Try to lock log file before doing any writes
     */
    public function __construct(
        $filename,
        $filesUnlimited = false,
        $level = Logger::DEBUG,
        $bubble = true,
        $filePermission = null,
        $useLocking = false
    ) {
        if (strpos($filename, '{severity}') === false) {
            throw new \RuntimeException('File name must contain {severity} placeholder');
        }

        $this->filename = $filename;
        $this->filesUnlimited = $filesUnlimited;
        $this->nextRotation = new \DateTime('tomorrow');
        $this->filenameFormat = '{filename}.{date}';
        $this->dateFormat = 'Y-m-d';

        parent::__construct($this->getTimedFilename(), $level, $bubble, $filePermission, $useLocking);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        parent::close();

        if (true === $this->mustRotate) {
            $this->rotate();
        }
    }

    /**
     * @param $filenameFormat
     * @param $dateFormat
     */
    public function setFilenameFormat($filenameFormat, $dateFormat)
    {
        if (!preg_match('{^Y(([/_.-]?m)([/_.-]?d)?)?$}', $dateFormat)) {
            trigger_error(
                'Invalid date format - format must be one of '.
                'RotatingFileHandler::FILE_PER_DAY ("Y-m-d"), RotatingFileHandler::FILE_PER_MONTH ("Y-m") '.
                'or RotatingFileHandler::FILE_PER_YEAR ("Y"), or you can set one of the '.
                'date formats using slashes, underscores and/or dots instead of dashes.',
                E_USER_DEPRECATED
            );
        }
        if (substr_count($filenameFormat, '{date}') === 0) {
            trigger_error(
                'Invalid filename format - format should contain at least `{date}`, because otherwise rotating is impossible.',
                E_USER_DEPRECATED
            );
        }
        $this->filenameFormat = $filenameFormat;
        $this->dateFormat = $dateFormat;
        $this->url = $this->getTimedFilename();
        $this->close();
    }

    /**
     * Rotates the files.
     */
    protected function rotate()
    {
        // update filename
        $this->url = $this->getTimedFilename();
        $this->nextRotation = new \DateTime('tomorrow');

        // skip old logs if files are unlimited
        if ($this->filesUnlimited === true) {
            return;
        }

        $severities = array_filter((new \ReflectionClass(__CLASS__))->getConstants(), function($key) {
            return strpos($key, 'MAX_FILES_') !== false;
        }, ARRAY_FILTER_USE_KEY);

        foreach ($severities as $k => $maxFiles) {
            $severity = strtolower(substr($k, 10));
            $logFiles = glob($this->getGlobPattern($severity));

            if ($maxFiles >= count($logFiles)) {
                continue;
            }

            // Sorting the files by name to remove the older ones
            usort($logFiles, function ($a, $b) {
                return strcmp($b, $a);
            });

            foreach (array_slice($logFiles, $maxFiles) as $file) {
                if (is_writable($file)) {
                    // suppress errors here as unlink() might fail if two processes
                    // are cleaning up/rotating at the same time
                    set_error_handler(function ($errno, $errstr, $errfile, $errline) {});
                    unlink($file);
                    restore_error_handler();
                }
            }
        }

        $this->mustRotate = false;
    }

    /**
     * @inheritdoc
     */
    protected function write(array $record)
    {
        // on the first record written, if the log is new, we should rotate (once per day)
        if (null === $this->mustRotate) {
            $this->mustRotate = !file_exists($this->url);
        }

        if ($this->nextRotation < $record['datetime']) {
            $this->mustRotate = true;
            $this->close();
        }

        $url = $this->url;
        $severity = strtolower($record['level_name']);
        $this->url = str_replace('{severity}', $severity, $this->url);

        parent::write($record);

        $this->stream = null;
        $this->url = $url;
    }

    /**
     * @return mixed|string
     */
    protected function getTimedFilename()
    {
        $fileInfo = pathinfo($this->filename);
        $timedFilename = str_replace(
            array('{filename}', '{date}'),
            array($fileInfo['filename'], date($this->dateFormat)),
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );

        if (!empty($fileInfo['extension'])) {
            $timedFilename .= '.'.$fileInfo['extension'];
        }

        return $timedFilename;
    }

    /**
     * @inheritdoc
     */
    protected function getGlobPattern(string $severity)
    {
        $fileInfo = pathinfo($this->filename);
        $filename = str_replace('{severity}', $severity, $fileInfo['filename']);
        $glob = str_replace(
            ['{filename}', '{date}'],
            [$filename, '*'],
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );
        if (!empty($fileInfo['extension'])) {
            $glob .= '.'.$fileInfo['extension'];
        }

        return $glob;
    }
}