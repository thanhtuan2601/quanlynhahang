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
    // ... các phương thức index, aboutUs, nhahang, idnhahang, datban, timkiem giữ nguyên ...

    public function index(){
        return view('index')->with('route', 'TrangChu');
    }

    public function aboutUs(){
        return view('index')->with('route', 'GioiThieu');
    }

    public function nhahang(){
        $users = DB::table('users')->get();

        return view('index')->with('route', 'NhaHang')
            ->with('users', $users)
        ;
    }

    public function idnhahang($slug){
        $users = User::all();
        $bans = Ban::all();
        $user = User::find($slug);
        $danhgias = DB::table('danh_gia')->where('ID_nha_hang', $slug)->paginate(3);

        $monan = DB::table('mon_an')->where('ID_nha_hang', $slug)->count();
        $so_luong_ban = DB::table('ban')->where('ID_nha_hang', $slug)->count();
        $nhanvien = DB::table('nhan_vien')->where('ID_nha_hang', $slug)->count();

        $soluongdanhgia = DB::table('danh_gia')->where('ID_nha_hang', $slug)->count();
        session()->put('soluongdanhgia', $soluongdanhgia);

        $trungbinhdanhgia = DB::table('danh_gia')->where('ID_nha_hang', $slug)->avg('danh_gia');
        session()->put('trungbinhdanhgia', $trungbinhdanhgia);

        return view('index')->with('route', 'id-nha-hang')
            ->with('users', $users)
            ->with('user', $user)
            ->with('monan', $monan)
            ->with('so_luong_ban', $so_luong_ban)
            ->with('bans', $bans)
            ->with('nhanvien', $nhanvien)
            ->with('danhgias', $danhgias)
            ->with('alert', '0')
        ;
    }

    public function datban($id, Request $request){
        $bans = Ban::all();

        foreach($bans as $bann){
            if(($bann['ten_ban'] == $request->ban) && ($bann['ID_nha_hang'] == $request->ID_nha_hang)){
                $ban = Ban::find($bann['ID_ban']);
                $ban['dat_truoc'] = $request->time.' ngày '.$request->ngay;
                $ban['datban_ten'] = $request->ten;
                $ban['datban_so_nguoi'] = $request->so_nguoi;
                $ban['datban_ngay'] = $request->ngay;
                $ban['datban_time'] = $request->time;

                $ban->save();
            }
        }

        $users = User::all();
        $bans = Ban::all();
        $user = User::find($request->ID_nha_hang);

        $monan = DB::table('mon_an')->where('ID_nha_hang', $request->ID_nha_hang)->count();
        $so_luong_ban = DB::table('ban')->where('ID_nha_hang', $request->ID_nha_hang)->count();
        $nhanvien = DB::table('nhan_vien')->where('ID_nha_hang', $request->ID_nha_hang)->count();

        return view('index')->with('route', 'id-nha-hang')
            ->with('users', $users)
            ->with('user', $user)
            ->with('monan', $monan)
            ->with('so_luong_ban', $so_luong_ban)
            ->with('bans', $bans)
            ->with('nhanvien', $nhanvien)
            ->with('alert', '1')
        ;
    }

    public function timkiem(Request $request){
        $users = DB::table('users')
        ->where('Ten_nha_hang', 'like', '%'.$request->input('tim_kiem').'%')
        ->orWhere('Dia_chi', 'like', '%'.$request->input('tim_kiem').'%')
        ->orWhere('SDT', $request->input('tim_kiem'))
        ->orWhere('email', $request->input('tim_kiem'))
        ->orWhere('Ten_dang_nhap', $request->input('tim_kiem'))
        ->get();

        if(!$users){
            $users = DB::table('users')->get();
        }

        return view('index')->with('route', 'NhaHang')
            ->with('users', $users)
        ;
    }

    public function login(){
        return view('auth.login');
    }

    public function register(){
        return view('auth.register');
    }

    // PHẦN CẬP NHẬT: Xử lý Đăng ký
    public function storeReg(Request $request){
        $request->validate([
            'role' => 'required|in:quan_ly,khach_hang',
            // Chỉ yêu cầu tên nhà hàng và địa chỉ nếu là quan_ly
            'Ten_nha_hang' => 'required_if:role,quan_ly|nullable|unique:users,Ten_nha_hang',
            'Dia_chi' => 'required_if:role,quan_ly|nullable',
            'SDT' => 'required | digits:10 |unique:users',
            'email' => 'required | email | unique:users',
            'Ten_dang_nhap' => 'required | unique:users',
            'password' => 'required | min:5 | confirmed',
        ],[
            'Ten_nha_hang.required_if' => '* Tên nhà hàng là bắt buộc đối với Quản lý.',
            'Ten_nha_hang.unique' => '* Tên nhà hàng đã tồn tại.',
            'Dia_chi.required_if' => '* Địa chỉ là bắt buộc đối với Quản lý.',
            'email.unique' => '* Email đã tồn tại.',
            'Ten_dang_nhap.unique' => '* Tên đăng nhập đã tồn tại.',
            'password.min' => '* Mật khẩu phải chứa ít nhất 5 kí tự.',
            'password.confirmed' => '* Mật khẩu xác nhận nhập không đúng.',
            'SDT.digits' => '* Số điện thoại phải có 10 số.',
            'SDT.unique' => '* Số điện thoại đã tồn tại.',
        ]);

        User::create([
            'role' => $request->input('role'),
            // Lưu thông tin nhà hàng nếu là quản lý, ngược lại để null
            'Ten_nha_hang' => $request->input('role') === 'quan_ly' ? $request->input('Ten_nha_hang') : null,
            'Dia_chi' => $request->input('role') === 'quan_ly' ? $request->input('Dia_chi') : null,
            'SDT' => $request->input('SDT'),
            'email' => $request->input('email'),
            'Ten_dang_nhap' => $request->input('Ten_dang_nhap'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('auth.login')->with('thanhcong', 'Đăng ký thành công!');
    }

    // ... các phương thức loginCheck, dangXuat, dieuhuong, edit, update, destroy giữ nguyên ...
// Login Check;
    public function loginCheck(Request $request){
        $request->validate([
            'ten_dang_nhap' => 'required',
            'password' => 'required | min:5',
        ]);

        // Tìm thông tin theo Email, Tên đăng nhập hoặc tài khoản Nhân viên
        $userinfoEmail = User::where('email', $request->ten_dang_nhap)->first();
        $userinfoUser = User::where('Ten_dang_nhap', $request->ten_dang_nhap)->first();
        $staffInfo = NhanVien::where('tai_khoan', $request->ten_dang_nhap)->first(); 

        // 1. Kiểm tra nếu là Nhân viên nhà hàng
        if ($staffInfo) {
            if($request->password === $staffInfo->mat_khau){
                $checkRole = ChucVu::where('ID_chuc_vu', $staffInfo->chuc_vu_id)
                                    ->where('ID_nha_hang', $staffInfo->ID_nha_hang)->first(); 
                $request->session()->put([
                    'DangNhap' => $staffInfo->ID_nha_hang,
                    'CheckRole' => $checkRole->quyen,             
                    'TenChucVu' => $checkRole->ten_chuc_vu,             
                    'TenDangNhap' => $staffInfo->tai_khoan,             
                    'NhanVien' => $staffInfo,             
                ]);
                // Nhân viên luôn vào trang quản trị
                return view('admin.trangchu.trangchu')->with('data', User::where('id', session('DangNhap'))->first());
            } else {
                return back()->with('thatbai','* Mật khẩu nhân viên không đúng');
            }
        }

        // 2. Kiểm tra nếu là User (Quản lý hoặc Khách hàng)
        $user = $userinfoEmail ? $userinfoEmail : $userinfoUser;

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('DangNhap', $user->id);
                $request->session()->put('UserRole', $user->role); // Lưu role vào session để kiểm tra

                // PHÂN QUYỀN ĐIỀU HƯỚNG
                if ($user->role === 'quan_ly') {
                    // Nếu là Quản lý: Vào trang quản trị
                    return view('admin.trangchu.trangchu')->with('data', $user);
                } else {
                    // Nếu là Khách hàng: Quay về trang chủ
                    return redirect('/')->with('thanhcong', 'Đăng nhập thành công!');
                }
            } else {
                return back()->with('thatbai','* Mật khẩu không đúng, vui lòng nhập lại');
            }
        }

        return back()->with('thatbai', '* Tài khoản đăng nhập hoặc email không tồn tại');
    }

    function dangXuat(){
        if (session()->has('DangNhap')){
            session()->pull('DangNhap');
            session()->pull('CheckRole');
            session()->pull('TenChucVu');
            session()->pull('TenDangNhap');
            session()->pull('NhanVien');
            return redirect('/');
        }
    }

    public function dieuhuong($slug){
        $data = User::where('id',session('DangNhap'))->first();
        $monans = MonAn::all();
        $nguyenlieus = NguyenLieu::all();
        $bans = Ban::all();
        $datmons = DatMon::all();
        $chucvus = ChucVu::all();
        $nhanviens = NhanVien::all();

        $tong_tien['tong_tien'] = 0;
        $tong_tien['ten_ban_thanh_toan'] = "Chưa chọn bàn";
        $tong_doanh_thu['so_don_hang'] = 0;
        $tong_doanh_thu['tong_doanh_thu'] = 0;
        $tong_doanh_thu['tong_loi_nhuan'] = 0;
        $tong_doanh_thu['loi_nhuan'] = 0;
        $doanhthus = array();
        $bat_dau = '';
        $ket_thuc = '';

        return view("admin.{$slug}.{$slug}")
        ->with('data', $data)
        ->with('monans', $monans)
        ->with('nguyenlieus', $nguyenlieus)
        ->with('bans', $bans)
        ->with('datmons', $datmons)
        ->with('tong_tien', $tong_tien)
        ->with('chucvus', $chucvus)
        ->with('nhanviens', $nhanviens)
        ->with('doanhthus', $doanhthus)
        ->with('tong_doanh_thu', $tong_doanh_thu)
        ->with('bat_dau', $bat_dau)
        ->with('ket_thuc', $ket_thuc)
        ;
        
    }

    public function dieuhuong2($slug, $slug2){
        $data = User::where('id',session('DangNhap'))->first();
        $monans = MonAn::all();
        $nguyenlieus = NguyenLieu::all();
        $bans = Ban::all();
        $datmons = DatMon::all();
        $chucvus = ChucVu::all();
        $nhanviens = NhanVien::all();

        $tong_tien['tong_tien'] = 0;
        $tong_tien['ten_ban_thanh_toan'] = "Chưa chọn bàn";
        $tong_doanh_thu['so_don_hang'] = 0;
        $tong_doanh_thu['tong_doanh_thu'] = 0;
        $tong_doanh_thu['tong_loi_nhuan'] = 0;
        $tong_doanh_thu['loi_nhuan'] = 0;
        $doanhthus = array();
        $bat_dau = '';
        $ket_thuc = '';

        return view("admin.{$slug}.{$slug2}")
        ->with('data', $data)
        ->with('monans', $monans)
        ->with('nguyenlieus', $nguyenlieus)
        ->with('bans', $bans)
        ->with('datmons', $datmons)
        ->with('tong_tien', $tong_tien)
        ->with('chucvus', $chucvus)
        ->with('nhanviens', $nhanviens)
        ->with('doanhthus', $doanhthus)
        ->with('tong_doanh_thu', $tong_doanh_thu)
        ->with('bat_dau', $bat_dau)
        ->with('ket_thuc', $ket_thuc)
        ;
    }

    public function edit($id)
    {
        $data = User::find($id);
        return View('admin.trangchu.sua', ['data'=>$data]);
    }

    public function update(Request $request)
    {
        $data = User::find($request->id);
        $data['Ten_nha_hang'] = $request->Ten_nha_hang;
        $data['Dia_chi'] = $request->Dia_chi;
        $data['SDT'] = $request->SDT;
        $data['email'] = $request->email;
        $data['Ten_dang_nhap'] = $request->Ten_dang_nhap;
        $data['password'] = Hash::make($request->password);

        $data->save();
        return Redirect('/User/trangchu');

    }

    public function destroy($id)
    {
        $data = User::find($id);
        
        $data->delete();
        if (session()->has('DangNhap')){
            session()->pull('DangNhap');
            session()->pull('CheckRole');
            session()->pull('TenChucVu');
            session()->pull('TenDangNhap');
            session()->pull('NhanVien');
            return redirect('/');
        }
        return redirect('/');
    }

    // Trang chủ khách hàng
    public function khachHangIndex() {
        // Kiểm tra nếu chưa đăng nhập thì bắt đăng nhập
        if (!session()->has('DangNhap')) {
            return redirect()->route('auth.login');
        }

        $data = User::where('id', session('DangNhap'))->first();
        
        // Nếu là quản lý mà vào nhầm trang này thì cho qua trang admin
        if($data->role === 'quan_ly') {
            return redirect('/User/trangchu');
        }

        return view('khachhang.index', compact('data'));
    }

    // Cập nhật thông tin khách hàng
    public function khachHangUpdate(Request $request) {
        $request->validate([
            'SDT' => 'required|digits:10',
            'email' => 'required|email',
            'password' => 'nullable|min:5|confirmed',
        ]);

        $user = User::find(session('DangNhap'));
        $user->SDT = $request->SDT;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('thanhcong', 'Cập nhật thông tin thành công!');
    }
    
}