<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Usub\Core\UsubWhitelistRepository;
use Usub\Models\UsubWhitelist;

class UsubWhitelistRepositoryTest extends TestCase
{
	protected $repo;

	protected function setUp()
	{
		parent::setUp();

		$this->repo = new UsubWhitelistRepository(
			new UsubWhitelist()
		);
	}

	public function testGetAll()
	{

	}

	public function testPaginateAll()
	{

	}

	public function testDeleteById()
	{

	}

	public function testTruncate()
	{

	}

	public function testSave()
	{

	}
}
