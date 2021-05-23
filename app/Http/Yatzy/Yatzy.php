<?php

namespace App\Http\Yatzy;

use App\Http\Dice\{
    Dice,
    DiceHand,
    DiceGraphic
};
use App\Http\Yatzy\Special;

/**
 * Class Yatzy
 */
class Yatzy {
    /**
     * Returns a rolled hand of five dices
     */
    public function firstRoll()
    {
        // Checking so Session has everything needed
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);
        session(['summa' => session('summa') ?? 0]);
        session(['rolling' => true]);
        // $this->diceHand = new DiceHand();
        // Get 5 dices, roll and put into dh (dicehand) variable
        if (session('rollCounter') == 0) {
            for ($i = 0; $i < 5; $i++) {
                session("diceHand")->addDice(new DiceGraphic());
            }
            session("diceHand")->roll();
            
            // Save roll counter to session
            session(["rollCounter" => 1]);
        };

        session(["dh" => session("diceHand")->getHand()]);
        return;
    }
    /**
     * Checks if there has been a selection
     * Checks if bonus is available
     * Checks if the player want to keep dices
     * 
     * Rolls dices of non selected if any
     * Adds one to rollCounter
     */
    public function roll()
    {
        // Checking so Session has everything needed
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);
        session(['summa' => session('summa') ?? 0]);
        session(['sum' => session('sum') ?? 0]);
        session(['check' => session('check') ?? null]);
        session(['rollCounter' => session('rollCounter') ?? 1]);

        // Check if selected
        $this->selection();

        // Check if bonus
        $this->bonus();

        // Checkbox array to see what needs to be rolled
        $trueRoll = $this->trueRoll();
        if (in_array(true, $trueRoll)) {
            session("diceHand")->rollTrue($trueRoll);
        }
        session(['dh' => session("diceHand")->getHand()]);
        session()->increment("sum", (int)session("diceHand")->getSum());

        // add player rolls
        session()->increment("rollCounter", 1);
        return;
    }

    /**
     * Returns an array of true or false for what dice to roll
     */
    public function trueRoll(): array
    {
        // Check what user wanna keep
        $trueRoll = [false, false, false, false, false];
        $check = session("check");
        if ($check) {
            $len = sizeof($check);
            for ($i = 0; $i < $len; $i++) {
                $trueRoll[$check[$i]] = true;
            }
        }

        return $trueRoll;
    }

    /**
     * Splits string into array, checks if it's the first 6 of options
     * Passes to Special calls if more than 1 character in string with name
     * 
     * Adds sum to relativ option selected and to total
     * Rerolls the whole dicehand
     */
    public function selection(): void
    {
        // Add session if not exsists
        session(['summa' => session('summa') ?? 0]);
        session(['specialSumma' => session('specialSumma') ?? 0]);
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);
        session(['yatzy' => session('yatzy') ?? new Yatzy()]);
        $selectArray = session('selection')[0] ?? null;
        $selection = explode(' ', $selectArray)[0] ?? null;
        // $sessionWord = explode(' ', $selectArray)[1] ?? null;
        
        if (strlen($selection) == 1) {
            $sumNumber = session('diceHand')->getSumNumber((int)$selection) ?? 0;
            session([('select' . $selection) => $sumNumber]);
            session(['rollCounter' => 0]);
            session(['check' => ["0", "1", "2", "3", "4"]]);
            session()->increment('summa', $sumNumber);
            session(['end' => session('yatzy')->checkAllBoxes()]);
            session(['selection' => null]);
            return;
        } elseif (strlen($selection) > 1) {
            $arrayNumber = session('diceHand')->getArrayDiceNumber() ?? 0;
            $special = new Special();

            $sumNumber = $special->middleSelection($selection, $arrayNumber);
            $specNumber = $special->specialSelection($selection, $arrayNumber);

            if ($specNumber >= $sumNumber) {
                $sumNumber = $specNumber;
            }

            session([$selection => $sumNumber]);
            session(['rollCounter' => 0]);
            session(['check' => ["0", "1", "2", "3", "4"]]);
            session()->increment('specialSumma', $sumNumber);
            session(['end' => session('yatzy')->checkAllBoxes()]);
            session(['selection' => null]);
            return;
        }
    }

    /**
     * Checks if summa is enough for bonus points
     * 
     * return 50+ points if true
     */
    public function bonus(): void
    {
        $summa = session('summa');
        if ($summa >= 63) {
            session(['bonus' => 50]);
        }
    }

    /**
     * Check if all boxes are filled in
     * Return bool
     */
    public function checkAllBoxes(): bool
    {
        $select = array();
        $select[] = session('select1') ?? null;
        $select[] = session('select2') ?? null;
        $select[] = session('select3') ?? null;
        $select[] = session('select4') ?? null;
        $select[] = session('select5') ?? null;
        $select[] = session('select6') ?? null;
        $select[] = session('pair') ?? null;
        $select[] = session('twopair') ?? null;
        $select[] = session('three') ?? null;
        $select[] = session('four') ?? null;
        $select[] = session('five') ?? null;
        $select[] = session('stairLow') ?? null;
        $select[] = session('stairHigh') ?? null;
        $select[] = session('house') ?? null;
        $select[] = session('chance') ?? null;

        $count = 0;

        foreach ($select as $key) {
            if (!is_null($key)) {
                $count += 1;
            }
        }

        if ($count == 15) {
            return true;
        }
        return false;
    }

}