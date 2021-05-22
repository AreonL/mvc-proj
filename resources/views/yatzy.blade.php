<?php

$header = "Yatzy";
$message = "A game of Yatzy";
$end = $session["end"] ?? null;
$action = $action ?? url("/yatzy/game");
$yatzy = $yatzy ?? null;
$dh = $dh ?? null;
$roll = $roll ?? null;
$select1 = $session["select1"] ?? null;
$select2 = $session["select2"] ?? null;
$select3 = $session["select3"] ?? null;
$select4 = $session["select4"] ?? null;
$select5 = $session["select5"] ?? null;
$select6 = $session["select6"] ?? null;
$pair = $session["pair"] ?? null;
$twopair = $session["twopair"] ?? null;
$three = $session["three"] ?? null;
$four = $session["four"] ?? null;
$five = $session["five"] ?? null;
$stairLow = $session["stairLow"] ?? null;
$stairHigh = $session["stairHigh"] ?? null;
$house = $session["house"] ?? null;
$chance = $session["chance"] ?? null;
$specialSumma = $session["specialSumma"] ?? null;
$summa = $session["summa"] ?? 0;
$bonus = $session["bonus"] ?? 0;

?>

@extends('layout.layout')

@section('content')

<h1>{{ $header }}</h1>

<!-- If first visit -->
@if($end)
    <p>The end</p>
    <p>Total sum: {{ $bonus + $summa + $specialSumma }}</p>
    <form method="post" action="{{ url("/highscore/store") }}">
        @csrf
        <label for="name">Namn:</label>
        <input type="text" name="name">
        <div>{{ $errors->first('name') }}</div>

        <input type="hidden" name="select1" value={{ $select1 }}>
        <input type="hidden" name="select2" value={{ $select2 }}>
        <input type="hidden" name="select3" value={{ $select3 }}>
        <input type="hidden" name="select4" value={{ $select4 }}>
        <input type="hidden" name="select5" value={{ $select5 }}>
        <input type="hidden" name="select6" value={{ $select6 }}>
        <input type="hidden" name="pair" value={{ $pair }}>
        <input type="hidden" name="twopair" value={{ $twopair }}>
        <input type="hidden" name="three" value={{ $three }}>
        <input type="hidden" name="four" value={{ $four }}>
        <input type="hidden" name="five" value={{ $five }}>
        <input type="hidden" name="stairLow" value={{ $stairLow }}>
        <input type="hidden" name="stairHigh" value={{ $stairHigh }}>
        <input type="hidden" name="house" value={{ $house }}>
        <input type="hidden" name="chance" value={{ $chance }}>
        <input type="hidden" name="summa" value={{ $summa }}>
        <input type="hidden" name="bonus" value={{ $bonus }}>
        <input type="hidden" name="total" value={{ $bonus + $summa + $specialSumma}}>
        <input type="submit" value="Submit score!">
    </form>
@elseif($yatzy)
    <p>{{ $message }}</p>
    <form method="post" action="{{ $action }}">
        @csrf
        <p>
            <input type="hidden" name="firstRoll" value="firstRoll">
            <input type="submit" value="Play">
        </p>
    </form>
<!-- If pressed play -->
@elseif ($roll)
    <div class="grid-container">
    <div class="dice-grid">
    <p class="dices">{{ $dh }}</p>
    <form method="post" action="{{ $action }}">
        @csrf
        <p>
            <input type="hidden" name="roll" value="roll">
            @if ($session["rollCounter"] < 3)
            <input class="checkbox" type="checkbox" name="check[]" value="0" checked="checked">
            <input class="checkbox" type="checkbox" name="check[]" value="1" checked="checked">
            <input class="checkbox" type="checkbox" name="check[]" value="2" checked="checked">
            <input class="checkbox" type="checkbox" name="check[]" value="3" checked="checked">
            <input class="checkbox" type="checkbox" name="check[]" value="4" checked="checked">
            <br>
            <br>
            <input type="submit" value="Roll selected">
            <br>
            @endif
        </p>
    </form>
    </div>
    <form id="select" class="select" method="post" action="{{ $action }}">
        @csrf
            <p>
                <label>1:or</label>
                @if (is_null($select1))
                <input type="radio" name="selection[]" value=1>
                @else
                    = {{ $select1 }}
                @endif
            </p>
            <p>
                <label>2:or</label>
                @if (is_null($select2))
                <input type="radio" name="selection[]" value=2>
                @else
                    = {{ $select2 }}
                @endif
            </p>
            <p>
                <label>3:or</label>
                @if (is_null($select3))
                <input type="radio" name="selection[]" value=3>
                @else
                    = {{ $select3 }}
                @endif
            </p>
            <p>
            <label>4:or</label>
                @if (is_null($select4))
                <input type="radio" name="selection[]" value="4">
                @else
                    = {{ $select4 }}
                @endif
            </p>
            <p>
            <label>5:or</label>
                @if (is_null($select5))
                <input type="radio" name="selection[]" value="5">
                @else
                    = {{ $select5 }}
                @endif
            </p>
            <p>
            <label>6:or</label>
                @if (is_null($select6))
                <input type="radio" name="selection[]" value="6">
                @else
                    = {{ $select6 }}
                @endif
            </p>
            <p>Summa: {{ $summa }}</p>
            <p>Bonus: {{ $bonus }}</p>
            <p>
                <label>Par</label>
                @if (is_null($pair))
                <input type="radio" name="selection[]" value="pair">
                @else
                    = {{ $pair }}
                @endif
            </p>
            <p>
                <label>Två par</label>
                @if (is_null($twopair))
                <input type="radio" name="selection[]" value="twopair">
                @else
                    = {{ $twopair }}
                @endif
            </p>
            <p>
                <label>Tretal</label>
                @if (is_null($three))
                <input type="radio" name="selection[]" value="three">
                @else
                    = {{ $three }}
                @endif
            </p>
            <p>
                <label>Fyrtal</label>
                @if (is_null($four))
                <input type="radio" name="selection[]" value="four">
                @else
                    = {{ $four }}
                @endif
            </p>
            <p>
                <label>Liten stege</label>
                @if (is_null($stairLow))
                <input type="radio" name="selection[]" value="stairLow">
                @else
                    = {{ $stairLow }}
                @endif
            </p>
            <p>
                <label>Stor stege</label>
                @if (is_null($stairHigh))
                <input type="radio" name="selection[]" value="stairHigh">
                @else
                    = {{ $stairHigh }}
                @endif
            </p>
            <p>
                <label>House</label>
                @if (is_null($house))
                <input type="radio" name="selection[]" value="house">
                @else
                    = {{ $house }}
                @endif
            </p>
            <p>
                <label>Chans</label>
                @if (is_null($chance))
                <input type="radio" name="selection[]" value="chance">
                @else
                    = {{ $chance }}
                @endif
            </p>
            <p>
                <label>Yatzy</label>
                @if (is_null($five))
                <input type="radio" name="selection[]" value="five">
                @else
                    = {{ $five }}
                @endif
            </p>
            <p>Total: {{ $bonus + $summa + $specialSumma }}</p>
            <input type="hidden" name="roll" value="roll">
            <input form="select" type="submit" value="Select">
    </form>
    </div>

@endif

{{-- end of page --}}
@endsection
