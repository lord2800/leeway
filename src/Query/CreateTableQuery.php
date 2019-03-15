<?php
declare(strict_types=1);

namespace Leeway\Query;

use Latitude\QueryBuilder\CriteriaInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use Latitude\QueryBuilder\Query\SelectQuery;
use LogicException;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\listing;

class CreateTableQuery extends AbstractQuery
{
	/** @var StatementInterface */
	protected $table;

	/** @var StatementInterface[] */
	protected $columns = [];

	/** @var bool */
	protected $temp = false;

	/** @var bool */
	protected $ifNotExists = false;

	/** @var SelectQuery|null */
	protected $select = null;

	public function table($table): self
	{
		$this->table = identify($table);
		return $this;
	}

	public function temporary(bool $state = true): self
	{
		$this->temp = $state;
		return $this;
	}

	public function ifNotExists(bool $state = true): self
	{
		$this->ifNotExists = $state;
		return $this;
	}

	public function asSelect(SelectQuery $query): self
	{
		$this->select = $query;
		return $this;
	}

	public function columns($columns): self
	{
		$this->columns = $columns;
		return $this;
	}

	public function asExpression(): ExpressionInterface
	{
		$query = $this->startExpression();
		$query = $this->applyIfNotExists($query);
		$query = $this->applyTable($query);
		$query = $this->applySelect($query);
		$query = $this->applyColumns($query);

		return $query;
	}

	protected function startExpression(): ExpressionInterface
	{
		return express('CREATE ' . ($this->temp ? 'TEMPORARY ' : '') . 'TABLE');
	}

	protected function applyTable(ExpressionInterface $query): ExpressionInterface
	{
		return $this->table ? $query->append('%s', $this->table) : $query;
	}

	protected function applyIfNotExists(ExpressionInterface $query): ExpressionInterface
	{
		return $this->ifNotExists ? $query->append('IF NOT EXISTS') : $query;
	}

	protected function applyColumns(ExpressionInterface $query): ExpressionInterface
	{
		return $this->columns ? $query->append('(%s)', listing($this->columns)) : $query;
	}

	protected function applySelect(ExpressionInterface $query): ExpressionInterface
	{
		return $this->select ?  $query->append('AS %s', $this->select) : $query;
	}
}
