<?php

namespace Tests\Unit;


use Usub\Core\UsubService;
use Usub\Core\UsubTokenRepository;
use Usub\Models\UsubToken;

class UsubServiceTest extends \PHPUnit\Framework\TestCase
{
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new UsubService(
            new UsubTokenRepository( new UsubToken() )
        );
    }

    public function testGenerateToken()
    {
        $expectedLength = 100;

        $token = $this->service->generateToken( $expectedLength );

        $this->assertEquals( $expectedLength, strlen($token) );
    }

    public function testGetTokenExpirationDate()
    {
        $expectedMins = 30;

        $dateNow = (new \DateTime())->format('Y-m-d H:i:s');

        $dateExpiration = $this->service->getTokenExpirationDate( $expectedMins );

        $interval = date_diff(
            new \DateTime( $dateNow ), new \DateTime( $dateExpiration )
        );

        $this->assertEquals( $expectedMins, $interval->i );
    }
}