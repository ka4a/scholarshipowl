<?php namespace App\Entity\Resource;

use App\Entity\Account;
use ScholarshipOwl\Data\AbstractResource;

class TaggedResource extends AbstractResource
{
    public function toArray(): array
    {
        $result = $this->stringData;
        $account = \Auth::user();
        if($account instanceof Account) {
            $result = map_tags_provider($this->stringData, [$account]);
        }
        return [$result];
    }
}
