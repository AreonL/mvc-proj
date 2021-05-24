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
 * Test cases for the class Yatzy.
 */
class YatzyTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheYatzyClass()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionFirstRoll()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);

        $this->assertEmpty(session()->all());

        $class->firstRoll();

        $this->assertNotEmpty(session('dh'));
        $this->assertNotEmpty(session('diceHand'));
        $this->assertEquals(session('rollCounter'), 1);
        $this->assertEquals(session('summa'), 0);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionRoll()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);

        $this->assertEmpty(session()->all());

        $class->roll();

        $this->assertNotEmpty(session('diceHand'));
        $this->assertEquals(session('summa'), 0);
        $this->assertEquals(session('rollCounter'), 2);

        session(['check' => [true, false, true, false, true]]);

        $class->roll();

        $this->assertEquals(session('rollCounter'), 3);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionTrueRoll()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);

        $this->assertEmpty(session()->all());

        $class->trueRoll();

        $check = ["0", "1", "2", "3", "4"];
        $exp = [true, true, true, true, true];
        $this->trueRollFunction($check, $exp, $class);

        $check = ["1", "3", "4"];
        //              1            3      4
        $exp = [false, true, false, true, true];
        $this->trueRollFunction($check, $exp, $class);

        $check = ["2"];
        $exp = [false, false, true, false, false];
        $this->trueRollFunction($check, $exp, $class);
    }

    public function trueRollFunction($check, $exp, $class)
    {
        session(['check' => $check]);
        $this->assertNotEmpty(session('check'));
        $this->assertEquals(session('check'), $check);

        $result = $class->trueRoll();

        $this->assertEquals($result, $exp);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testFunctionSelection()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);

        $this->assertEmpty(session()->all());

        $this->selectionHelper(['1'], 'select1');
        $this->selectionHelper(['2'], 'select2');
        $this->selectionHelper(['3'], 'select3');
        $this->selectionHelper(['4'], 'select4');
        $this->selectionHelper(['5'], 'select5');
        $this->selectionHelper(['6'], 'select6');
        $this->selectionHelper(['pair'], 'pair');
        $this->selectionHelper(['twopair'], 'twopair');
        $this->selectionHelper(['threeFourFive'], 'three');
        $this->selectionHelper(['threeFourFive'], 'four');
        $this->selectionHelper(['threeFourFive'], 'five');
        $this->selectionHelper(['stair'], 'stairLow');
        $this->selectionHelper(['stair'], 'stairHigh');
        $this->selectionHelper(['house'], 'house');
        $this->selectionHelper(['chance'], 'chance');
    }

    public function selectionHelper($selection, $name)
    {
        $class = new Yatzy();
        session(['selection' => $selection]);

        $class->selection();

        $this->assertEquals(session($name), 0);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testBonus()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);

        session([
            "summa" => 62,
            "bonus" => 0,
        ]);

        $class->bonus();
        $res = session("bonus");
        $exp = 0;

        $this->assertEquals($res, $exp);

        session([
            "summa" => 63
        ]);

        $class->bonus();
        $res = session("bonus");
        $exp = 50;
        $this->assertEquals($res, $exp);
    }

    /**
     * Check function response
     */
    public function testFunctionCheckAllBoxes()
    {
        $class = new Yatzy();
        $this->assertInstanceOf("App\Http\Yatzy\Yatzy", $class);

        $this->assertEmpty(session()->all());

        session(['select1' => 0]);
        $res = $class->checkAllBoxes();
        $exp = false;
        $this->assertEquals($res, $exp);

        session(['select2' => 0]);
        session(['select3' => 0]);
        session(['select4' => 0]);
        session(['select5' => 0]);
        session(['select6' => 0]);
        session(['pair' => 0]);
        session(['twopair' => 0]);
        session(['three' => 0]);
        session(['four' => 0]);
        session(['five' => 0]);
        session(['stairLow' => 0]);
        session(['stairHigh' => 0]);
        session(['house' => 0]);
        session(['chance' => 0]);
        $res = $class->checkAllBoxes();
        $exp = true;
        $this->assertEquals($res, $exp);
    }
}
