<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InputProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tanggal_penerimaan' => 'required|date',
            'jns_sam' => 'required',
            'asal_sam' => 'required|in:Internal,Eksternal', // Only allow 'Internal' or 'Eksternal'
            'no_kupa' => 'required|numeric',
            'no_lab' => 'required|string', // Changed from 'required'
            'nama_pelanggan' => 'required|string', // Changed from 'required'
            'departemen' => 'required|string', // Changed from 'required'
            'kode_sampel' => 'required|string', // Changed from 'required'
            'no_surat' => 'required|string', // Changed from 'required'
            'estimasi' => 'required|date',
            'tujuan' => 'required|string',
            'parameter_analisis' => 'required',
            'email' => 'required',
            'file-upload' => 'max:7000',
        ];
    }

    public function messages()
    {
        return [

            'tanggal_penerimaan.required' => ' Tanggal Penerimaan  Tidak Boleh Kosong.',
            'jns_sam.required' => ' Jenis Sampel  Tidak Boleh Kosong.',
            'asal_sam.required' => ' Asal Sampel  Tidak Boleh Kosong.',
            'no_kupa.required' => 'Nomor Kupa Tidak Boleh Kosong.',
            'no_lab.required' => ' Nomor Lab  Tidak Boleh Kosong.',
            'nama_pelanggan.required' => ' Nama Pelanggan  Tidak Boleh Kosong.',
            'kode_sampel.required' => 'Kode Sampel Tidak Boleh Kosong.',
            'no_surat.required' => ' Nomor Surat  Tidak Boleh Kosong.',
            'estimasi.required' => 'Estimasi Kupa  Tidak Boleh Kosong.',
            'tujuan.required' => 'Tujuan Tidak Boleh Kosong.',
            'parameter_analisis.required' => 'Parameter Analisis Tidak Boleh Kosong.',
            'email.required' => 'Email Tidak Boleh Kosong',
            // Add custom messages for other rules as needed
        ];
    }
}
