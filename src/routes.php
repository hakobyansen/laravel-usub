<?php

Route::post('usub/sign-in', '\Usub\Http\Controllers\UsubTokensController@signIn')->name('usub.sign_in');
Route::post('usub/sign-out', '\Usub\Http\Controllers\UsubTokensController@signOut')->name('usub.sign_out');
