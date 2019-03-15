<?php

namespace Tests\Leeway\Query;

use Tests\Leeway\TestCase;

use function Leeway\column;
use function Leeway\foreignKey;

class AlterTableTest extends TestCase
{
	public function testAlterTable()
	{
		$alter = $this->factory
			->alterTable('users');

		$this->assertSql('ALTER TABLE `users`', $alter);
		$this->assertParams([], $alter);
	}

	public function testAlterTableWithRenameTable()
	{
		$alter = $this->factory
			->alterTable('users')
			->rename('users2');

		$this->assertSql('ALTER TABLE `users` RENAME TO `users2`', $alter);
		$this->assertParams([], $alter);
	}

	public function testAlterTableWithAddColumn()
	{
		$alter = $this->factory
			->alterTable('users')
			->addColumn(column('name')->type('varchar(255)')->unique()->notNull());

		$this->assertSql('ALTER TABLE `users` ADD COLUMN `name` varchar(255) UNIQUE NOT NULL', $alter);
		$this->assertParams([], $alter);
	}

	public function testAlterTableWithDropColumn()
	{
		$alter = $this->factory
			->alterTable('users')
			->dropColumn(column('name'));

		$this->assertSql('ALTER TABLE `users` DROP COLUMN `name`', $alter);
		$this->assertParams([], $alter);
	}

	public function testAlterTableWithChangeColumn()
	{
		$alter = $this->factory
			->alterTable('users')
			->changeColumn(
				column('name'),
				column('first_name')->type('varchar(255)')->unique()->notNull()
			);

		$this->assertSql('ALTER TABLE `users` CHANGE COLUMN `name` `first_name` varchar(255) UNIQUE NOT NULL', $alter);
		$this->assertParams([], $alter);
	}

	public function testAlterTableWithRenameColumn()
	{
		$alter = $this->factory
			->alterTable('users')
			->renameColumn(
				column('name'),
				column('first_name')
			);

		$this->assertSql('ALTER TABLE `users` RENAME COLUMN `name` `first_name`', $alter);
		$this->assertParams([], $alter);
	}
}
