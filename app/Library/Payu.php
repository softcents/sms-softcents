<?php

namespace App\Library;

class Payu
{
    public static function view()
    {
        return redirect()->route('users.dashboard.index');
    }

    public static function status()
    {
        return redirect()->route('users.dashboard.index');
    }
}
