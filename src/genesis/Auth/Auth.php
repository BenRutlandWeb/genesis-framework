<?php

namespace Genesis\Auth;

use Genesis\Database\Models\User;
use Genesis\Http\Request;

class Auth
{
    /**
     * Get the current User ID.
     *
     * @return integer
     */
    public function id(): int
    {
        return get_current_user_id();
    }

    /**
     * Get the current User.
     *
     * @return \Genesis\Database\Models\User|null
     */
    public function user(): ?User
    {
        return User::find($this->id());
    }

    /**
     * Check if the user is logged in.
     *
     * @return boolean
     */
    public function check(): bool
    {
        return is_user_logged_in();
    }

    /**
     * Check if the user is a guest (not logged in).
     *
     * @return boolean
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * Log the user in with the credentials supplied.
     *
     * @param \Genesis\Http\Request|string $login    The user login name/email address.
     * @param string|null                  $password The user plain text password.
     * @param boolean|null                 $remember The remember token.
     *
     * @return \Genesis\Database\Models\User|false
     */
    public function login($login, ?string $password = null, ?bool $remember = null)
    {
        if (!$login instanceof Request) {
            $login = [
                'user_login'    => $login,
                'user_password' => $password,
                'remember'      => $remember ?? false,
            ];
        }
        $user = wp_signon($login);

        if (!is_wp_error($user)) {
            return User::find($user->ID);
        }
        return false;
    }

    /**
     * Log the current User out.
     *
     * @return void
     */
    public function logout(): void
    {
        wp_logout();
    }
}
