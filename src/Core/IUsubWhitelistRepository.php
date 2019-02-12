<?php

namespace Usub\Core;

interface IUsubWhitelistRepository
{
	public function getAll();

	public function deleteById( int $whiteListId ): int;

	public function truncate();

	public function save( array $data );
}
