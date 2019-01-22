<?php

namespace Usub\Core;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Usub\Models\UsubToken;

class UsubService
{
    private $tokenRepo;

    /**
     * UsubService constructor.
     * @param IUsubTokenRepository $repo
     */
    public function __construct( IUsubTokenRepository $repo )
    {
        $this->tokenRepo = $repo;
    }

    /**
     * @return string
     */
    public function generateToken(): string
    {
        return str_random( Config::get( 'usub.length' ) );
    }

    /**
     * @param $user1
     * @param $user2
     * @return UsubToken
     * @throws \Exception
     */
    public function storeToken( $user1, $user2 ): UsubToken
    {
        $tokenExpirationMinutes = Config::get( 'usub.expiration' );

        $dateTime = new \DateTime();
        $dateTime->modify( '+30 minutes' );

        $tokenExpirationDate = $dateTime->modify( 'Y-m-d H:i:s' );

        $token = $this->generateToken();

        Cookie::queue( Cookie::make( 'usub_token', $token,  $tokenExpirationMinutes ) );

        return $this->tokenRepo->save([
            'token'       => $token,
            'user1'       => $user1,
            'user2'       => $user2,
            'redirect_to' => Config::get( 'usub.redirect_to' ),
            'expires_at'  => $tokenExpirationDate
        ]);
    }

    /**
     * @return int|null
     */
    public function getAdminId(): ?int
    {
        $token = Cookie::get('usub_token');

        $adminId = null;

        $usubToken = $this->tokenRepo->getByToken( $token );

        if ( !is_null($usubToken) )
        {
            if( Auth::id() == $usubToken->user2 )
            {
                $adminId = $usubToken->user1;
            }
        }

        return $adminId;
    }
}