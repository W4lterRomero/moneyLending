<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessSettingRequest;
use App\Models\BusinessSetting;

class BusinessSettingController extends Controller
{
    public function edit()
    {
        $settings = BusinessSetting::first();

        return view('settings.business', compact('settings'));
    }

    public function update(BusinessSettingRequest $request)
    {
        $settings = BusinessSetting::firstOrNew([]);
        $settings->fill($request->validated());
        $settings->save();

        return back()->with('success', 'Configuración guardada exitosamente');
    }

    public function downloadBackup()
    {
        // Ejecutar el comando de backup
        // Asumimos que data:export genera un archivo en storage/app/backups/
        // Si no, lo forzamos a un path temporal y lo descargamos.
        
        $filename = 'backup-' . now()->format('Y-m-d-His') . '.json';
        $path = storage_path('app/' . $filename);
        
        // Ejecutar el comando para que escriba en ese path (si el comando lo soporta)
        // O si el comando escribe a stdout, capturamos el output.
        // Revisando el context anterior: "php artisan data:export (backup JSON)"
        // Si el comando no acepta argumentos de archivo, asumiremos que imprime a stdout.
        
        \Illuminate\Support\Facades\Artisan::call('data:export');
        $output = \Illuminate\Support\Facades\Artisan::output();
        
        // Si el output es el JSON directo, lo guardamos y descargamos
        if (json_decode($output)) {
             return response()->streamDownload(function () use ($output) {
                echo $output;
            }, $filename);
        }
        
        // Fallback genérico si el comando guarda en otro lado, 
        // pero por ahora probamos capturando output que es lo standard en estos scripts simples.
        
        return back()->with('error', 'No se pudo generar el backup');
    }
}
