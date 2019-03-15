<?php

declare(strict_types=1);

namespace Leeway\Engine;

use Latitude\QueryBuilder\Engine\MySqlEngine as LatitudeMySqlEngine;
use Leeway\SchemaAwareEngineInterface;
use Leeway\Query\CreateTableQuery;
use Leeway\Query\AlterTableQuery;
use Leeway\Query\DropTableQuery;

class MySqlEngine extends LatitudeMySqlEngine implements SchemaAwareEngineInterface
{
	public function makeCreateTable(): CreateTableQuery
	{
		return new CreateTableQuery($this);
	}

	public function makeDropTable(): DropTableQuery
	{
		return new DropTableQuery($this);
	}

	public function makeAlterTable(): AlterTableQuery
	{
		return new AlterTableQuery($this);
	}
}
