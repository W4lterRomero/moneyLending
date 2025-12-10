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

        $settings->fill($request->validated());
        $settings->save();

        return back()->with('success', 'Configuración guardada');
    }
}
