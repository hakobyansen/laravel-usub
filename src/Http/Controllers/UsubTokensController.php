<?php

namespace Usub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Usub\Core\UsubService;
use Usub\Core\UsubTokenRepository;
use Usub\Models\UsubToken;

class UsubTokensController extends Controller
{
    protected $usubService;

    /**
     * UsubTokensController constructor.
     */
    public function __construct()
    {
        $this->usubService = new UsubService(
            new UsubTokenRepository( new UsubToken() )
        );

        $this->middleware('auth');

        $this->middleware('usub_sign_out')->only( 'signOut' );
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function signIn( Request $request )
    {
        $user1 = Auth::id();
        $user2 = $request->get('user2');

        $this->usubService->storeToken( $user1, $user2 );

        Auth::loginUsingId( $user2 );
    }

    /**
     * @param Request $request
     */
    public function signOut( Request $request )
    {
        $user2 = Auth::id();

        if( $this->usubService->getAdminId() !== null )
        {
            Cookie::queue( Cookie::forget('usub_token') );

            Auth::loginUsingId( $user2 );
        }
        else
        {
            abort( 401 );
        }
    }
}