<?php

namespace Bhutanio\Laravel\Services;

use Bhutanio\Laravel\Contracts\Services\GatekeeperInterface;
use App\Models\User;
use Cookie;

class Gatekeeper implements GatekeeperInterface
{
    /**
     * @var \Illuminate\Auth\SessionGuard|\Illuminate\Auth\TokenGuard
     */
    protected $auth;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var int
     */
    protected $user_id;

    /**
     * @var bool|int
     */
    protected $cached = false;

    public function __construct($cache = false)
    {
        $this->auth = auth();
        $this->cache = app('cache');
        $this->session = app('session');

        $this->user_id = $this->session->get($this->auth->getName());
        $this->user = null;

        $this->cache($cache)->authenticate();
    }

    /**
     * Cache current authenticated user.
     *
     * @param int $duration
     *
     * @return self
     */
    public function cache($duration = 0)
    {
        if (!empty($duration)) {
            $this->cached = $duration;
        }

        return $this;
    }

    /**
     * Bootstrap authentication.
     *
     * @return bool
     */
    public function authenticate()
    {
        if ($this->cached && $this->user_id) {
            $user = $this->cache->get('auth:'.$this->user_id);
            if ($user) {
                $this->hydrateUserData($user);

                return true;
            }
        }

        if ($this->auth->check()) {
            $this->user_id = $this->auth->user()->id;
            $this->user = $this->auth->user();
            $group = $this->auth->user()->group;
            if ($this->cached) {
                $this->cache->put('auth:'.$this->user_id, $this->user->toArray(), $this->cached);
            }

            $this->updateUserLastAccess($this->user);

            return true;
        }

        $this->killDeadCookies();

        return false;
    }

    /**
     * Get current authenticated user with details.
     *
     * @return User;
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        if (!empty($this->user) && $this->user_id) {
            return true;
        }
        if ($this->authenticate()) {
            return true;
        }

        return false;
    }

    /**
     * Check if the user is guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Check if the authenticated user belongs to $group.
     *
     * @param int|string $group Group ID or Name
     *
     * @return bool
     */
    public function groupIs($group)
    {
        // TODO: Implement groupIs() method.
    }

    /**
     * Check if user can perform action on a given route.
     *
     * @param string           $action
     * @param \App\Models\User $user
     * @param string           $route
     *
     * @return bool
     */
    public function routeAcl($action, $user, $route)
    {
        // TODO: Implement routeAcl() method.
    }

    /**
     * Check if user can perform action on a given model.
     *
     * @param string                              $action
     * @param \App\Models\User                    $user
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function modelAcl($action, $user, $model)
    {
        // TODO: Implement modelAcl() method.
    }

    /**
     * @param $user array
     */
    private function hydrateUserData(array $user)
    {
        //        $group = UserGroup::hydrate([$user['group']])->first();
//        unset($user['group']);
//        $this->user = User::hydrate([$user])->first();
//        $this->user->group = $group;
        $this->user = json_decode(json_encode($user));
    }

    private function killDeadCookies()
    {
        $cookies = $_COOKIE;
        if (is_array($cookies)) {
            foreach ($cookies as $key => $cookie) {
                if (strpos($key, 'remember_') !== false) {
                    Cookie::queue($key, null, -9999);
                }
            }
        }
    }

    private function updateUserLastAccess($user)
    {
        $user->ip_address = get_ip();
        $user->country_code = geoip_country_code($user->ip_address);
        $user->last_access = carbon();
        $user->save();
    }
}
