<?php

namespace Tests\Leeway\Query;

use Tests\Leeway\TestCase;

use function Leeway\column;
use function Leeway\foreignKey;

class CreateTableTest extends TestCase
{
	public function testCreateTable()
	{
		$create = $this->factory
			->createTable('users');

		$this->assertSql('CREATE TABLE `users`', $create);
		$this->assertParams([], $create);
	}

	public function testCreateTempTable()
	{
		$create = $this->factory
			->createTable('users')
			->temporary();

		$this->assertSql('CREATE TEMPORARY TABLE `users`', $create);
		$this->assertParams([], $create);
	}

	public function testCreateTableIfNotExists()
	{
		$create = $this->factory
			->createTable('users')
			->ifNotExists();

		$this->assertSql('CREATE TABLE IF NOT EXISTS `users`', $create);
		$this->assertParams([], $create);
	}

	public function testCreateTempTableIfNotExists()
	{
		$create = $this->factory
			->createTable('users')
			->temporary()
			->ifNotExists();

		$this->assertSql('CREATE TEMPORARY TABLE IF NOT EXISTS `users`', $create);
		$this->assertParams([], $create);
	}

	public function createTableAsSelect()
	{
		$select = $this->factory
			->select()
			->from('users');

		$create = $this->factory
			->createTable('users')
			->asSelect($select);

		$this->assertSql('CREATE TABLE `users` AS SELECT * FROM `users`', $create);
		$this->assertParams([], $create);
	}

	public function testCreateTableWithColumns()
	{
		$create = $this->factory
			->createTable('users')
			->columns([
				column('id')->type('int')->primaryKey()->autoIncrement(),
				column('name')->type('varchar(255)')->unique()->notNull()
			]);

		$this->assertSql('CREATE TABLE `users` (`id` int PRIMARY KEY AUTOINCREMENT, `name` varchar(255) UNIQUE NOT NULL)', $create);
		$this->assertParams([], $create);
	}

	public function testCreateTableForeignKey()
	{
		$create = $this->factory
			->createTable('users')
			->columns([
				column('id')->type('int')->primaryKey()->autoIncrement(),
				column('other_name')->type('varchar(255)')->references(
					foreignKey('user_names')->columns(['first_name', 'last_name'])->deleteStrategy('set null')
				)->notNull()
			]);

		$this->assertSql('CREATE TABLE `users` (`id` int PRIMARY KEY AUTOINCREMENT, `other_name` varchar(255) NOT NULL REFERENCES `user_names` (`first_name`, `last_name`) ON DELETE SET NULL)', $create);
		$this->assertParams([], $create);
	}
}
