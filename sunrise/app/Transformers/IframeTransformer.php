<?php

/**
 * Auto-generated transformer file
 */

declare(strict_types=1);

namespace App\Transformers;

use App\Entities\Iframe;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;

class IframeTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'template',
    ];

	/**
	 * @param Iframe $iframe
	 * @return array
	 */
	public function transform(Iframe $iframe)
	{
		return [
			'id' => $iframe->getId(),
            'width' => $iframe->getWidth(),
            'height' => $iframe->getHeight(),
            'source' => $iframe->getSource(),
            'createdAt' => $iframe->getCreatedAt()->format('c'),
            'updatedAt' => $iframe->getUpdatedAt()->format('c'),
            'links' => [
                'src' => sprintf('%s/%s/frm.js', config('sunrise.go.url'), $iframe->getId()),
            ]
		];
	}

    /**
     * @param Iframe $iframe
     * @return Item
     */
	public function includeTemplate(Iframe $iframe)
    {
        return $this->item(
            $iframe->getTemplate(),
            new ScholarshipTemplateTransformer(),
            $iframe->getTemplate()->getResourceKey()
        );
    }
}
