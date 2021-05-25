<?php

namespace App\Http\Yatzy;

use App\Http\Dice\{
    Dice,
    DiceHand,
    DiceGraphic
};

/**
 * Class Special
 *
 * Cohesion to Yatzy
 */
class Special
{
    /**
     * Checks if selected are either
     * Pair, Two pair or Threes Fours Yatzy
     *
     * Returns int sum
     */
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

    /**
     * Checks if selected are either
     * Stair Low, Stair High, House or Chance
     *
     * Returns int sum
     */
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

        // dd($selection);
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
                for ($incre = 0; $incre < $antal; $incre++) {
                    $sum += $key;
                    // echo $sum;
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
        $resultLow = array_intersect_assoc($sumNumber, $stairLow);
        $resultHigh = array_intersect_assoc($sumNumber, $stairHigh);

        if (count($resultLow) == 5) {
            session(['stairLow' => 15]);
            session()->increment('specialSumma', 15);

            return 15;
        }
        if (count($resultHigh) == 5) {
            session(['stairHigh' => 20]);
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
        foreach ($newArray as $value) {
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
}
