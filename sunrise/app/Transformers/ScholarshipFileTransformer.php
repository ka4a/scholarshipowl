<?php namespace App\Transformers;

use App\Entities\ApplicationFile;
use App\Entities\ScholarshipFile;
use League\Fractal\TransformerAbstract;

class ScholarshipFileTransformer extends TransformerAbstract
{
    public function transform(ScholarshipFile $file)
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
