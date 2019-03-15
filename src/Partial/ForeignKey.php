<?php
declare(strict_types=1);

namespace Leeway\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;

class ForeignKey implements StatementInterface
{
	/** @var StatementInterface */
	protected $table;

	/** @var StatementInterface[] */
	protected $columns;

	/** @var string */
	protected $updateStrategy;

	/** @var string */
	protected $deleteStrategy;

	public function __construct(string $table, array $columns = [])
	{
		$this->table = identify($table);
		$this->columns = identifyAll($columns);
	}

	public function sql(EngineInterface $engine): string
	{
		$format = 'REFERENCES %s';
		$args = [$this->table->sql($engine)];

		if ($this->columns) {
			$format .= ' (%s)';
			$args[] = $engine->flattenSql(', ', ...$this->columns);
		}

		if ($this->deleteStrategy) {
			$format .= ' ON DELETE %s';
			$args[] = $this->determineStrategy($this->deleteStrategy);
		}

		if ($this->updateStrategy) {
			$format .= ' ON UPDATE %s';
			$args[] = $this->determineStrategy($this->updateStrategy);
		}

		return vsprintf($format, $args);
	}

	public function params(EngineInterface $engine): array
	{
		return [];
	}

	protected function determineStrategy(string $strategy): string
	{
		switch ($strategy) {
			case 'set null':
			case 'set default':
			case 'cascade':
			case 'restrict':
			case 'no action':
				return strtoupper($strategy);
			default:
				throw new \LogicException('Invalid strategy "' . $strategy . '"');
		}
	}

	public function columns(array $columns): self
	{
		$this->columns = identifyAll($columns);
		return $this;
	}

	public function deleteStrategy(string $strategy): self
	{
		$this->deleteStrategy = $strategy;
		return $this;
	}

	public function updateStrategy(string $strategy): self
	{
		$this->updateStrategy = $strategy;
		return $this;
	}
}
