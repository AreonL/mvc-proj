<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use AreonL\Dice\{
    Dice,
    DiceHand,
    DiceGraphic
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Test cases for the controller Session.
 */
class ControllerSessionTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new SessionController();
        $this->assertInstanceOf("App\Http\Controllers\SessionController", $controller);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testReturnsResponse()
    {
        $controller = new SessionController();
        $this->assertInstanceOf("App\Http\Controllers\SessionController", $controller);
        $response = $this->get('/session');

        $response->assertStatus(200);
    }

    /**
     * Check that the controller "destroys" session with flush.
     */
    public function testDestroySession()
    {
        $controller = new SessionController();
        $this->assertInstanceOf("App\Http\Controllers\SessionController", $controller);

        session(['key' => 'value']);

        $session = session()->all();
        $this->assertNotEmpty($session);

        $controller->destroy();

        $session = session()->all();
        $this->assertEmpty($session);
    }
}
