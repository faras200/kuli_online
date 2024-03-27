<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                  => 'required|max:100',
            'identity_card_number'  => 'nullable|max:16|unique:users',
            'email'                 => 'email',
            // 'gender'                => 'required',
            // 'phone'                 => 'required|numeric',
            'wilayah_id'            => 'nullable|numeric',
            'rekening'              => 'nullable|numeric',
            'npwp'                  => 'nullable|numeric',
            'address'               => 'nullable',
            'province_id'           => 'nullable',
            'city_id'               => 'nullable',
            'district_id'           => 'nullable',
            'village_id'            => 'nullable',
            'rt'                    => 'nullable|numeric',
            'rw'                    => 'nullable|numeric',
            'postcode'              => 'nullable|numeric',
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
            // 'gender.required'               => 'Jenis Kelamin harus diisi.',
            // 'email.email'                   => 'Format email salah.',
            // 'email.unique'                  => 'Email sudah dipakai, silahkan ganti dengan yang lain.',
            'phone.required'                => 'No. HP harus diisi.',
            'wilayah_id.numeric'            => 'Pilih Nama Wilayahnya.',
            'phone.numeric'                 => 'No. HP diisi dengan angka saja.',
            'address.required'              => 'Alamat harus diisi.',
            'rt.numeric'                    => 'RT diisi dengan angka saja.',
            'rw.numeric'                    => 'RW diisi dengan angka saja.',
            'postcode.numeric'              => 'Kode Pos diisi dengan angka saja.',
            'password.required'             => 'Password harus diisi.',
            'password.min'                  => 'Password minimal 8 huruf.',
            'profile_image.max'             => 'Gambar tidak boleh > 2Mb.',
            'profile_image.image'           => 'File yang diupload hanya boleh berupa Gambar.',
            'profile_image.mimes'           => 'Jenis file yang boleh diupload hanya JPEG, JPG, dan PNG.',
        ];
    }
}
