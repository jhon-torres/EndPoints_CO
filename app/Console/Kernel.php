<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            echo "Hola ";
            $title = 'titulo de prueba';
            $body = 'cuerpo de prueba';
            $deviceToken = 'ExponentPushToken[-ptt0CI1CX1qumfq8mdOYv]'; // aqui token del dispositivo EXPO

            // Ejemplo de envío de notificación utilizando una solicitud HTTP
        $response = Http::post('https://exp.host/--/api/v2/push/send', [
            'title' => $title,
            'body' => $body,
            'to' => $deviceToken,
        ]);

        // Procesar la respuesta de la solicitud y manejar errores si es necesario
        if ($response->successful()) {
            echo 'Notificación enviada correctamente.';
            // return response()->json(['message' => 'Notificación enviada correctamente.']);
        } else {
            echo 'Error al enviar la notificación.';
            // return response()->json(['message' => 'Error al enviar la notificación.'], 500);
        }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
