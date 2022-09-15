<?php

namespace ScholarshipOwl\Data;


class DateHelper
{

    const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    const FULL_DATE_FORMAT = 'g:i A T \o\n F jS Y';

    const DEFAULT_DATE_FORMAT = 'm/d/Y';

    /**
     * @param $date
     * @return \DateTime
     */
    static public function fromString($date)
    {
        return \DateTime::createFromFormat(self::DEFAULT_FORMAT, $date);
    }

}
