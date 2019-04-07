<?php

namespace Usub\Core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
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
     * @param int|null $length
     * @return string
     */
    public function generateToken( int $length = null ): string
    {
        if ( !is_null( $length ) )
        {
            return Str::random( $length );
        }

        return Str::random( Config::get( 'usub.length' ) );
    }

    /**
     * @param int $user1
     * @param int $user2
     * @param string $redirectTo
     * @param int|null $expirationMins
     * @return UsubToken
     * @throws \Exception
     */
    public function storeToken( int $user1, int $user2, string $redirectTo, int $expirationMins = null ): UsubToken
    {
        $token = $this->generateToken();

        $usubToken = $this->tokenRepo->save([
            'token'       => $token,
            'user1'       => $user1,
            'user2'       => $user2,
            'redirect_to' => $redirectTo,
            'expires_at'  => $this->getTokenExpirationDate()
        ]);

        if( is_null( $expirationMins ) )
        {
            $expirationMins = Config::get( 'usub.expiration' );
        }

        $this->storeUsubTokenCookie( $token, $expirationMins );

        return $usubToken;
    }

    /**
     * @return UsubToken|null
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
     * @param int $authUserId
     * @return int|null
     */
    public function getAdminId( UsubToken $usubToken, int $authUserId ): ?int
    {
        $adminId = null;

        if ( !is_null($usubToken) )
        {
            if( $authUserId == $usubToken->user2 )
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

    /**
     * @param string $token
     * @param int $expirationMins
     * @return void
     */
    public function storeUsubTokenCookie(string $token, int $expirationMins )
    {
        Cookie::queue( Cookie::make( 'usub_token', $token,  $expirationMins) );
    }

    /**
     * @return string|null
     */
    public function getUsubTokenCookie(): ?string
    {
        return Cookie::get( 'usub_token' );
    }

    /**
     * @return void
     */
    public function deleteUsubTokenCookie()
    {
        Cookie::queue( Cookie::forget('usub_token') );
    }
}
