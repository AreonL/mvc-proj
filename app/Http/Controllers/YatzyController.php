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
    public function index()
    {
        $roll = session("roll", null);
        $firstRoll = session("firstRoll", null);
        if ($firstRoll) {
            $data = $this->firstRoll();
        } elseif ($roll) {
            $data = $this->roll();
        } else { // Setup will run if I remove this
            $data = $this->setup();
        }
        return view('yatzy', [
            'dh' => $data['dh'] ?? null,
            'roll' => $data['roll'] ?? null,
            'yatzy' => $data['yatzy'] ?? null,
            'session' => session()->all(),
        ]);
    }

    public function game()
    {
        session(["roll" => $_POST["roll"] ?? null]);
        session(["firstRoll" => $_POST["firstRoll"] ?? null]);
        session(["end" => $_POST["end"] ?? null]);
        session(["check" => $_POST["check"] ?? null]);
        session(["selection" => $_POST["selection"] ?? null]);
        return redirect("/yatzy");
    }

    public function setup(): array
    {
        $data = [
            "yatzy" => true,
        ];
        session(["sum" => 0]);
        session(["diceHand" => new DiceHand()]);
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
        session(["summa" => 0]);
        session(["bonus" => null]);
        return $data;
    }

    public function firstRoll(): array
    {
        // Checking so Session has everything needed
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);
        session(['summa' => session('summa') ?? 0]);
        $data = [
            "roll" => true,
        ];
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

        $data["dh"] = session("diceHand")->getHand();
        return $data;
    }

    public function roll(): array
    {
        // Checking so Session has everything needed
        session(['diceHand' => session('diceHand') ?? new DiceHand()]);
        session(['summa' => session('summa') ?? 0]);
        session(['sum' => session('sum') ?? 0]);
        session(['check' => session('check') ?? null]);
        session(['rollCounter' => session('rollCounter') ?? 1]);

        // Set roll to true for view
        $data = [
            "roll" => true,
        ];

        // Check if selected
        $this->selection();

        // $this->specialSelection();

        // Check if bonus
        $yatzy = new Yatzy();
        $yatzy->bonus();

        // Checkbox array to see what needs to be rolled
        $trueRoll = $this->trueRoll();
        if (in_array(true, $trueRoll)) {
            session("diceHand")->rollTrue($trueRoll);
        }
        $data["dh"] = session("diceHand")->getHand();
        session()->increment("sum", (int)session("diceHand")->getSum());

        // add player rolls
        session()->increment("rollCounter", 1);
        $data["summa"] = session("summa");
        return $data;
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

        $selection = session('selection')[0] ?? null;
        
        if (strlen($selection) == 1) {
            $sumNumber = session('diceHand')->getSumNumber((int)$selection) ?? 0;
            session([('select' . $selection) => $sumNumber]);
            session(['rollCounter' => 0]);
            session(['check' => ["0", "1", "2", "3", "4"]]);
            session()->increment('summa', $sumNumber);
            session(['end' => $this->checkAllBoxes()]);
            session(['selection' => null]);
            return;
        } elseif (strlen($selection) > 1) {
            $sumNumber = $this->specialSelection($selection);
            session([($selection) => $sumNumber]);
            session(['rollCounter' => 0]);
            session(['check' => ["0", "1", "2", "3", "4"]]);
            session()->increment('specialSumma', $sumNumber);
            session(['end' => $this->checkAllBoxes()]);
            session(['selection' => null]);
            return;
        }
    }

    public function specialSelection($selection): int
    {
        $sumNumber = session('diceHand')->getArrayDiceNumber() ?? 0;
        var_dump($sumNumber);
        $sum = 0;
        switch ($selection) {
            case 'pair':
                $sum = $this->pair($sumNumber);
                break;
            case 'twopair':
                $sum = $this->twopair($sumNumber);
                break;
            case 'three':
                $sum = $this->threeFourFive($sumNumber, 3);
                break;
            case 'four':
                $sum = $this->threeFourFive($sumNumber, 4);
                break;
            case 'five':
                $sum = $this->threeFourFive($sumNumber, 5);
                break;
            case 'stairLow':
                $sum = $this->stairLow($sumNumber);
                break;
            case 'stairHigh':
                $sum = $this->stairHigh($sumNumber);
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
        $sumNumber = array_count_values($sumNumber);
        foreach ($sumNumber as $key => $value) {
            if ($value >= $antal) {
                // Yatzy
                if ($antal == 5) {
                    return 50;
                }
                // Three and Four
                for ($i = 0; $i < $antal; $i++) { 
                    $sum += $key;
                }
                return $sum;
            }
        }
        return 0;
    }

    public function stairLow($sumNumber): int
    {
        sort($sumNumber);
        $stairArray = array(1, 2, 3, 4, 5);
        $result = array_intersect_assoc($sumNumber, $stairArray);
        if (count($result) == 5) {
            return 15;
        }
        return 0;
    }

    public function stairHigh($sumNumber): int
    {
        sort($sumNumber);
        $stairArray = array(2, 3, 4, 5, 6);
        $result = array_intersect_assoc($sumNumber, $stairArray);
        if (count($result) == 5) {
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
        if ($highNumber == true && $lowNumber == true) {
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

    // public function bonus(): void
    // {
    //     $summa = session('summa');
    //     if ($summa >= 63) {
    //         session(['bonus' => 50]);
    //     }
    // }

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
        var_dump($count);

        if ($count == 15) {
            return true;
        }
        return false;
    }
}
