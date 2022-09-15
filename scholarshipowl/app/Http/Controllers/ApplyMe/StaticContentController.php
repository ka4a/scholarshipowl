<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Controllers\ApplyMe;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\JsonResponses;
use App\Http\Controllers\Controller;

class StaticContentController extends Controller
{
    use JsonResponses;

    protected $applicationTerms = <<<TERMS
test application terms
TERMS;

    protected $policyText = <<<POLICY
test policy text
POLICY;

    /**
     * Return static content
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->input('get') != '') {
            $fields = explode(',', $request->input('get'));
            $data = [];
            foreach ($fields as $field) {
                if (property_exists($this, $field)) {
                    $data[$field] = $this->{$field};
                } else {
                    return $this->jsonErrorResponse('Bad request. Property "' . $field . '" doesn\'t exist.');
                }
            }
            return $this->jsonSuccessResponse([$data]);
        }

        return $this->jsonSuccessResponse([
            'applicationTerms' => $this->applicationTerms,
            'policyText'       => $this->policyText
        ]);
    }
}