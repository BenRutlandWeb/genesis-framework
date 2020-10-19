<?php

namespace Genesis\Auth;

use Genesis\Database\Models\User;

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
     * @param string  $login    The user login name/email address.
     * @param string  $password The user plain text password.
     * @param boolean $remember The remember token.
     *
     * @return boolean
     */
    public function login(string $login, string $password, ?bool $remember = false): bool
    {
        $success = wp_signon(
            [
                'user_login'    => $login,
                'user_password' => $password,
                'remember'      => $remember ?? false,
            ]
        );
        return !is_wp_error($success);
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
