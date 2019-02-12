<?php

namespace Usub\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Usub\Core\IUsubWhitelistRepository;

class UsubWhitelistController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $whitelistRepo;

	const PAGINATION_COUNT = 25;

	public function __construct( IUsubWhitelistRepository $repo )
	{
		$this->whitelistRepo = $repo;
	}

	public function index()
	{
		$whitelistItems = $this->whitelistRepo->paginateAll( self::PAGINATION_COUNT );
	}

	public function store( Request $request )
	{
		$data = [
			'ip_address' => $request->get( 'ip_address' ),
			'created_at' => date( 'Y-m-d H:i:s' )
		];

		$this->whitelistRepo->save( $data );
	}

	public function destroy( $whitelistId )
	{
		$this->whitelistRepo->deleteById( $whitelistId );
	}
}
