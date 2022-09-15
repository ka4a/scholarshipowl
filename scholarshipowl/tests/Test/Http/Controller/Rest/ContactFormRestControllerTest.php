<?php namespace Test\Http\Controller\Rest;

use App\Mail\Contact;
use App\Testing\TestCase;

class ContactFormRestControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testSettingsActions()
    {
        \Mail::fake();

        $data = [
            'name' => 'test_name',
            'email' => 'test_email@example.com',
            'phone' => '',
            'content' => 'test',
            'location' => 'location'
        ];
        $resp = $this->post(route('rest::v1.rest-post-contact','location'), $data);
        $this->assertTrue($resp->status() === 200);

        \Mail::assertSent(Contact::class, function ($mail) use ($data) {
            $mail->build();
            return $mail->hasTo(\Config::get("scholarshipowl.mail.system.contact.to")) &&
                $mail->viewData === $data;
        });
    }
}
