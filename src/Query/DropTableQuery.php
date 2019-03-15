<?php
declare(strict_types=1);

namespace Leeway\Query;

use Latitude\QueryBuilder\CriteriaInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;

class DropTableQuery extends AbstractQuery
{
	/** @var StatementInterface */
	protected $table;

	/** @var bool */
	protected $ifExists = false;

	public function table($table): self
	{
		$this->table = identify($table);
		return $this;
	}

	public function ifExists(bool $state = true): self
	{
		$this->ifExists = $state;
		return $this;
	}

	public function asExpression(): ExpressionInterface
	{
		$query = $this->startExpression();
		$query = $this->applyIfExists($query);
		$query = $this->applyTable($query);

		return $query;
	}

	protected function startExpression(): ExpressionInterface
	{
		return express('DROP TABLE');
	}

	protected function applyIfExists(ExpressionInterface $query): ExpressionInterface
	{
		return $this->ifExists ? $query->append('IF EXISTS') : $query;
	}

	protected function applyTable(ExpressionInterface $query): ExpressionInterface
	{
		return $this->table ? $query->append('%s', $this->table) : $query;
	}
}
