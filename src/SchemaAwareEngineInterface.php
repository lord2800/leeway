<?php

namespace Leeway;

use Latitude\QueryBuilder\EngineInterface;

interface SchemaAwareEngineInterface extends EngineInterface
{
    /**
     * Create a new CREATE TABLE query
     */
    public function makeCreateTable(): Query\CreateTableQuery;

    /**
     * Create a new DROP TABLE query
     */
    public function makeDropTable(): Query\DropTableQuery;

    /**
     * Create a new ALTER TABLE query
     */
    public function makeAlterTable(): Query\AlterTableQuery;
}
