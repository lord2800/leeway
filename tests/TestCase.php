<?php

namespace Tests\Leeway;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;
use Leeway\QueryFactory;
use Leeway\Engine\MySqlEngine;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var EngineInterface */
    protected $engine;

    /** @var QueryFactory */
    protected $factory;

    public function setUp(): void
    {
        $this->engine = $this->getEngine();
        $this->factory = new QueryFactory($this->engine);
    }

    protected function getEngine(): EngineInterface
    {
        return new MySqlEngine();
    }

    public function assertSql(string $sql, StatementInterface $statement)
    {
        $this->assertSame($sql, $statement->sql($this->engine));
        if ($statement instanceof QueryInterface) {
            $this->assertSame($sql, $statement->compile()->sql());
        }
    }

    public function assertParams(array $params, StatementInterface $statement)
    {
        $this->assertSame($params, $statement->params($this->engine));
        if ($statement instanceof QueryInterface) {
            $this->assertSame($params, $statement->compile()->params());
        }
    }
}
