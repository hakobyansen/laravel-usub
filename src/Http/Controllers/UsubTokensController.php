<?php

namespace Usub\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Usub\Core\UsubService;
use Usub\Core\UsubTokenRepository;
use Usub\Models\UsubToken;
use Illuminate\Routing\Controller as BaseController;

class UsubTokensController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $usubService;

    /**
     * UsubTokensController constructor.
     */
    public function __construct()
    {
        $this->usubService = new UsubService(
            new UsubTokenRepository( new UsubToken() )
        );

        $this->middleware('web');
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

        $redirectTo = $request->get('redirect_to') ?? Config::get( 'usub.redirect_to' );

        $this->usubService->storeToken( $user1, $user2, $redirectTo );

        Auth::loginUsingId( $user2 );
    }

    /**
     * @param Request $request
     * @throws \Exception
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