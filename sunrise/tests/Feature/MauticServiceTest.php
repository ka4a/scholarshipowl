<?php namespace Tests\Feature;

use App\Entities\MauticContact;
use App\Services\MauticService;
use Mautic\Api\Contacts;
use Tests\TestCase;

class MauticServiceTest extends TestCase
{

    public function test_find_or_generate_mautic_contact()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $email = str_random() . '@test-mautic.local';
//        $template = $this->generateScholarshipTemplate();
//        $scholarship = $this->sm()->publish($template);
//        $application = $this->generateApplication($scholarship, $email);

        $this->mauticContacts->shouldReceive('create')
            ->with(['email' => $email])
            ->andReturnUsing(function() {
                return [
                    'contact' => [
                        'id' => rand(10000, 99999)
                    ]
                ];
            });

        $mautic = $this->mauticService->generateMauticContact($email);

        $this->assertInstanceOf(MauticContact::class, $mautic);

        $this->mauticContacts->shouldReceive('delete');

        $mautic2 = $this->mauticService->generateMauticContact($email);

        $this->assertInstanceOf(MauticContact::class, $mautic2);
        $this->assertEquals($mautic, $mautic2);

        $mautic3 = $this->mauticService->findOrGenerateMauticContactByEmail($email);

        $this->assertInstanceOf(MauticContact::class, $mautic3);
        $this->assertEquals($mautic, $mautic3);
    }
}
