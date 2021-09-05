<?php

namespace App;

interface EmailAdapter
{
    public function getEmails(int $offset = 0, int $limit = 10): array;

    public function getEmailDetail(string $id): array;

    public function supports($platform) : bool;
}
