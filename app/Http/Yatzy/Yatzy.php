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
            $sumNumber = $this->middleSelection($selection, $arrayNumber);
            $specNumber = $this->specialSelection($selection, $arrayNumber);

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

    public function middleSelection($selection, $arrayNumber): int
    {
        $sum = 0;

        switch ($selection) {
            case 'pair':
                $sum = $this->pair($arrayNumber);
                break;
            case 'twopair':
                $sum = $this->twopair($arrayNumber);
                break;
            case 'threeFourFive':
                $this->threeFourFive($arrayNumber);
                break;
        }
        return $sum;
    }

    public function specialSelection($selection, $arrayNumber): int
    {
        $sum = 0;

        switch ($selection) {
            case 'stair':
                $this->stair($arrayNumber);
                break;
            case 'house':
                $sum = $this->house($arrayNumber);
                break;
            case 'chance':
                $sum = $this->chans($arrayNumber);
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
        // $number[0] = One number, $number[1] = Second number
        $number = array(0, 0);
        
        foreach ($sumNumber as $key => $value) {
            unset($sumNumber[$key]);
            $number = $this->twopairCheck($sumNumber, $value, $number);
        }
        if ($number[1] != 0) {
            return ($number[0] + $number[0]) + ($number[1] + $number[1]);
        }
        return 0;
    }

    public function twopairCheck($sumNumber, $value, $number)
    {
        if (in_array($value, $sumNumber) && $number[0] == 0) {
            $number[0] = $value;
        } elseif (in_array($value, $sumNumber) && $value != $number[0]) {
            $number[1] = $value;
        }
        return $number;
    }

    public function threeFourFive($sumNumber): int
    {
        $sum = 0;
        $selection = session('selection')[0] ?? null;
        
        $sessionWord = explode(' ', $selection)[1] ?? null;
        $antal = explode(' ', $selection)[2] ?? null;

        $sumNumber = array_count_values($sumNumber);
        foreach ($sumNumber as $key => $value) {
            if ($value >= $antal) {
                // Yatzy
                if ($antal == 5) {
                    session(['five' => 50]);
                    session()->increment('specialSumma', 50);
                    return 50;
                }
                // Three and Four
                for ($i = 0; $i < $value; $i++) { 
                    $sum += $key;
                }
                session([$sessionWord => $sum]);
                session()->increment('specialSumma', $sum);
                return $sum;
            }
        }
        session([$sessionWord => 0]);
        return 0;
    }

    public function stair($sumNumber): int
    {
        $selection = session('selection')[0] ?? null;
        $sessionWord = explode(' ', $selection)[1] ?? null;

        sort($sumNumber);
        $stairLow = array(1, 2, 3, 4, 5);
        $stairHigh = array(2, 3, 4, 5, 6);
        $resultHigh = array_intersect_assoc($sumNumber, $stairHigh);
        $resultLow = array_intersect_assoc($sumNumber, $stairLow);

        if (count($resultLow) == 5) {
            session(['stairLow' => 15]);
            session()->increment('specialSumma', 15);

            return 15;
        }
        if (count($resultHigh) == 5) {
            session(['stairLow' => 20]);
            session()->increment('specialSumma', 20);

            return 20;
        }
        session([$sessionWord => 0]);
        return 0;
    }

    public function house($sumNumber): int
    {
        $sum = 0;
        $newArray = array_count_values($sumNumber);

        $numberArray = $this->houseCheck($newArray);

        if ($numberArray[0] === true && $numberArray[1] === true) {
            foreach ($sumNumber as $key) {
                $sum += $key;
            }
            return $sum;
        }
        return 0;
    }

    public function houseCheck($newArray): array
    {
        $numberArray = array(false, false);
        foreach ($newArray as $key => $value) {
            if ($value == 3) {
                $numberArray[0] = true;
            } elseif ($value == 2) {
                $numberArray[1] = true;
            }
        }
        return $numberArray;
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