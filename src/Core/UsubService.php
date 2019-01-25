<?php

namespace Usub\Core;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
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
     * @param int $user1
     * @param int $user2
     * @param string $redirectTo
     * @return UsubToken
     * @throws \Exception
     */
    public function storeToken( int $user1, int $user2, string $redirectTo ): UsubToken
    {
        $token = $this->generateToken();

        $usubToken = $this->tokenRepo->save([
            'token'       => $token,
            'user1'       => $user1,
            'user2'       => $user2,
            'redirect_to' => $redirectTo,
            'expires_at'  => $this->getTokenExpirationDate()
        ]);

        Cookie::queue( Cookie::make( 'usub_token', $token,  Config::get( 'usub.expiration' ) ) );

        return $usubToken;
    }

    /**
     * @return int|null
     * @throws \Exception
     */
    public function getUsubTokenInstance(): ?UsubToken
    {
        $token = Cookie::get('usub_token');

        if( !$token )
        {
            return null;
        }

        $usubToken = $this->tokenRepo->getByToken( $token, $this->getTokenExpirationDate() );

        return $usubToken;
    }

    /**
     * @param UsubToken $usubToken
     * @return int|null
     */
    public function getAdminId( UsubToken $usubToken ): ?int
    {
        $adminId = null;

        if ( !is_null($usubToken) )
        {
            if( Auth::id() == $usubToken->user2 )
            {
                $adminId = $usubToken->user1;
            }
        }

        return $adminId;
    }

    /**
     * @param UsubToken $usubToken
     * @return string
     */
    public function getRedirectTo( UsubToken $usubToken ): string
    {
        return $usubToken->redirect_to;
    }

    /**
     * @param int|null $tokenExpirationMinutes
     * @return string
     * @throws \Exception
     */
    public function getTokenExpirationDate( int $tokenExpirationMinutes = null )
    {
        if( is_null($tokenExpirationMinutes) )
        {
            $tokenExpirationMinutes = Config::get( 'usub.expiration' );
        }

        $dateTime = new \DateTime();
        $dateTime->modify( "+$tokenExpirationMinutes minutes" );

        return $dateTime->format( 'Y-m-d H:i:s' );
    }
}