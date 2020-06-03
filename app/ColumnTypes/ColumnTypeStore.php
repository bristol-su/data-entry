<?php

namespace BristolSU\Module\DataEntry\ColumnTypes;

class ColumnTypeStore
{

    protected $mapping = [];
    
    public function register($alias, $class): void
    {
        $this->mapping[$alias] = $class;
    }

    public function has($alias): bool
    {
        return array_key_exists($alias, $this->mapping);
    }
    
    public function find($alias): string
    {
        if($this->has($alias)) {
            return $this->mapping[$alias];
        }
        throw new \Exception(sprintf('Alias %s could not be found', $alias));
    }

    public function aliases(): array
    {
        return array_keys($this->mapping);
    }

    public static function add($alias, $class): void
    {
        app(static::class)->register($alias, $class);
    }
    
}