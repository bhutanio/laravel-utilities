<?php

namespace Bhutanio\Laravel\Contracts\Services;

interface GatekeeperInterface
{
    /**
     * Cache current authenticated user.
     *
     * @param int $duration
     *
     * @return self
     */
    public function cache($duration);

    /**
     * Bootstrap authentication.
     *
     * @return bool
     */
    public function authenticate();

    /**
     * Get current authenticated user with details.
     *
     * @return \Illuminate\Foundation\Auth\User;
     */
    public function user();

    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    public function check();

    /**
     * Check if the user is guest.
     *
     * @return bool
     */
    public function guest();

    /**
     * Check if the authenticated user belongs to $group.
     *
     * @param int|string $group Group ID or Name
     *
     * @return bool
     */
    public function groupIs($group);

    /**
     * Check if user can perform action on a given route.
     *
     * @param string           $action
     * @param \App\Models\User $user
     * @param string           $route
     *
     * @return bool
     */
    public function routeAcl($action, $user, $route);

    /**
     * Check if user can perform action on a given model.
     *
     * @param string                              $action
     * @param \App\Models\User                    $user
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function modelAcl($action, $user, $model);
}
