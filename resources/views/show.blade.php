<?php

$header = "Board for " . $board->name;


?>


@extends('layout.layout')

@section('content')

<h1>{{ $header }}</h1>

<div class="board">
    <table>
        <thead>
            <tr>
                <th>{{ $board->name }}</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ett</td>
                <td>{{ $board->select1 }}</td>
            </tr>
            <tr>
                <td>Två</td>
                <td>{{ $board->select2 }}</td>
            </tr>
            <tr>
                <td>Tre</td>
                <td>{{ $board->select3 }}</td>
            </tr>
            <tr>
                <td>Fyra</td>
                <td>{{ $board->select4 }}</td>
            </tr>
            <tr>
                <td>Fem</td>
                <td>{{ $board->select5 }}</td>
            </tr>
            <tr>
                <td>Sex</td>
                <td>{{ $board->select6 }}</td>
            </tr>
            <tr class="bold">
                <td>Summa</td>
                <td>{{ $board->summa }}</td>
            </tr>
            <tr>
                <td>Bonus</td>
                <td>{{ $board->bonus }}</td>
            </tr>
            <tr>
                <td>Par</td>
                <td>{{ $board->pair }}</td>
            </tr>
            <tr>
                <td>Två Par</td>
                <td>{{ $board->twopair }}</td>
            </tr>
            <tr>
                <td>Tretal</td>
                <td>{{ $board->three }}</td>
            </tr>
            <tr>
                <td>Fyrtal</td>
                <td>{{ $board->four }}</td>
            </tr>
            <tr>
                <td>Liten Stege</td>
                <td>{{ $board->stairLow }}</td>
            </tr>
            <tr>
                <td>Stor Stege</td>
                <td>{{ $board->stairHigh }}</td>
            </tr>
            <tr>
                <td>Kåk</td>
                <td>{{ $board->house }}</td>
            </tr>
            <tr>
                <td>Chans</td>
                <td>{{ $board->chance }}</td>
            </tr>
            <tr>
                <td>Yatzy</td>
                <td>{{ $board->five }}</td>
            </tr>
            <tr class="bold">
                <td>Total</td>
                <td>{{ $board->total }}</td>
            </tr>
        </tbody>



</div>

@endsection
