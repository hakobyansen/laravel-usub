<?php

return [
    // Token expiration time in minutes
    'expiration' => 120,

    // Token length
    'length' => 100,

    /**
     * Default url where user will be redirected on sign in whenever it's not overridden
     * by redirect_to_on_sign_in key in request, e.g. by hidden input field.
     */
    'redirect_to_on_sign_in' => '/',

    /**
     * Default url where user will be redirected on sign out whenever it's not overridden
     * by redirect_to_on_sign_out key in request, e.g. by hidden input field.
     */
    'redirect_to_on_sign_out' => '/',

    /**
     * Url where user will be redirected when token cookie expired.
     */
    'redirect_to_on_cookie_expiration' => '/'
];