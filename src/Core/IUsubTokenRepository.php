<?php

namespace Usub\Core;

use Usub\Models\UsubToken;

interface IUsubTokenRepository
{
    public function save( array $data ): UsubToken;

    public function delete( int $tokenId ): int;

    public function getByToken( string $Token, string $expirationDateDate ): ?UsubToken;

    public function getAll(): array;

    public function deleteExpiredTokens(): int;
}