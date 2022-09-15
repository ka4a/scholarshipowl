<?php namespace App\Transformers;

use App\Entities\ApplicationFile;
use App\Entities\ApplicationRequirement;
use League\Fractal\TransformerAbstract;

class ApplicationRequirementTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'requirement',
        'files',
    ];

    public function transform(ApplicationRequirement $requirement)
    {
        return [
            'id' => $requirement->getId(),
            'value' => $requirement->getValue(),
        ];
    }

    /**
     * @param ApplicationRequirement $requirement
     * @return \League\Fractal\Resource\Item
     */
    public function includeRequirement(ApplicationRequirement $requirement)
    {
        return $this->item(
            $requirement->getRequirement(),
            new ScholarshipRequirementTransformer(),
            $requirement->getRequirement()->getResourceKey()
        );
    }

    /**
     * @param ApplicationRequirement $requirement
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFiles(ApplicationRequirement $requirement)
    {
        return $this->collection(
            $requirement->getFiles(),
            new ApplicationFileTransformer(),
            ApplicationFile::getResourceKey()
        );
    }
}
