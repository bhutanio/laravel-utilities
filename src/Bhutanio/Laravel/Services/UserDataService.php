<?php

namespace Bhutanio\Laravel\Services;

use Illuminate\Support\Facades\Auth;

class UserDataService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function is($group)
    {

    }

    public function can()
    {

    }
}