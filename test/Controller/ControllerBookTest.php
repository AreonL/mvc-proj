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
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Test cases for the controller Session.
 */
class ControllerBookTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new BookController();
        $this->assertInstanceOf("App\Http\Controllers\BookController", $controller);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testReturnsResponse()
    {
        $controller = new BookController();
        $this->assertInstanceOf("App\Http\Controllers\BookController", $controller);

        $response = $this->get('/book');

        $response->assertStatus(200);
        $response->assertSee('header', 'Booooks');
        $response->assertSee('book');
    }

    /**
     * Check that the controller returns a response.
     */
    public function testReturnResponseStore()
    {
        $controller = new BookController();
        $this->assertInstanceOf("App\Http\Controllers\BookController", $controller);

        $this->post(
            '/book/store',
            [
            'title' => 'Test',
            'ISBN' => 'Test',
            'author' => 'Test',
            'picture' => 'Test'
            ]
        );

        $controller->store();
    }
}
