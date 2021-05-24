<?php

namespace App\Http\Controllers;

use App\Models\Highscore;
use App\Models\Board;

class HighscoreController extends Controller
{
    public function index()
    {
        $highscore = Highscore::all();

        $arr = array();

        foreach ($highscore as $key) {
            $arr[] = array($key['name'], $key['score'], $key['id']);
        }

        usort($arr, function ($number1, $number2) {
            return $number2[1] <=> $number1[1];
        });

        return view('highscore', [
            'header' => 'Highscore!',
            'highscore' => $arr,
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
        ]);

        $highscore = new Highscore();
        $highscore->name  = request('name');
        $highscore->score = request('total') ?? 0;
        $highscore->save();


        $board = new Board();
        $board->name        = request('name') ?? 0;
        $board->select1     = request('select1') ?? 0;
        $board->select2     = request('select2') ?? 0;
        $board->select3     = request('select3') ?? 0;
        $board->select4     = request('select4') ?? 0;
        $board->select5     = request('select5') ?? 0;
        $board->select6     = request('select6') ?? 0;
        $board->pair        = request('pair') ?? 0;
        $board->twopair     = request('twopair') ?? 0;
        $board->three       = request('three') ?? 0;
        $board->four        = request('four') ?? 0;
        $board->five        = request('five') ?? 0;
        $board->stairLow    = request('stairLow') ?? 0;
        $board->stairHigh   = request('stairHigh') ?? 0;
        $board->house       = request('house') ?? 0;
        $board->chance      = request('chance') ?? 0;
        $board->summa       = request('summa') ?? 0;
        $board->bonus       = request('bonus') ?? 0;
        $board->total       = request('total') ?? 0;
        $board->save();

        session()->flush();

        return redirect("/yatzy/setup");
    }

    /**
     * @param integer $id
     */
    public function show($id)
    {
        $board = Board::find($id);

        return view('show', compact('board'));
    }
}
