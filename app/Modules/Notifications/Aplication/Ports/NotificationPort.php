<?php
namespace App\Modules\Notifications\Application\Ports;

interface NotificationPort
{
    public function send(string $toEmail, string $subject, string $message): void;
}
