<?php

return [
	// Token expiration time in minutes
	'expiration' => env('USUB_TOKEN_EXPIRATION', 120),

	// Token length
	'length' => env('USUB_TOKEN_LENGTH', 100),

	/*
    * Default url where user will be redirected on sign in whenever it's not overridden
    * by redirect_to_on_sign_in key in request, e.g. by hidden input field.
    */
	'redirect_to_on_sign_in' => env('USUB_REDIRECT_TO_ON_SIGN_IN', '/'),

	/*
    * Default url where user will be redirected on sign out whenever it's not overridden
    * by redirect_to_on_sign_out key in request, e.g. by hidden input field.
    */
	'redirect_to_on_sign_out' => env('USUB_REDIRECT_TO_ON_SIGN_OUT', '/'),


	// Url where user will be redirected when token cookie expired.
	'redirect_to_on_cookie_expiration' => env('USUB_REDIRECT_TO_ON_COOKIE_EXPIRATION', '/'),


	// Package will deny not whitelisted ip address whenever set to true
	'whitelisting' => env( 'USUB_WHITELISTING', false )
];
