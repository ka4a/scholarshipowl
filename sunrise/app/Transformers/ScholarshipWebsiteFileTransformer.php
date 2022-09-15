<?php namespace App\Transformers;

use App\Entities\ApplicationFile;
use App\Entities\ScholarshipFile;
use App\Entities\ScholarshipWebsiteFile;
use League\Fractal\TransformerAbstract;

class ScholarshipWebsiteFileTransformer extends TransformerAbstract
{
    /**
     * @param ScholarshipWebsiteFile $file
     * @return array
     */
    public function transform(ScholarshipWebsiteFile $file)
    {
        return [
            'id' => $file->getId(),
            'path' => $file->getPath(),
            'name' => $file->getName(),
            'mimeType' => $file->getMimeType(),
            'size' => $file->getSize(),
            'links' => [
                'url' => $file->url()
            ]
        ];
    }
}
