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
use Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Test cases for the controller Session.
 */
class ControllerHighscoreTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new HighscoreController();
        $this->assertInstanceOf("App\Http\Controllers\HighscoreController", $controller);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testReturnsResponse()
    {
        $controller = new HighscoreController();
        $this->assertInstanceOf("App\Http\Controllers\HighscoreController", $controller);

        $response = $this->get('/highscore');

        $response->assertStatus(200);
        $response->assertSee('header', 'Highscore!');
        $response->assertSee('highscore');
    }

    /**
     * Check that the controller returns a response.
     */
    public function testReturnResponseStore()
    {
        $controller = new HighscoreController();
        $this->assertInstanceOf("App\Http\Controllers\HighscoreController", $controller);

        $this->post('/highscore/store', ['name' => 'Test']);

        $controller->store();
    }

    /**
     * Check that the controller returns a response.
     */
    public function testReturnResponseShow()
    {
        $controller = new HighscoreController();
        $this->assertInstanceOf("App\Http\Controllers\HighscoreController", $controller);

        $this->post('/highscore/store', ['name' => 'Test']);

        $controller->store();

        // Check if there is one id in db
        $controller->show(1);
    }
}
