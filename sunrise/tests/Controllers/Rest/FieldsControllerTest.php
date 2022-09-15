<?php namespace tests\Controllers\Rest;

use App\Entities\Field;
use App\Entities\Passport\OauthClient;
use Tests\TestCase;

class FieldsControllerTest extends TestCase
{
    public function test_fields_index()
    {
        $headers = $this->getOAuthClientHeaders('*', OauthClient::barn());

        $this->json('get', route('field.index'), [], $headers)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                    ]
                ]
            ]);

        $this->json('get', route('field.show', Field::NAME), [], $headers)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => Field::NAME,
                    'type' => Field::getResourceKey(),
                    'attributes' => [
                        'type' => Field::TYPE_TEXT,
                        'name' => 'Name',
                    ]
                ]
            ]);
    }
}
