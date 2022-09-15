<?php namespace App\Contracts;

interface Recurrable
{
    const PERIOD_TYPE_DAY = "day";
    const PERIOD_TYPE_WEEK = "week";
    const PERIOD_TYPE_MONTH = "month";
    const PERIOD_TYPE_YEAR = "year";
    const PERIOD_TYPE_NEVER = "never";
}
