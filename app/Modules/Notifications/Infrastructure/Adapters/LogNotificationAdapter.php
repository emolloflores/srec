<?php
namespace App\Modules\Notifications\Infrastructure\Adapters;

use App\Modules\Notifications\Application\Ports\NotificationPort;
use Illuminate\Support\Facades\Log;

class LogNotificationAdapter implements NotificationPort
{
    public function send(string $toEmail, string $subject, string $message): void
    {
        // Para este proyecto, solo lo escribiremos en el log de Laravel.
        // En un futuro, aquí iría la lógica para usar Mailgun, SendGrid, etc.
        Log::info("--- NOTIFICACIÓN ---");
        Log::info("Para: {$toEmail}");
        Log::info("Asunto: {$subject}");
        Log::info("Mensaje: {$message}");
        Log::info("--------------------");
    }
}
