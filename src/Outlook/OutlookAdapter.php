<?php

namespace App\Outlook;

use App\EmailAdapter;

class OutlookAdapter implements EmailAdapter
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
            'o_sender' => '',
            'o_recipients' => [],
            'o_cc' => [],
            'o_bcc' => [],
            'o_subject' => '',
            'o_body' => '',
        ];

        return $details;
    }

    public function supports($platform): bool
    {
        return 'outlook' === strtolower($platform);
    }
}
