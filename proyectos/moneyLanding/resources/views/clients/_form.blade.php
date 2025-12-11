@csrf
<div class="space-y-6">
    {{-- SECCIÓN 1: Información Personal Básica --}}
    <div class="card">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">📋 Información Personal</h3>
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Completo *</label>
                <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}" required
                    class="input-apple @error('name') border-red-500 @enderror" />
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Número de DUI *</label>
                <input type="text" name="document_number" value="{{ old('document_number', $client->document_number ?? '') }}"
                    placeholder="12345678-9" pattern="[0-9]{8}-[0-9]" maxlength="10"
                    class="input-apple @error('document_number') border-red-500 @enderror" />
                <p class="text-xs text-slate-500 mt-1">Formato: 12345678-9</p>
                @error('document_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
                    class="input-apple @error('email') border-red-500 @enderror" />
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono Principal *</label>
                <input type="tel" name="phone" value="{{ old('phone', $client->phone ?? '') }}" required
                    placeholder="7890-1234"
                    class="input-apple @error('phone') border-red-500 @enderror" />
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono Alternativo</label>
                <input type="tel" name="second_phone" value="{{ old('second_phone', $client->second_phone ?? '') }}"
                    placeholder="7890-1234"
                    class="input-apple @error('second_phone') border-red-500 @enderror" />
                @error('second_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($client->birth_date ?? null)?->toDateString()) }}"
                    class="input-apple @error('birth_date') border-red-500 @enderror" />
                @error('birth_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Género</label>
                <select name="gender" class="input-apple @error('gender') border-red-500 @enderror">
                    <option value="">Seleccionar...</option>
                    @foreach(['male' => 'Masculino', 'female' => 'Femenino', 'other' => 'Otro', 'prefer_not_to_say' => 'Prefiero no decir'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('gender', $client->gender ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Estado Civil</label>
                <select name="marital_status" class="input-apple @error('marital_status') border-red-500 @enderror">
                    <option value="">Seleccionar...</option>
                    @foreach(['single' => 'Soltero/a', 'married' => 'Casado/a', 'divorced' => 'Divorciado/a', 'widowed' => 'Viudo/a', 'separated' => 'Separado/a'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('marital_status', $client->marital_status ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('marital_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Dependientes</label>
                <input type="number" name="dependents" value="{{ old('dependents', $client->dependents ?? 0) }}" min="0"
                    class="input-apple @error('dependents') border-red-500 @enderror" />
                @error('dependents') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nacionalidad</label>
                <input type="text" name="nationality" value="{{ old('nationality', $client->nationality ?? 'El Salvador') }}"
                    class="input-apple @error('nationality') border-red-500 @enderror" />
                @error('nationality') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Ocupación/Profesión</label>
                @php
                    $existingOccupations = \App\Models\Client::whereNotNull('occupation')
                        ->where('occupation', '!=', '')
                        ->distinct()
                        ->pluck('occupation')
                        ->sort();
                @endphp
                <input type="text" name="occupation" value="{{ old('occupation', $client->occupation ?? '') }}"
                    placeholder="Ej: Ingeniero, Comerciante, Docente"
                    list="occupations-list"
                    autocomplete="off"
                    class="input-apple @error('occupation') border-red-500 @enderror" />
                <datalist id="occupations-list">
                    @foreach($existingOccupations as $occ)
                        <option value="{{ $occ }}">
                    @endforeach
                </datalist>
                @error('occupation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Lugar de Nacimiento</label>
                <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $client->place_of_birth ?? '') }}"
                    class="input-apple @error('place_of_birth') border-red-500 @enderror" />
                @error('place_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Dirección Completa</label>
                <textarea name="address" rows="2" class="input-apple @error('address') border-red-500 @enderror">{{ old('address', $client->address ?? '') }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Ciudad/Municipio</label>
                <input type="text" name="city" value="{{ old('city', $client->city ?? '') }}"
                    class="input-apple @error('city') border-red-500 @enderror" />
                @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Departamento/País</label>
                <input type="text" name="country" value="{{ old('country', $client->country ?? 'El Salvador') }}"
                    class="input-apple @error('country') border-red-500 @enderror" />
                @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- SECCIÓN 2: Información Laboral --}}
    <div class="card">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">💼 Información Laboral</h3>
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Empleo</label>
                <select name="employment_type" class="input-apple @error('employment_type') border-red-500 @enderror">
                    <option value="">Seleccionar...</option>
                    @foreach(['permanent' => 'Permanente', 'temporary' => 'Temporal', 'freelance' => 'Freelance', 'self_employed' => 'Independiente', 'unemployed' => 'Desempleado'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('employment_type', $client->employment_type ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('employment_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la Empresa</label>
                <input type="text" name="company_name" value="{{ old('company_name', $client->company_name ?? '') }}"
                    class="input-apple @error('company_name') border-red-500 @enderror" />
                @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Cargo/Posición</label>
                <input type="text" name="job_title" value="{{ old('job_title', $client->job_title ?? '') }}"
                    placeholder="Ej: Gerente de Ventas, Operario"
                    class="input-apple @error('job_title') border-red-500 @enderror" />
                @error('job_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Salario Mensual (USD)</label>
                <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income', $client->monthly_income ?? '') }}"
                    placeholder="0.00"
                    class="input-apple @error('monthly_income') border-red-500 @enderror" />
                @error('monthly_income') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono de Trabajo</label>
                <input type="tel" name="work_phone" value="{{ old('work_phone', $client->work_phone ?? '') }}"
                    class="input-apple @error('work_phone') border-red-500 @enderror" />
                @error('work_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Inicio en Empleo</label>
                <input type="date" name="employment_start_date" value="{{ old('employment_start_date', optional($client->employment_start_date ?? null)?->toDateString()) }}"
                    class="input-apple @error('employment_start_date') border-red-500 @enderror" />
                @error('employment_start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre del Supervisor/Jefe</label>
                <input type="text" name="supervisor_name" value="{{ old('supervisor_name', $client->supervisor_name ?? '') }}"
                    class="input-apple @error('supervisor_name') border-red-500 @enderror" />
                @error('supervisor_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono del Supervisor</label>
                <input type="tel" name="supervisor_phone" value="{{ old('supervisor_phone', $client->supervisor_phone ?? '') }}"
                    class="input-apple @error('supervisor_phone') border-red-500 @enderror" />
                @error('supervisor_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Dirección de Trabajo</label>
                <textarea name="work_address" rows="2" class="input-apple @error('work_address') border-red-500 @enderror">{{ old('work_address', $client->work_address ?? '') }}</textarea>
                @error('work_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- SECCIÓN 3: Información Bancaria --}}
    <div class="card">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">🏦 Información Bancaria (Opcional)</h3>
        <div class="grid md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Banco</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $client->bank_name ?? '') }}"
                    placeholder="Ej: Banco Agrícola, BAC"
                    class="input-apple @error('bank_name') border-red-500 @enderror" />
                @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Número de Cuenta</label>
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $client->bank_account_number ?? '') }}"
                    class="input-apple @error('bank_account_number') border-red-500 @enderror" />
                @error('bank_account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Cuenta</label>
                <select name="bank_account_type" class="input-apple @error('bank_account_type') border-red-500 @enderror">
                    <option value="">Seleccionar...</option>
                    @foreach(['savings' => 'Ahorros', 'checking' => 'Corriente'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('bank_account_type', $client->bank_account_type ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('bank_account_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- SECCIÓN 4: Documentos --}}
    <div class="card">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">📎 Documentos</h3>
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">📷 Foto del Cliente</label>
                <input type="file" name="photo" accept="image/*" capture="user"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('photo') border-red-500 @enderror" />
                @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @if (!empty($client->photo_path))
                    <div class="mt-2 flex items-center gap-2">
                        <img src="{{ Storage::url($client->photo_path) }}" alt="Foto actual" class="w-16 h-16 rounded-lg object-cover border border-slate-200">
                        <p class="text-xs text-slate-500">Archivo actual</p>
                    </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">🪪 DUI Frente</label>
                <input type="file" name="dui_front" accept="image/*,.pdf"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('dui_front') border-red-500 @enderror" />
                @error('dui_front') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">🪪 DUI Reverso</label>
                <input type="file" name="dui_back" accept="image/*,.pdf"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('dui_back') border-red-500 @enderror" />
                @error('dui_back') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">🤳 Selfie con DUI</label>
                <input type="file" name="selfie_with_id" accept="image/*" capture="user"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('selfie_with_id') border-red-500 @enderror" />
                @error('selfie_with_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">💵 Comprobante de Ingresos</label>
                <input type="file" name="proof_of_income" accept="image/*,.pdf"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('proof_of_income') border-red-500 @enderror" />
                <p class="text-xs text-slate-500 mt-1">Recibo de sueldo, constancia de ingresos</p>
                @error('proof_of_income') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">🧾 Recibo de Servicios</label>
                <input type="file" name="utility_bill" accept="image/*,.pdf"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 @error('utility_bill') border-red-500 @enderror" />
                <p class="text-xs text-slate-500 mt-1">Agua, luz, telefonía (comprobante de domicilio)</p>
                @error('utility_bill') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- SECCIÓN 5: Estado y Notas --}}
    <div class="card">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">⚙️ Configuración</h3>
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Estado del Cliente</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 @error('status') border-red-500 @enderror">
                    @foreach (['lead' => 'Prospecto', 'active' => 'Activo', 'suspended' => 'Suspendido'] as $status => $label)
                        <option value="{{ $status }}" @selected(old('status', $client->status ?? 'active') === $status)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Etiquetas</label>
                <input type="text" name="tags" value="{{ old('tags', isset($client->tags) ? implode(', ', (array) $client->tags) : '') }}"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 @error('tags') border-red-500 @enderror"
                    placeholder="VIP, recurrente, alto_riesgo (separados por coma)" />
                @error('tags') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Notas Adicionales</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-sky-400 focus:ring focus:ring-sky-100 @error('notes') border-red-500 @enderror">{{ old('notes', $client->notes ?? '') }}</textarea>
                @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('clients.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">
            Cancelar
        </a>
        <button type="submit" class="px-4 py-2 bg-sky-500 text-white rounded-lg shadow hover:bg-sky-600">
            {{ isset($client) ? 'Actualizar Cliente' : 'Crear Cliente' }}
        </button>
    </div>
</div>
