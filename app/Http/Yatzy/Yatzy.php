<?php

namespace App\Http\Yatzy;

use App\Http\Dice\{
    Dice,
    DiceHand,
    DiceGraphic
};

/**
 * Class Yatzy
 */
class Yatzy {

    /**
     * 
     */
    public function firstRoll()
    {
        // Checking so Session has everything needed
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);
        session(['summa' => session('summa') ?? 0]);
        session(['roll' => true]);
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

        // $this->specialSelection();

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
        // $data["summa"] = session("summa");
        
        return;
    }

    public function trueRoll(): array
    {
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

    public function selection(): void
    {
        // Add session if not exsists
        session(['summa' => session('summa') ?? 0]);
        session(['specialSumma' => session('specialSumma') ?? 0]);
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);

        $select = session('selection')[0] ?? null;
        $selection = explode(' ', $select)[0];
        $antal = explode(' ', $select)[1] ?? null;
        
        
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
            $sumNumber = $this->specialSelection($selection, $antal);
            // session([($selection) => $sumNumber]);
            session(['rollCounter' => 0]);
            session(['check' => ["0", "1", "2", "3", "4"]]);
            session()->increment('specialSumma', $sumNumber);
            session(['end' => session('yatzy')->checkAllBoxes()]);
            session(['selection' => null]);
            return;
        }
    }

    public function specialSelection($selection, $antal): int
    {
        $sumNumber = session('diceHand')->getArrayDiceNumber() ?? 0;
        $sum = 0;

        switch ($selection) {
            case 'pair':
                $sum = $this->pair($sumNumber);
                break;
            case 'twopair':
                $sum = $this->twopair($sumNumber);
                break;
            case 'threeFourFive':
                $sum = $this->threeFourFive($sumNumber, $antal);
                break;
            case 'stair':
                $sum = $this->stair($sumNumber);
                break;
            case 'house':
                $sum = $this->house($sumNumber);
                break;
            case 'chance':
                $sum = $this->chans($sumNumber);
                break;
        }
        return $sum;
    }

    public function pair($sumNumber): int
    {
        $biggest = 0;
        foreach ($sumNumber as $key => $value) {
            unset($sumNumber[$key]);
            if (in_array($value, $sumNumber) && $value > $biggest) {
                $biggest = $value;
            }
        }
        return $biggest + $biggest;
    }

    public function twopair($sumNumber): int
    {
        $numberOne = 0;
        $numberTwo = 0;
        foreach ($sumNumber as $key => $value) {
            unset($sumNumber[$key]);
            if (in_array($value, $sumNumber) && $numberOne == 0) {
                $numberOne = $value;
            } elseif (in_array($value, $sumNumber) && $value != $numberOne) {
                $numberTwo = $value;
            }
        }
        if ($numberOne != 0 && $numberTwo != 0) {
            return ($numberOne + $numberOne) + ($numberTwo + $numberTwo);
        }
        return 0;
    }

    public function threeFourFive($sumNumber, $antal): int
    {
        $sum = 0;
        $selection = session('selection')[0] ?? null;
        $sumNumber = array_count_values($sumNumber);
        foreach ($sumNumber as $key => $value) {
            if ($value >= 3) {
                // Yatzy
                if ($value === 5) {
                    session(['five' => 50]);
                    return 50;
                }
                // Three and Four
                for ($i = 0; $i < $value; $i++) { 
                    $sum += $key;
                }
                if ($value === 4) {
                    session(['four' => $sum]);
                    return $sum;
                }
                session(['three' => $sum]);
                return $sum;
            }
        }
        session([$antal => 0]);
        return 0;
    }

    // public function stairLow($sumNumber): int
    // {
    //     sort($sumNumber);
    //     $stairLow = array(1, 2, 3, 4, 5);
    //     $result = array_intersect_assoc($sumNumber, $stairLow);
    //     if (count($result) == 5) {
    //         return 15;
    //     }
    //     return 0;
    // }

    public function stair($sumNumber): int
    {
        sort($sumNumber);
        $stairLow = array(1, 2, 3, 4, 5);
        $stairHigh = array(2, 3, 4, 5, 6);
        $resultHigh = array_intersect_assoc($sumNumber, $stairHigh);
        $resultLow = array_intersect_assoc($sumNumber, $stairLow);
        if (count($resultLow) == 5) {
            session(['stairLow' => 15]);
            return 15;
        }
        if (count($resultHigh) == 5) {
            session(['stairLow' => 20]);
            return 20;
        }
        return 0;
    }

    public function house($sumNumber): int
    {
        $sum = 0;
        $highNumber = false;
        $lowNumber = false;
        $newArray = array_count_values($sumNumber);
        foreach ($newArray as $key => $value) {
            if ($value == 3) {
                $highNumber = true;
            } elseif ($value == 2) {
                $lowNumber = true;
            }
        }
        if ($highNumber === true && $lowNumber === true) {
            foreach ($sumNumber as $key) {
                $sum += $key;
            }
            return $sum;
        }
        return 0;
    }

    public function chans($sumNumber): int
    {
        $sum = 0;
        foreach ($sumNumber as $key) {
            $sum += $key;
        }
        return $sum;
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