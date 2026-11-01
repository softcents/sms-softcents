<?php

namespace App\Library;

class CustomGateway
{
    public static function status()
    {
        return redirect()->route('users.dashboard.index');
    }
}
