<?php

namespace Usub\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Usub\Models\UsubWhitelist;

class UsubWhitelistRepository implements IUsubWhitelistRepository
{
	protected $model;

	/**
	 * UsubWhitelistRepository constructor.
	 * @param UsubWhitelist $model
	 */
	public function __construct( UsubWhitelist $model )
	{
		$this->model = $model;
	}

	/**
	 * @return mixed
	 */
	public function getAll()
	{
		return $this->model
			->orderBy( 'created_at', 'DESC' )
			->get();
	}

	/**
	 * @param int $paginationItemsCount
	 * @return LengthAwarePaginator
	 */
	public function paginateAll( int $paginationItemsCount ): LengthAwarePaginator
	{
		return $this->model
			->orderBy( 'created_at', 'DESC' )
			->paginate( $paginationItemsCount );
	}

	/**
	 * @param int $whiteListId
	 * @return int
	 */
	public function deleteById( int $whiteListId ): int
	{
		return $this->model
			->where( 'id', $whiteListId )
			->delete();
	}

	/**
	 * @return mixed
	 */
	public function truncate()
	{
		return $this->model->truncate();
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function save( array $data )
	{
		return $this->model->updateOrCreate([
			'ip_address' => $data[ 'ip_address' ]
		], $data);
	}
}
