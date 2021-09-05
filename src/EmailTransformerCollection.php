<?php

namespace App;

class EmailTransformerCollection
{
    private $transformers;

    public function __construct(iterable $transformers)
    {
        $this->transformers = $transformers;
    }

    public function fromArray($platform, $data)
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($platform)) {
                return $transformer->fromArray($data);
            }
        }

    }
}
