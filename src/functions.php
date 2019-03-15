<?php

declare(strict_types=1);

namespace Leeway;

function column(string $name): Partial\Column
{
	return new Partial\Column($name);
}

function foreignKey(string $name): Partial\ForeignKey
{
	return new Partial\ForeignKey($name);
}
