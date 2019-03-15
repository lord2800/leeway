<?php

namespace Tests\Leeway\Query;

use Tests\Leeway\TestCase;

class DropTableTest extends TestCase
{
	public function testDropTable()
	{
		$drop = $this->factory
			->dropTable('users');

		$this->assertSql('DROP TABLE `users`', $drop);
		$this->assertParams([], $drop);
	}

	public function testDropTableIfExists()
	{
		$drop = $this->factory
			->dropTable('users')
			->ifExists();

		$this->assertSql('DROP TABLE IF EXISTS `users`', $drop);
		$this->assertParams([], $drop);
	}
}
