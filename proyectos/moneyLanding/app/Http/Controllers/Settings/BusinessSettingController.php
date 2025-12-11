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
        $settings = BusinessSetting::first();
        if (!$settings) {
            $settings = new BusinessSetting();
        }

        $data = $request->validated();

        // Convertir contract_templates de JSON string a array si es necesario
        if (isset($data['contract_templates']) && is_string($data['contract_templates'])) {
            $data['contract_templates'] = json_decode($data['contract_templates'], true) ?: [];
        }

        $settings->fill($data);
        $settings->save();

        return back()->with('success', 'Configuración guardada exitosamente');
    }
}
