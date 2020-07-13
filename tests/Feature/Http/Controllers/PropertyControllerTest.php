<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class PropertyControllerTest extends TestCase
{
    /**
     * @test
     * @group PropertyController
     */
    public function addPropertyShouldReturn422()
    {
        $response = $this->postJson('/api/property');

        $response->assertStatus(422);
    }

    /**
     * @test
     * @group PropertyController
     */
    public function shouldAddProperty()
    {
        $response = $this->postJson('/api/property', [
            'suburb' => 'Parramatta',
            'state' => 'NSW',
            'country' => 'Australia',
        ]);

        $response->assertStatus(200);
    }
}
