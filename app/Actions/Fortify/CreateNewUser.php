<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // Kiểm tra logic validation
        Validator::make($input, [
            'role' => ['required', 'string', 'in:quan_ly,khach_hang'], // Xác định vai trò
            
            // Tên nhà hàng và Địa chỉ chỉ bắt buộc nếu người đăng ký là "quan_ly"
            'Ten_nha_hang' => $input['role'] === 'quan_ly' ? ['required', 'string', 'max:255'] : ['nullable'], 
            'Dia_chi' => $input['role'] === 'quan_ly' ? ['required', 'string', 'max:255'] : ['nullable'], 
            
            'SDT' => ['required', 'string', 'max:12'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'Ten_dang_nhap' => ['required', 'string',  'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        // Tạo người dùng mới trong Database
        return User::create([
            'role' => $input['role'], 
            // Nếu là khách hàng thì lưu null cho các trường nhà hàng
            'Ten_nha_hang' => $input['role'] === 'quan_ly' ? $input['Ten_nha_hang'] : null, 
            'Dia_chi' => $input['role'] === 'quan_ly' ? $input['Dia_chi'] : null, 
            
            'SDT' => $input['SDT'], 
            'email' => $input['email'],
            'Ten_dang_nhap' => $input['Ten_dang_nhap'],
            'password' => Hash::make($input['password']),
        ]);
    }
}