<?php

namespace App;

use App\Entity\Email;

interface EmailTransformer
{
    public function fromArray(array $data) : Email;

    public function supports($platform) : bool;
}
