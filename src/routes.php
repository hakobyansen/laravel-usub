<?php

Route::post('usub-sign-in', '\Usub\Http\Controllers\UsubTokensControllers@signin')->name('usub.sign_in');
Route::post('usub-sign-out', '\Usub\Http\Controllers\UsubTokensControllers@signin')->name('usub.sign_out');
