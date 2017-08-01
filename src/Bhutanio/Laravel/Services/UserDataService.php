<?php

namespace Bhutanio\Laravel\Services;

use Illuminate\Support\Facades\Auth;

abstract class UserDataService
{
    protected $user;

    public function __construct()
    {
        $this->user = $this->loadUser();
    }

    public function user()
    {
        return $this->user;
    }

    public function is($group)
    {

    }

    public function can()
    {

    }

    protected function loadUser()
    {
        return Auth::user();
    }
}