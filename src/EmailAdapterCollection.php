<?php

namespace App;

class EmailAdapterCollection
{
    private $adapters;

    public function __construct(iterable $adapters)
    {
        $this->adapters = $adapters;
    }

    public function getAdapter($platform)
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->supports($platform)) {
                return $adapter;
            }
        }
    }
}
