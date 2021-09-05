<?php

namespace App\Gmail;

use App\EmailAdapter;

class GmailAdapter implements EmailAdapter
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
            'g_sender' => '',
            'g_recipients' => [],
            'g_cc' => [],
            'g_bcc' => [],
            'g_subject' => '',
            'g_body' => '',
        ];

        return $details;
    }

    public function supports($platform): bool
    {
        return 'gmail' === strtolower($platform);
    }
}
