<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ClientDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'description',
        'verified',
        'verified_at',
        'verified_by',
        'expires_at',
        'uploaded_by',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'dui_front' => 'DUI Frente',
            'dui_back' => 'DUI Reverso',
            'selfie_with_id' => 'Selfie con DUI',
            'proof_of_income' => 'Comprobante de Ingresos',
            'utility_bill' => 'Recibo de Servicios',
            'bank_statement' => 'Estado de Cuenta',
            'employment_letter' => 'Carta de Trabajo',
            'tax_return' => 'Declaración de Renta',
            'contract_signed' => 'Contrato Firmado',
            'collateral_photo' => 'Foto de Garantía',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getIconAttribute(): string
    {
        return match (true) {
            str_starts_with($this->mime_type, 'image/') => '🖼️',
            str_starts_with($this->mime_type, 'application/pdf') => '📄',
            default => '📎',
        };
    }
}
