<?php

namespace Modules\Email\Infrastructure\Persistence;

use Modules\Email\Domain\Models\EmailSetting;

class EmailRepository
{
    public function first()
    {
        return EmailSetting::query()
            ->first();
    }

    public function save(array $data): bool
    {
        $existing = $this->first();

        if ($existing) {

            $id = is_array($existing)
                ? $existing['id']
                : $existing->id;

            return (bool) EmailSetting::query()
                ->where('id', $id)
                ->update($data);
        }

        return (bool) EmailSetting::create($data);
    }

    public function getEmailSettings(): array
    {
        $row = $this->first();

        if (!$row) {

            return [
                'smtp_host' => '',
                'smtp_port' => 587,
                'encryption' => 'tls',
                'username' => '',
                'password' => '',
                'sender_email' => '',
                'sender_name' => ''
            ];
        }

        return [
            'smtp_host' =>
                is_array($row)
                    ? ($row['smtp_host'] ?? '')
                    : ($row->smtp_host ?? ''),

            'smtp_port' =>
                is_array($row)
                    ? ($row['smtp_port'] ?? 587)
                    : ($row->smtp_port ?? 587),

            'encryption' =>
                is_array($row)
                    ? ($row['encryption'] ?? 'tls')
                    : ($row->encryption ?? 'tls'),

            'username' =>
                is_array($row)
                    ? ($row['username'] ?? '')
                    : ($row->username ?? ''),

            'password' =>
                is_array($row)
                    ? ($row['password'] ?? '')
                    : ($row->password ?? ''),

            'sender_email' =>
                is_array($row)
                    ? ($row['sender_email'] ?? '')
                    : ($row->sender_email ?? ''),

            'sender_name' =>
                is_array($row)
                    ? ($row['sender_name'] ?? '')
                    : ($row->sender_name ?? '')
        ];
    }

    public function saveEmailSettings(array $data): bool
    {
        return $this->save([
            'smtp_host' => $data['smtp_host'] ?? '',
            'smtp_port' => $data['smtp_port'] ?? 587,
            'encryption' => $data['encryption'] ?? 'tls',
            'username' => $data['username'] ?? '',
            'password' => $data['password'] ?? '',
            'sender_email' => $data['sender_email'] ?? '',
            'sender_name' => $data['sender_name'] ?? ''
        ]);
    }
}