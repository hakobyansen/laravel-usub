<?php

namespace Tests\Integration;

use Tests\TestCase;
use Usub\Models\UsubToken;
use Usub\Core\UsubTokenRepository;
use Usub\Core\UsubService;

class UsubServiceTest extends TestCase
{
    protected $service;
    protected $repo;
    protected $tokenAr;
    protected $usubToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new UsubTokenRepository(
            new UsubToken()
        );

        $this->service = new UsubService( $this->repo );

        $this->tokenAr = [
            'token'       => 's0m3t0k3n',
            'user1'       => 1,
            'user2'       => 2,
            'redirect_to' => '/',
            'expires_at'  => $this->service->getTokenExpirationDate( 30 )
        ];

        $this->usubToken = $this->repo->save( $this->tokenAr );
    }

    public function testGetAdminId()
    {
        $adminId = $this->service->getAdminId(
            $this->usubToken, $this->tokenAr['user2']
        );

        $this->assertEquals( $this->tokenAr['user1'], $adminId );
    }

    public function testGetRedirectTo()
    {
        $redirectTo = $this->service->getRedirectTo( $this->usubToken );

        $this->assertEquals( $this->tokenAr['redirect_to'], $redirectTo );
    }
}
