<?php

namespace App\Http\Requests;

use App\Models\Keperluan;
use App\Models\Pegawai;
use App\Models\Tamu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Mode input tamu
            'TAMU_MODE' => ['required', Rule::in(['existing', 'new'])],

            // Existing tamu
            'ID_TAMU' => [
                'nullable',
                'string',
                'max:3',
                Rule::requiredIf(fn () => $this->input('TAMU_MODE') === 'existing'),
                Rule::exists((new Tamu())->getTable(), 'ID_TAMU'),
            ],

            // Tamu baru
            'NAMA_TAMU' => [
                'nullable',
                'string',
                'max:50',
                Rule::requiredIf(fn () => $this->input('TAMU_MODE') === 'new'),
            ],
            'INSTANSI' => [
                'nullable',
                'string',
                'max:60',
                Rule::requiredIf(fn () => $this->input('TAMU_MODE') === 'new'),
            ],
            'NO_HP' => [
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf(fn () => $this->input('TAMU_MODE') === 'new'),
            ],
            'EMAIL' => [
                'nullable',
                'email',
                'max:100',
                Rule::requiredIf(fn () => $this->input('TAMU_MODE') === 'new'),
            ],
            'NO_KTP' => [
                'nullable',
                'string',
                'max:16',
                Rule::requiredIf(fn () => $this->input('TAMU_MODE') === 'new'),
            ],

            // Data kunjungan (ID_KUNJUNGAN & STATUS tidak boleh diinput user)
            'TANGGAL_KUNJUNGAN' => ['required', 'date'],
            'JAM_MASUK' => ['nullable', 'date_format:H:i'],
            'CATATAN' => ['nullable', 'string', 'max:100'],

            // Pivot
            'ID_KEPERLUAN' => ['required', 'array', 'min:1'],
            'ID_KEPERLUAN.*' => [
                'required',
                'string',
                'max:5',
                Rule::exists((new Keperluan())->getTable(), 'ID_KEPERLUAN'),
            ],

            'NIK' => ['required', 'array', 'min:1'],
            'NIK.*' => [
                'required',
                'string',
                'max:16',
                Rule::exists((new Pegawai())->getTable(), 'NIK'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'TAMU_MODE.required' => 'Pilih mode tamu (existing / baru).',
            'ID_KEPERLUAN.required' => 'Pilih minimal 1 keperluan.',
            'NIK.required' => 'Pilih minimal 1 pegawai yang ditemui.',
        ];
    }
}
