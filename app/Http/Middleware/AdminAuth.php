<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra nếu chưa đăng nhập thì bắt quay về trang login
        if (!session()->has('DangNhap')) {
            return redirect()->route('auth.login')->with('thatbai', 'Vui lòng đăng nhập để truy cập.');
        }

        // 2. Lấy Role từ session (đã lưu ở bước loginCheck)
        // Nếu là nhân viên hoặc có role là quan_ly thì cho đi tiếp
        $userRole = session('UserRole'); 
        $checkRole = session('CheckRole'); // Quyền của nhân viên

        if ($userRole === 'quan_ly' || $checkRole !== null) {
            return $next($request);
        }

        // 3. Nếu là Khách hàng mà đòi vào trang Admin -> Đẩy về trang chủ
        return redirect('/')->with('thatbai', 'Bạn không có quyền truy cập khu vực quản lý.');
    }
}