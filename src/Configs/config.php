<?php

return [
    // Token expiration time in minutes
    'expiration' => 120,

    // Token length
    'length' => 100,

    /**
     * Default url where user will be redirected on sign in and sign out whenever it's not
     * overridden by redirect_to_on_sign_in and redirect_to_on_sign_out keys in request.
     */
    'redirect_to' => '/'
];