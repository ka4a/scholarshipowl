<?php namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class Job implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
}
