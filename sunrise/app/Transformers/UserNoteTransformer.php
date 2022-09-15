<?php namespace App\Transformers;

use App\Entities\UserNote;
use League\Fractal\TransformerAbstract;

class UserNoteTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'user',
    ];

    /**
     * @param UserNote $note
     *
     * @return array
     */
    public function transform(UserNote $note)
    {
        return [
            'id' => $note->getId(),
            'identifier' => $note->getIdentifier(),
            'type' => $note->getType(),
            'content' => $note->getContent(),
            'createdAt' => $note->getCreatedAt(),
            'updatedAt' => $note->getUpdatedAt(),
        ];
    }

    /**
     * @param UserNote $note
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(UserNote $note)
    {
        return $this->item($note->getUser(), new UserTransformer(), 'user');
    }
}
