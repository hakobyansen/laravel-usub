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
        return $this->model->create( $data );
    }

    /**
     * @param string $token
     * @param string $expirationDate
     * @return UsubToken|null
     */
    public function getByToken( string $token, string $expirationDate ): ?UsubToken
    {
        $query = $this->model
            ->where('token', $token);

        if( !is_null( $expirationDate ) )
        {
            $query = $query->whereDate('expires_at', '<', $expirationDate );
        }

        return $query->first();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @param string $expirationDate
     * @return int
     */
    public function deleteExpiredTokens( string $expirationDate ): int
    {
        return $this->model
            ->where('expires_at', '<',  $expirationDate)
            ->delete();
    }
}