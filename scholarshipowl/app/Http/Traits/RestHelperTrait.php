<?php namespace App\Http\Traits;

use Illuminate\Http\Request;

trait RestHelperTrait
{
    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isRestCall(Request $request)
    {
        return $request->is('rest/*') ||
            $request->is('rest-mobile/*') ||
            $request->is('apply-me/*') ||
            $request->is('rest-external/*');
    }
}
