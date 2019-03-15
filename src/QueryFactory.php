<?php

declare(strict_types=1);

namespace Leeway;

use Latitude\QueryBuilder\QueryFactory as LatitudeQueryFactory;
use Latitude\QueryBuilder\StatementInterface;
use Leeway\Engine\MySqlEngine;

class QueryFactory extends LatitudeQueryFactory
{
	/** @var SchemaAwareEngineInterface */
	private $schemaEngine;

	public function __construct(SchemaAwareEngineInterface $engine = null)
	{
		$this->schemaEngine = $engine ?: new MySqlEngine();
		parent::__construct($this->schemaEngine);
	}

	/**
	 * Create a new CREATE TABLE query.
	 *
	 * @param string|StatementInterface $table
	 */
	public function createTable($table): Query\CreateTableQuery
	{
		return $this->schemaEngine->makeCreateTable()->table($table);
	}

	/**
	 * Create a new DROP TABLE query.
	 *
	 * @param string|StatementInterface $table
	 */
	public function dropTable($table): Query\DropTableQuery
	{
		return $this->schemaEngine->makeDropTable()->table($table);
	}

	/**
	 * Create a new ALTER TABLE query.
	 *
	 * @param string|StatementInterface $table
	 */
	public function alterTable($table): Query\AlterTableQuery
	{
		return $this->schemaEngine->makeAlterTable()->table($table);
	}
}
