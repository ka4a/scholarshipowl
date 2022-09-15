<?php namespace Tests\Controllers\Rest;

use App\Entities\Country;
use Tests\TestCase;

class CountryControllerTest extends TestCase
{
    public function test_fetch_countries()
    {
        $this->actingAs($this->generateUser());

        $this->json('get', route('country.index'))
            // ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => Country::USA,
                        'type' => Country::getResourceKey(),
                        'attributes' => [
                            'name' => 'USA',
                            'abbreviation' => 'US',
                        ]
                    ]
                ]
            ]);
    }
}