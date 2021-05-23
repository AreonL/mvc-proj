<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function session()
    {
        return view('session', [
            'session' => session()->all(),
        ]);
    }

    public function destroy()
    {
        session()->flush();

        return redirect("/session");
    }
}
