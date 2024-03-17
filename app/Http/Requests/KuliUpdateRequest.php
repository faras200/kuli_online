<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KuliUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|max:100|unique:users,name,' . $this->kuli->id,
            'identity_card_number'  => 'nullable|max:100|unique:users,identity_card_number,' . $this->kuli->id,
            'gender'                => 'nullable',
            'email'                 => 'email|unique:users,email,' . $this->kuli->id,
            'phone'                 => 'nullable|numeric|unique:users,phone,' . $this->kuli->id,
            'address'               => 'nullable',
            'npwp'                  => 'nullable',
            'rekening'              => 'nullable',
            'province_id'           => 'nullable',
            'city_id'               => 'nullable',
            'district_id'           => 'nullable',
            'village_id'            => 'nullable',
            'rt'                    => 'nullable',
            'rw'                    => 'nullable',
            'profile_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Batasan file gambar
            'password'              => 'nullable|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required'                 => 'Nama harus diisi.',
            'identity_card_number.required' => 'NIK harus diisi.',
            'identity_card_number.unique'   => 'NIK sudah dipakai, silahkan ganti dengan yang lain.',
            'email.email'                   => 'Format email salah.',
            'email.unique'                  => 'Email sudah dipakai, silahkan ganti dengan yang lain.',
            'phone.numeric'                 => 'No. HP diisi dengan angka saja.',
            'password.min'                  => 'Password minimal 8 huruf.',
            'profile_image.max'             => 'Gambar tidak boleh > 2Mb.',
            'profile_image.image'           => 'File yang diupload hanya boleh berupa Gambar.',
            'profile_image.mimes'           => 'Jenis file yang boleh diupload hanya JPEG, JPG, dan PNG.',
        ];
    }
}
