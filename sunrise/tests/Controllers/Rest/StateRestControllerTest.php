<?php namespace Tests\Controllers\Rest;

use App\Entities\State;
use Tests\TestCase;

class StateRestControllerTest extends TestCase
{
    public function test_get_states_list()
    {
        $this->json('GET', route('state.index'), [], $this->getOAuthClientHeaders('*'))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => State::STATE_ALABAMA,
                        'type' => State::getResourceKey(),
                        'attributes' => [
                            'name' => 'Alabama',
                            'abbreviation' => 'AL',
                        ]
                    ],
                    [],
                    [],
                    [],
                    [],
                    [],
                    [
                        'id' => State::STATE_CONNECTICUT,
                        'type' => State::getResourceKey(),
                        'attributes' => [
                            'name' => 'Connecticut',
                            'abbreviation' => 'CT',
                        ]
                    ],
                ],
            ]);
    }
}
