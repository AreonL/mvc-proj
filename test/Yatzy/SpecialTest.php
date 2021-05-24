<?php

declare(strict_types=1);

namespace App\Http\Yatzy;

use AreonL\Dice\{
    Dice,
    DiceHand,
    DiceGraphic
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

/**
 * Test cases for the class Special.
 */
class SpecialTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheSpecialClass()
    {
        $class = new Special();
        $this->assertInstanceOf("App\Http\Yatzy\Special", $class);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionPair()
    {
        $function = 'pair';

        $sumNumber = ["1", "2", "3", "4", "5"];
        $exp = 0;
        $this->assertFunctionEquals($sumNumber, $function, $exp);

        $sumNumber = ["6", "6", "1", "2", "3"];
        $exp = 12;
        $this->assertFunctionEquals($sumNumber, $function, $exp);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionTwoPair()
    {
        $function = 'twopair';

        $sumNumber = ["1", "2", "3", "4", "5"];
        $exp = 0;
        $this->assertFunctionEquals($sumNumber, $function, $exp);

        $sumNumber = ["6", "6", "1", "5", "5"];
        $exp = 22;
        $this->assertFunctionEquals($sumNumber, $function, $exp);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionThreeFourFive()
    {
        $sumNumber = ["6", "6", "6", "5", "5"];
        $exp = 18;
        $this->threeFourFiveHelper('three', '3', $sumNumber, $exp);
        $sumNumber = ["6", "6", "6", "6", "5"];
        $exp = 24;
        $this->threeFourFiveHelper('four', '4', $sumNumber, $exp);
        $sumNumber = ["6", "6", "6", "6", "6"];
        $exp = 50;
        $this->threeFourFiveHelper('five', '5', $sumNumber, $exp);
        $this->assertEquals(session('three'), 18);
        $this->assertEquals(session('four'), 24);
        $this->assertEquals(session('five'), 50);
    }

    public function threeFourFiveHelper($wordNumber, $number, $sumNumber2, $exp2)
    {
        $function = 'threeFourFive';
        $selection = ['threeFourFive ' . $wordNumber . ' ' . $number];

        session(['selection' => $selection]);

        $sumNumber = ["1", "2", "3", "4", "5"];
        $exp = 0;
        $this->assertFunctionEquals($sumNumber, $function, $exp);
        $this->assertFunctionEquals($sumNumber2, $function, $exp2);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionStair()
    {
        $sumNumber = ["1", "2", "3", "4", "5"];
        $exp = 15;
        $this->stairHelper('stairLow', $sumNumber, $exp);
        $sumNumber = ["2", "3", "4", "5", "6"];
        $exp = 20;
        $this->stairHelper('stairHigh', $sumNumber, $exp);
        $this->assertEquals(session('stairLow'), 15);
        $this->assertEquals(session('stairHigh'), 20);
    }

    public function stairHelper($wordNumber, $sumNumber2, $exp2)
    {

        $function = 'stair';
        $selection = ['stair ' . $wordNumber];

        session(['selection' => $selection]);

        $sumNumber = ["1", "2", "3", "5", "6"];
        $exp = 0;
        $this->assertFunctionEquals($sumNumber, $function, $exp);
        $this->assertFunctionEquals($sumNumber2, $function, $exp2);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionHouse()
    {

        $function = 'House';

        $sumNumber = ["1", "2", "3", "4", "5"];
        $exp = 0;
        $this->assertFunctionEquals($sumNumber, $function, $exp);

        $sumNumber = ["6", "6", "6", "5", "5"];
        $exp = 28;
        $this->assertFunctionEquals($sumNumber, $function, $exp);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionChans()
    {

        $function = 'Chans';

        $sumNumber = ["1", "2", "3", "4", "5"];
        $exp = 15;
        $this->assertFunctionEquals($sumNumber, $function, $exp);

        $sumNumber = ["6", "6", "6", "6", "6"];
        $exp = 30;
        $this->assertFunctionEquals($sumNumber, $function, $exp);
    }

    /**
     * Used to assert all functions options
     */
    public function assertFunctionEquals($sumNumber, $function, $exp)
    {
        $class = new Special();
        $this->assertInstanceOf("App\Http\Yatzy\Special", $class);

        $res = $class->$function($sumNumber);
        $this->assertEquals($res, $exp);
    }
}
