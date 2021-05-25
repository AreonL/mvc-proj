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
 * Test cases for the controller YatzyController.
 */
class ControllerYatzyTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);
    }

    /**
     * Check that the function returns response.
     */
    public function testReturnsResponse()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);

        $response = $this->get('/yatzy');

        $response->assertStatus(200);
    }

    /**
     * Check that the function returns action url.
     */
    public function testReturnsAction()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);

        // $controller->setup();

        $this->actionReturns('firstView', url("/yatzy/firstRoll"), $controller);
        // $this->actionReturns('rolling', url("/yatzy/roll"), $controller);
        $this->actionReturns('end', url("/highscore/store"), $controller);
    }

    public function actionReturns($sessionVar, $output, $controller)
    {
        $controller->setup();
        session([$sessionVar => true]);
        $response = $this->get('/yatzy');

        $response->assertStatus(200);
        $response->assertSee($output);
        session([$sessionVar => false]);
    }

    /**
     * Check that the function returns session.
     */
    public function testFunctionSetUp()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);

        $session = session()->all();
        $this->assertEmpty($session);

        $controller->setup();

        $session = session()->all();
        $this->assertNotEmpty($session);

        $this->assertArrayHasKey('select1', $session);
        $this->assertArrayHasKey('select2', $session);
        $this->assertArrayHasKey('select3', $session);
        $this->assertArrayHasKey('select4', $session);
        $this->assertArrayHasKey('select5', $session);
        $this->assertArrayHasKey('select6', $session);
        $this->assertArrayHasKey('pair', $session);
        $this->assertArrayHasKey('twopair', $session);
        $this->assertArrayHasKey('three', $session);
        $this->assertArrayHasKey('four', $session);
        $this->assertArrayHasKey('five', $session);
        $this->assertArrayHasKey('stairLow', $session);
        $this->assertArrayHasKey('stairHigh', $session);
        $this->assertArrayHasKey('house', $session);
        $this->assertArrayHasKey('chans', $session);
        $this->assertEquals(session('specialSumma'), 0);
        $this->assertEquals(session('summa'), 0);
        $this->assertEquals(session('bonus'), 0);
        $this->assertEquals(session('sum'), 0);
        $this->assertEquals(session('firstView'), true);
    }

    /**
     * Check that the function returns session.
     */
    public function testFunctionFirstRoll()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);

        $controller->firstRoll();

        $session = session()->all();
        $this->assertNotEmpty($session);
    }

    /**
     * Check that the function returns session.
     */
    public function testFunctionRoll()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);

        $controller->roll();

        $session = session()->all();
        $this->assertNotEmpty($session);
    }
}
