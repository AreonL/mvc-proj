<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Dice\{
    Dice,
    DiceHand,
    DiceGraphic
};
use App\Http\Yatzy\Yatzy;
use Illuminate\Http\Request;

class YatzyController extends Controller
{
    /**
     * Declare some variables
     */
    private string $action;
    
    /**
     * Index function
     * 
     * Check what action each view should have
     * Returns the view with action and session
     */
    public function index()
    {
        if (session('end')) {
            $action = url("/highscore/store");
        } elseif (session('roll')) {
            $action = url("/yatzy/roll");
        } elseif (session('firstView')) {
            $action = url("/yatzy/firstRoll");
        }

        return view('yatzy', [
            'action' => $action,
            'session' => session()->all(),
        ]);
    }

    /**
     * Puts everything nessesary into session
     */
    public function setup()
    {
        session(['firstView' => true]);
        session(["sum" => 0]);
        session(["diceHand" => new DiceHand()]);
        session(['yatzy' => new Yatzy()]);
        session(["select1" => null]);
        session(["select2" => null]);
        session(["select3" => null]);
        session(["select4" => null]);
        session(["select5" => null]);
        session(["select6" => null]);
        session(["pair" => null]);
        session(["twopair" => null]);
        session(["three" => null]);
        session(["four" => null]);
        session(["five" => null]);
        session(["stairLow" => null]);
        session(["stairHigh" => null]);
        session(["house" => null]);
        session(["chans" => null]);
        session(["specialSumma" => null]);
        session(["summa" => 0]);
        session(["bonus" => null]);

        return redirect('/yatzy');
    }

    /**
     * Calls the firstRoll funtion
     */
    public function firstRoll()
    {
        session('yatzy')->firstRoll();

        return redirect('/yatzy');
    }

    /**
     * Put the posted values into session
     * 
     * Calls function roll
     */
    public function roll()
    {
        session(["check" => $_POST["check"] ?? null]);
        session(["selection" => $_POST["selection"] ?? null]);

        session('yatzy')->roll();

        return redirect('/yatzy');
    }
}
