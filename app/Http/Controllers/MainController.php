<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MonAn;
use App\Models\NguyenLieu;
use App\Models\Ban;
use App\Models\DatMon;
use App\Models\LichLamViec;
use App\Models\NhanVien;
use App\Models\ChucVu;
use App\Models\DoanhThu;
use App\Models\DanhGia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class MainController extends Controller
{
    public function index(){
        return view('index')->with('route', 'TrangChu');
    }

    public function aboutUs(){
        return view('index')->with('route', 'GioiThieu');
    }

    public function nhahang(){
        $users = DB::table('users')->where('role', 'quan_ly')->get();
        return view('index')->with('route', 'NhaHang')->with('users', $users);
    }

    public function idnhahang($slug){
        $user = User::find($slug);
        if(!$user) return redirect('/')->with('thatbai', 'Không tìm thấy nhà hàng');

        $users = User::all();
        $bans = Ban::where('ID_nha_hang', $slug)->get();
        $danhgias = DB::table('danh_gia')->where('ID_nha_hang', $slug)->paginate(3);

        $monan = MonAn::where('ID_nha_hang', $slug)->count();
        $so_luong_ban = $bans->count();
        $nhanvien = NhanVien::where('ID_nha_hang', $slug)->count();

        $soluongdanhgia = DB::table('danh_gia')->where('ID_nha_hang', $slug)->count();
        session()->put('soluongdanhgia', $soluongdanhgia);

        $trungbinhdanhgia = DB::table('danh_gia')->where('ID_nha_hang', $slug)->avg('danh_gia');
        session()->put('trungbinhdanhgia', $trungbinhdanhgia);

        return view('index')->with('route', 'id-nha-hang')
            ->with(compact('users', 'user', 'monan', 'so_luong_ban', 'bans', 'nhanvien', 'danhgias'))
            ->with('alert', '0');
    }

    public function datban($id, Request $request){
        $ban = Ban::where('ID_nha_hang', $request->ID_nha_hang)
                  ->where('ten_ban', $request->ban)
                  ->first();

        if($ban){
            $ban->dat_truoc = $request->time.' ngày '.$request->ngay;
            $ban->datban_ten = session('TenDangNhap') ?? $request->ten;
            $ban->datban_so_nguoi = $request->so_nguoi;
            $ban->datban_ngay = $request->ngay;
            $ban->datban_time = $request->time;
            $ban->save();
        }

        return redirect('/NhaHang/nha-hang='.$request->ID_nha_hang)->with('alert', '1');
    }

    public function timkiem(Request $request){
        $query = $request->input('tim_kiem');
        $users = DB::table('users')
            ->where('role', 'quan_ly')
            ->where(function($q) use ($query) {
                $q->where('Ten_nha_hang', 'like', '%'.$query.'%')
                  ->orWhere('Dia_chi', 'like', '%'.$query.'%');
            })->get();

        if($users->isEmpty()) $users = DB::table('users')->where('role', 'quan_ly')->get();
        return view('index')->with('route', 'NhaHang')->with('users', $users);
    }

    public function login(){ return view('auth.login'); }
    public function register(){ return view('auth.register'); }

    public function storeReg(Request $request){
        $request->validate([
            'role' => 'required|in:quan_ly,khach_hang',
            'Ten_nha_hang' => 'required_if:role,quan_ly|nullable|unique:users,Ten_nha_hang',
            'SDT' => 'required|digits:10|unique:users',
            'email' => 'required|email|unique:users',
            'Ten_dang_nhap' => 'required|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        User::create([
            'role' => $request->role,
            'Ten_nha_hang' => $request->role === 'quan_ly' ? $request->Ten_nha_hang : null,
            'Dia_chi' => $request->role === 'quan_ly' ? $request->Dia_chi : null,
            'SDT' => $request->SDT,
            'email' => $request->email,
            'Ten_dang_nhap' => $request->Ten_dang_nhap,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('auth.login')->with('thanhcong', 'Đăng ký thành công!');
    }

    public function loginCheck(Request $request){
        $request->validate([
            'ten_dang_nhap' => 'required',
            'password' => 'required|min:5',
        ]);

        $userEmail = User::where('email', $request->ten_dang_nhap)->first();
        $userName = User::where('Ten_dang_nhap', $request->ten_dang_nhap)->first();
        $staff = NhanVien::where('tai_khoan', $request->ten_dang_nhap)->first(); 

        if ($staff) {
            if($request->password === $staff->mat_khau){
                $role = ChucVu::where('ID_chuc_vu', $staff->chuc_vu_id)->first(); 
                $request->session()->put([
                    'DangNhap' => $staff->ID_nha_hang,
                    'CheckRole' => $role->quyen,
                    'UserRole' => 'nhan_vien',
                    'TenDangNhap' => $staff->tai_khoan,
                    'NhanVien' => $staff,
                ]);
                return redirect('/User/trangchu');
            }
            return back()->with('thatbai','* Mật khẩu nhân viên sai');
        }

        $user = $userEmail ?? $userName;
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put([
                    'DangNhap' => $user->id,
                    'UserRole' => $user->role,
                    'TenDangNhap' => $user->Ten_dang_nhap
                ]);

                if ($user->role === 'quan_ly') {
                    return redirect('/User/trangchu');
                } else {
                    return redirect('/')->with('thanhcong', 'Chào mừng '.$user->Ten_dang_nhap);
                }
            }
        }
        return back()->with('thatbai', '* Tài khoản hoặc mật khẩu không đúng');
    }

    public function dieuhuong($slug){
        $id_nh = session('DangNhap');
        if(!$id_nh) return redirect('/auth/login');
        $data = User::find($id_nh);
        $monans = MonAn::where('ID_nha_hang', $id_nh)->get();
        $nguyenlieus = NguyenLieu::where('ID_nha_hang', $id_nh)->get();
        $bans = Ban::where('ID_nha_hang', $id_nh)->get();
        $datmons = DatMon::where('ID_nha_hang', $id_nh)->get();
        $chucvus = ChucVu::where('ID_nha_hang', $id_nh)->get();
        $nhanviens = NhanVien::where('ID_nha_hang', $id_nh)->get();

        $tong_tien = ['tong_tien' => 0, 'ten_ban_thanh_toan' => "Chưa chọn bàn"];
        $tong_doanh_thu = ['so_don_hang' => 0, 'tong_doanh_thu' => 0, 'tong_loi_nhuan' => 0, 'loi_nhuan' => 0];

        return view("admin.{$slug}.{$slug}")
            ->with(compact('data', 'monans', 'nguyenlieus', 'bans', 'datmons', 'tong_tien', 'chucvus', 'nhanviens', 'tong_doanh_thu'))
            ->with(['doanhthus' => [], 'bat_dau' => '', 'ket_thuc' => '']);
    }

    public function dieuhuong2($slug, $slug2){
        $id_nh = session('DangNhap');
        $data = User::find($id_nh);
        $monans = MonAn::where('ID_nha_hang', $id_nh)->get();
        $nguyenlieus = NguyenLieu::where('ID_nha_hang', $id_nh)->get();
        $bans = Ban::where('ID_nha_hang', $id_nh)->get();
        $datmons = DatMon::where('ID_nha_hang', $id_nh)->get();
        $chucvus = ChucVu::where('ID_nha_hang', $id_nh)->get();
        $nhanviens = NhanVien::where('ID_nha_hang', $id_nh)->get();

        $tong_tien = ['tong_tien' => 0, 'ten_ban_thanh_toan' => "Chưa chọn bàn"];
        $tong_doanh_thu = ['so_don_hang' => 0, 'tong_doanh_thu' => 0, 'tong_loi_nhuan' => 0, 'loi_nhuan' => 0];

        return view("admin.{$slug}.{$slug2}")
            ->with(compact('data', 'monans', 'nguyenlieus', 'bans', 'datmons', 'tong_tien', 'chucvus', 'nhanviens', 'tong_doanh_thu'))
            ->with(['doanhthus' => [], 'bat_dau' => '', 'ket_thuc' => '']);
    }

    public function dangXuat(){
        session()->flush();
        return redirect('/');
    }

    // TRANG CÁ NHÂN KHÁCH HÀNG
    public function khachHangIndex() {
        if (!session()->has('DangNhap')) return redirect()->route('auth.login');
        
        $data = User::find(session('DangNhap'));
        if(!$data || $data->role === 'quan_ly') return redirect('/User/trangchu');

        // Lọc các bàn có datban_ten trùng với khách đang login
        $bookings = Ban::where('datban_ten', $data->Ten_dang_nhap)->get();
        $totalBookings = $bookings->count();

        return view('khachhang.index', compact('data', 'bookings', 'totalBookings'));
    }

    // CẬP NHẬT THÔNG TIN VÀ ĐỔI MẬT KHẨU
    public function khachHangUpdate(Request $request) {
        $request->validate([
            'SDT' => 'required|digits:10',
            'email' => 'required|email',
            'new_password' => 'nullable|min:5|confirmed',
        ]);

        $user = User::find(session('DangNhap'));
        $user->SDT = $request->SDT;
        $user->email = $request->email;
        
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        return back()->with('thanhcong', 'Cập nhật thành công!');
    }

    // HÀM HỦY ĐẶT BÀN (MỚI)
    public function khachHangHuyBan($id) {
        $user = User::find(session('DangNhap'));
        // Tìm đúng bàn mà khách này đang giữ
        $ban = Ban::where('ID_ban', $id)->where('datban_ten', $user->Ten_dang_nhap)->first();

        if ($ban) {
            // Xóa sạch thông tin đặt bàn để trả về bàn trống
            $ban->dat_truoc = null;
            $ban->datban_ten = null;
            $ban->datban_so_nguoi = null;
            $ban->datban_ngay = null;
            $ban->datban_time = null;
            $ban->save();
            
            return back()->with('thanhcong', 'Đã hủy đặt bàn thành công!');
        }

        return back()->with('thatbai', 'Không tìm thấy thông tin đặt bàn.');
    }

    public function edit($id){ return view('admin.trangchu.sua', ['data'=>User::find($id)]); }

    public function update(Request $request){
        $data = User::find($request->id);
        $data->fill($request->only(['Ten_nha_hang', 'Dia_chi', 'SDT', 'email', 'Ten_dang_nhap']));
        if($request->password) $data->password = Hash::make($request->password);
        $data->save();
        return redirect('/User/trangchu');
    }

    public function destroy($id){
        User::find($id)->delete();
        session()->flush();
        return redirect('/');
    }
}