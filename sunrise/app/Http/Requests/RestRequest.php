<?php namespace App\Http\Requests;

use App\Entities\User;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\LaravelDoctrine\Rest\RestRequest as BaseRestRequest;

/**
 * Class RestRequest
 * @method User user($guard = null)
 */
class RestRequest extends BaseRestRequest
{
    /**
     * Insert custom data for entity creation into here.
     *
     * @var array
     */
    protected $customData = [];

    /**
     * @param array $data
     * @param bool  $merge
     *
     * @return $this
     */
    public function setCustomData(array $data, $merge = false)
    {
        $this->customData = !$merge ? $data : array_merge_recursive($this->customData, $data);
        return $this;
    }

    /**
     * @return array
     * @throws RestException
     */
    public function getData()
    {
        if (method_exists($this, 'entityRules')) {
            $rules = $this->entityRules();

            if (empty($rules)) {
                return array_merge_recursive($this->customData, $this->input('data'));
            }

            $only = $this->only(array_keys($rules));
            if (!isset($only['data']) || !is_array($only['data'])) {
                throw RestException::missingRootData();
            }

            return array_merge_recursive($this->customData, $only['data']);
        }

        return array_merge_recursive($this->customData, parent::getData());
    }

    /**
     * @return array
     */
    public function rules()
    {
        return parent::rules() + ((method_exists($this, 'entityRules')) ? $this->entityRules() : []);
    }
}
