<?php
declare(strict_types=1);

namespace Leeway\Query;

use Latitude\QueryBuilder\CriteriaInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use LogicException;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\listing;

class AlterTableQuery extends AbstractQuery
{
	/** @var StatementInterface */
	protected $table;

	/** @var StatementInterface */
	protected $newTable;

	/** @var StatementInterface */
	protected $addedColumn;

	/** @var StatementInterface */
	protected $droppedColumn;

	/** @var StatementInterface[] */
	protected $changedColumn;

	/** @var StatementInterface[] */
	protected $renamedColumn;

	public function table($table): self
	{
		$this->table = identify($table);
		return $this;
	}

	public function rename($newTable): self
	{
		$this->newTable = identify($newTable);
		return $this;
	}

	public function addColumn($column): self
	{
		$this->addedColumn = $column;
		return $this;
	}

	public function dropColumn($column): self
	{
		$this->droppedColumn = $column;
		return $this;
	}

	public function changeColumn($oldColumn, $newColumn): self
	{
		$this->changedColumn = [$oldColumn, $newColumn];
		return $this;
	}

	public function renameColumn($oldColumn, $newColumn): self
	{
		$this->renamedColumn = [$oldColumn, $newColumn];
		return $this;
	}

	public function asExpression(): ExpressionInterface
	{
		$query = $this->startExpression();
		$query = $this->applyTable($query);
		$query = $this->applyAddColumn($query);
		$query = $this->applyDropColumn($query);
		$query = $this->applyChangeColumn($query);
		$query = $this->applyRenameColumn($query);
		$query = $this->applyRename($query);

		return $query;
	}

	protected function startExpression(): ExpressionInterface
	{
		return express('ALTER TABLE');
	}

	protected function applyTable(ExpressionInterface $query): ExpressionInterface
	{
		return $this->table ? $query->append('%s', $this->table) : $query;
	}

	protected function applyAddColumn(ExpressionInterface $query): ExpressionInterface
	{
		return $this->addedColumn ? $query->append('ADD COLUMN %s', $this->addedColumn) : $query;
	}

	protected function applyDropColumn(ExpressionInterface $query): ExpressionInterface
	{
		return $this->droppedColumn ? $query->append('DROP COLUMN %s', $this->droppedColumn) : $query;
	}

	protected function applyChangeColumn(ExpressionInterface $query): ExpressionInterface
	{
		return $this->changedColumn ? $query->append('CHANGE COLUMN %s %s', $this->changedColumn[0], $this->changedColumn[1]) : $query;
	}
	protected function applyRenameColumn(ExpressionInterface $query): ExpressionInterface
	{
		return $this->renamedColumn ? $query->append('RENAME COLUMN %s %s', $this->renamedColumn[0], $this->renamedColumn[1]) : $query;
	}

	protected function applyRename(ExpressionInterface $query): ExpressionInterface
	{
		return $this->newTable ? $query->append('RENAME TO %s', $this->newTable) : $query;
	}
}
