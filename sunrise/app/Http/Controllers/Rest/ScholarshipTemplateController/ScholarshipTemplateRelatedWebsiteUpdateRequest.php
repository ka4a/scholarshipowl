<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipWebsite;
use App\Entities\ScholarshipWebsiteFile;
use App\Http\Requests\RestRequest;
use App\Repositories\ScholarshipTemplateRepository;
use App\Rules\Data;
use Illuminate\Validation\Rule;
use LaravelDoctrine\ORM\Facades\EntityManager;

class ScholarshipTemplateRelatedWebsiteUpdateRequest extends RestRequest
{
    /**
     * @return array
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function entityRules()
    {
        /** @var ScholarshipTemplateRepository $repository */
        $repository = EntityManager::getRepository(ScholarshipTemplate::class);
        $template = $repository->findById($this->getId());

        $domainUnique = Rule::unique(ScholarshipWebsite::class, 'domain');
        $logoRule = new Data(ScholarshipWebsiteFile::class, 'image');

        if ($template->getWebsite()) {
            $domainUnique->ignore($template->getWebsite()->getId());
        }

        return [
            'data.attributes.domain'        => ['required', 'max:255', $domainUnique],
            'data.attributes.layout'        => 'required|max:255',
            'data.attributes.variant'       => 'required|max:255',

            'data.attributes.companyName'   => 'nullable|string',
            'data.attributes.title'         => 'nullable|string',
            'data.attributes.intro'         => 'nullable|string',

            'data.relationships.logo.data'  => ['nullable', $logoRule],
        ];
    }
}
