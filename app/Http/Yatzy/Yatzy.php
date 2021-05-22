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

}