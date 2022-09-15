<?php

namespace ScholarshipOwl\Domain\Log;

use ScholarshipOwl\Data\Service\IDDL;

class GTSPaymentFormUrl
{

    /**
     * @param $accountId
     * @param $formUrl
     */
    public static function logFormUrl($accountId, $formUrl)
    {
        if ($accountId && $formUrl) {
            \DB::table(IDDL::TABLE_LOG_GTS_FORM_URL)->insert(array(
                'account_id' => $accountId,
                'form_url' => $formUrl,
            ));
        }
    }

}