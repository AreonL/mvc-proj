<?php

namespace App\Http\Controllers;

use App\Models\Highscore;
use App\Models\Board;

class HighscoreController extends Controller
{
    public function index()
    {
        $highscore = Highscore::all();

        // dd($highscore);

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
        $highscore->score = request('total');
        $highscore->save();


        $board = new Board();
        $board->name        = request('name');
        $board->select1     = request('select1');
        $board->select2     = request('select2');
        $board->select3     = request('select3');
        $board->select4     = request('select4');
        $board->select5     = request('select5');
        $board->select6     = request('select6');
        $board->pair        = request('pair');
        $board->twopair     = request('twopair');
        $board->three       = request('three');
        $board->four        = request('four');
        $board->five        = request('five');
        $board->stairLow    = request('stairLow');
        $board->stairHigh   = request('stairHigh');
        $board->house       = request('house');
        $board->chance      = request('chance');
        $board->summa       = request('summa');
        $board->bonus       = request('bonus');
        $board->total       = request('total');
        $board->save();

        session()->flush();

        return redirect("/yatzy/setup");
    }

    public function show($id)
    {
        $board = Board::find($id);
        
        return view('show', compact('board'));
    }
}
