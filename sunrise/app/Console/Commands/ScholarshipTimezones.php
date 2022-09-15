<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScholarshipTimezones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:timezones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timezones = [];
        $identifiers = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, 'US');
        foreach ($identifiers as $identifier) {
            $offset = $this->ch150918__utc_offset_dst($identifier);
            $name = str_replace('America/', '', $identifier);
            $name = str_replace('/', ' ', $name);
            $name = str_replace('_', ' ', $name);
            $name = sprintf('(GMT %s:00) %s', $offset, $name);
            $timezones[$identifier] = $name;
        }

        $this->info('Scholarship USA Timezones:');
        $this->info(json_encode($timezones));
    }

    /**
     * Prints a string showing current time zone offset to UTC, considering daylight savings time.
     * @link                     http://php.net/manual/en/timezones.php
     * @param  string $time_zone Time zone name
     * @return string            Offset in hours, prepended by +/-
     */
    private function ch150918__utc_offset_dst( $time_zone = 'Europe/Berlin' )
    {
        $utc = new \DateTime('now', new \DateTimeZone('UTC'));
        // Calculate offset.
        $current = timezone_open($time_zone);
        $offset_s = timezone_offset_get($current, $utc); // seconds
        $offset_h = $offset_s / (60 * 60); // hours
        // Prepend “+” when positive
        $offset_h = (string)$offset_h;
        if (strpos($offset_h, '-') === FALSE) {
            $offset_h = '+' . $offset_h; // prepend +
        }
        return $offset_h;
    }
}
