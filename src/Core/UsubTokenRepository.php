<?php

namespace Usub\Core;

use Usub\Models\UsubToken;

class UsubTokenRepository implements IUsubTokenRepository
{
    protected $model;

    /**
     * UsubTokenRepository constructor.
     * @param UsubToken $model
     */
    public function __construct(UsubToken $model )
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return UsubToken
     */
    public function save( array $data ): UsubToken
    {
        // TODO: Implement save() method.
    }

    /**
     * @param int $tokenId
     * @return UsubToken
     */
    public function getById( int $tokenId ): UsubToken
    {
        // TODO: Implement getById() method.
    }

    /**
     * @param string $token
     * @return UsubToken
     */
    public function getByToken( string $token ): UsubToken
    {
        return $this->model
            ->where('token', $token)
            ->first();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @return int
     */
    public function deleteExpiredTokens(): int
    {
        // TODO: Implement deleteExpiredTokens() method.
    }

    public function delete( int $tokenId ): int
    {
        // TODO: Implement delete() method.
    }
}