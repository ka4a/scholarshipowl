<?php namespace App\Transformers;

use App\Entities\ApplicationFile;
use League\Fractal\TransformerAbstract;

class ApplicationFileTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'application'
    ];

    public function transform(ApplicationFile $file)
    {
        return [
            'id' => $file->getId(),
            'path' => $file->getPath(),
            'name' => $file->getName(),
            'mimeType' => $file->getMimeType(),
            'size' => $file->getSize(),
            'links' => [
                'download' => route('application_file.download', $file->getId())
            ]
        ];
    }

    /**
     * @param ApplicationFile $file
     * @return \League\Fractal\Resource\Item
     */
    public function includeApplication(ApplicationFile $file)
    {
        return $this->item(
            $file->getApplication(),
            new ApplicationTransformerOld(),
            $file->getApplication()->getResourceKey()
        );
    }
}
