<?php

namespace App\Http\Requests;

use App\Models\Keperluan;
use App\Models\Pegawai;
use App\Models\Tamu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ID_TAMU' => [
                'required',
                'string',
                'max:3',
                Rule::exists((new Tamu())->getTable(), 'ID_TAMU'),
            ],

            'TANGGAL_KUNJUNGAN' => ['required', 'date'],
            'JAM_MASUK' => ['nullable', 'date_format:H:i'],
            'JAM_KELUAR' => ['nullable', 'date_format:H:i'],

            'STATUS_KUNJUNGAN' => ['required', Rule::in(['Aktif', 'Selesai'])],

            'CATATAN' => ['nullable', 'string', 'max:100'],

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
}
