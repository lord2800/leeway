<?php
declare(strict_types=1);

namespace Leeway\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;
use function Latitude\QueryBuilder\identify;

class Column implements StatementInterface
{
	/** @var StatementInterface */
	protected $name;

	/** @var string */
	protected $defaultValue;

	/** @var string */
	protected $type;

	/** @var bool */
	protected $primaryKey;

	/** @var bool */
	protected $autoIncrement;

	/** @var bool */
	protected $unique;

	/** @var bool */
	protected $notNull;

	/** @var ForeignKey */
	protected $references;

	public function __construct(string $name)
	{
		$this->name = identify($name);
	}

	public function type(string $type): self
	{
		$this->type = $type;
		return $this;
	}

	public function defaultValue(string $defaultValue): self
	{
		$this->defaultValue = $defaultValue;
		return $this;
	}

	public function primaryKey(bool $state = true): self
	{
		$this->primaryKey = $state;
		return $this;
	}

	public function autoIncrement(bool $state = true): self
	{
		$this->autoIncrement = $state;
		return $this;
	}

	public function notNull(bool $state = true): self
	{
		$this->notNull = $state;
		return $this;
	}

	public function unique(bool $state = true): self
	{
		$this->unique = $state;
		return $this;
	}

	public function references(ForeignKey $identifier): self
	{
		$this->references = $identifier;
		return $this;
	}

	public function sql(EngineInterface $engine): string
	{
		$format = '%s';

		$args = [$this->name->sql($engine)];

		if ($this->type) {
			$format .= ' %s';
			$args[] = $this->type;
		}

		if ($this->primaryKey) {
			$format .= ' PRIMARY KEY';
			if ($this->autoIncrement) {
				$format .= ' AUTOINCREMENT';
			}
		} else if ($this->unique) {
			$format .= ' UNIQUE';
		}

		if ($this->notNull) {
			$format .= ' NOT NULL';
		}

		if ($this->defaultValue) {
			$format .= ' DEFAULT %s';
			$args[] = $this->defaultValue;
		}

		if ($this->references) {
			$format .= ' %s';
			$args[] = $this->references->sql($engine);
		}

		return vsprintf($format, $args);
	}

    public function params(EngineInterface $engine): array
    {
        return [];
    }
}
