<?php

namespace Tests\Integration;

use Tests\UsubTestCase;
use Usub\Core\UsubService;
use Usub\Core\UsubTokenRepository;
use Usub\Models\UsubToken;

class UsubTokensRepositoryUsubTest extends UsubTestCase
{
    protected $repo;
    protected $service;
    protected $token;
    protected $expirationMins;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new UsubTokenRepository(
            new UsubToken()
        );

        $this->service = new UsubService( $this->repo );

        $this->expirationMins = 30;

        $this->token = [
            'token'       => 's0m3t0k3n',
            'user1'       => 1,
            'user2'       => 2,
            'redirect_to' => '/',
            'expires_at'  => $this->service->getTokenExpirationDate( 30 )
        ];
    }

    public function testSave()
    {
        $created = $this->repo->save( $this->token );

        $this->assertEquals('s0m3t0k3n', $created->token );
    }

    public function testGetByToken()
    {
        $this->repo->save( $this->token );

        $token = $this->repo->getByToken(
            $this->token[ 'token' ], $this->token[ 'expires_at' ]
        );

        $this->assertEquals(
            $token->token, $token[ 'token' ]
        );
    }

    public function testDeleteExpiredTokens()
    {
        // Testing deletion of expired tokens

        $expectedCount = 3;

        for($i =0; $i < $expectedCount; $i++)
        {
            $this->repo->save( $this->token );
        }

        $deletedCount = $this->repo->deleteExpiredTokens(
            $this->service->getTokenExpirationDate( 40 )
        );

        $this->assertEquals( $expectedCount, $deletedCount );

        /*
         * Testing deletion of non-expired tokens
         * to make sure that non of non-expired tokens
         * will be deleted.
         */

        $this->repo->save( $this->token );

        $deletedCount = $this->repo->deleteExpiredTokens(
            $this->service->getTokenExpirationDate( 20 )
        );

        $this->assertEquals( 0, $deletedCount );
    }
}
