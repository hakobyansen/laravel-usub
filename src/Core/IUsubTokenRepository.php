<?php

namespace Usub\Core;

use Usub\Models\UsubToken;

interface IUsubTokenRepository
{
    public function save( array $data ): UsubToken;

    public function delete( int $tokenId ): int;

    public function getById( int $tokenId ): UsubToken;

    public function getByToken( string $Token ): UsubToken;

    public function getAll(): array;

    public function deleteExpiredTokens(): int;
}