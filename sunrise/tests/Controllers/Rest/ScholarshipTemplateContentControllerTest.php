<?php namespace Tests\Controllers\Rest;

use App\Contracts\LegalContentContract;
use App\Entities\ScholarshipTemplateContent;
use Tests\TestCase;

class ScholarshipTemplateContentControllerTest extends TestCase
{
    public function test_manage_scholarship_template_content()
    {
        $user = $this->generateUser(1, false);
        $org = $this->generateOrganisation('Test', $user);
        $template = $this->generateScholarshipTemplate($org);

        $affidavitContent = $template->getContentByType(LegalContentContract::TYPE_AFFIDAVIT);

        $this->actingAs($user)
            ->json('get', route('scholarship_template_content.show', $affidavitContent->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $affidavitContent->getId(),
                    'type' => $affidavitContent->getResourceKey(),
                ]
            ]);

        $data = [
            'data' => [
                'attributes' => [
                    'content' => 'test content',
                    'type' => LegalContentContract::TYPE_AFFIDAVIT
                ]
            ]
        ];

        $this->json('patch', route('scholarship_template_content.update', $affidavitContent->getId()), $data)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $affidavitContent->getId(),
                    'type' => $affidavitContent->getResourceKey(),
                    'attributes' => [
                        'content' => 'test content',
                        'type' => LegalContentContract::TYPE_AFFIDAVIT
                    ]
                ]
            ]);
    }
}
