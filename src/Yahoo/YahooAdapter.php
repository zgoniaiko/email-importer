<?php

namespace App\Yahoo;

use App\EmailAdapter;

class YahooAdapter implements EmailAdapter
{
    public function getEmails(int $offset = 0, int $limit = 10): array
    {
        // TODO: Implement getEmails() method.
        return [];
    }

    public function getEmailDetail(string $id): array
    {
        // TODO: Implement getEmailDetail() method.
        $details = [
            'y_sender' => '',
            'y_recipients' => [],
            'y_cc' => [],
            'y_bcc' => [],
            'y_subject' => '',
            'y_body' => '',
        ];

        return $details;
    }

    public function supports($platform): bool
    {
        return 'yahoo' === strtolower($platform);
    }
}
