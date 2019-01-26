<?php

namespace Usub\Core;

use Usub\Models\UsubToken;

interface IUsubTokenRepository
{
    public function save( array $data ): UsubToken;

    public function getByToken( string $Token, string $expirationDateDate ): ?UsubToken;

    public function deleteExpiredTokens( string $expirationDate ): int;
}