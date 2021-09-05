<?php

namespace App;

class EmailBodyParser
{
    public function parse(string $data, string $delimiter) : string
    {
        $pos = strpos($data, $delimiter);

        if (false !== $pos) {
            $data = substr($data, 0, $pos);
        }

        return $data;
    }
}
