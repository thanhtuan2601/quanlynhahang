<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ khách hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fc; }
        .card { border-radius: 15px; border: none; }
        .btn-rounded { border-radius: 50px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="mb-4">
            <a href="/" class="btn btn-light btn-sm btn-rounded shadow-sm text-primary">
                <i class="fas fa-arrow-left me-2"></i> QUAY LẠI TRANG CHỦ
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary text-uppercase">Chỉnh sửa hồ sơ & Mật khẩu</h6>
                    </div>
                    <div class="card-body">
                        @if(session('thanhcong'))
                            <div class="alert alert-success">{{ session('thanhcong') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </div>
                        @endif

                        <form action="{{ route('khachhang.update') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1 fw-bold">Số điện thoại</label>
                                    <input type="text" name="SDT" class="form-control" value="{{ $data->SDT }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1 fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $data->email }}">
                                </div>
                            </div>
                            
                            <hr>
                            <p class="text-muted small">Để trống mật khẩu nếu không muốn thay đổi</p>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="small mb-1 fw-bold text-danger">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới">
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1 fw-bold text-danger">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-rounded shadow-sm">CẬP NHẬT TẤT CẢ</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-success text-uppercase">Bàn bạn đã đặt</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Tên bàn</th>
                                        <th>Thời gian</th>
                                        <th>Số người</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $item->ten_ban }}</td>
                                        <td>{{ $item->dat_truoc }}</td>
                                        <td>{{ $item->datban_so_nguoi }} người</td>
                                        <td>
                                            <a href="{{ route('khachhang.huyban', $item->ID_ban) }}" 
                                               class="btn btn-danger btn-sm btn-rounded"
                                               onclick="return confirm('Bạn chắc chắn muốn hủy đặt bàn này chứ?')">
                                                HỦY ĐẶT
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Bạn chưa đặt bàn nào.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow text-center p-4">
                    <div class="mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ $data->Ten_dang_nhap }}&background=4e73df&color=fff&size=128" class="rounded-circle shadow-sm" width="100">
                    </div>
                    <h5 class="fw-bold">{{ $data->Ten_dang_nhap }}</h5>
                    <div class="d-flex justify-content-between mt-3 small">
                        <span>Lượt đặt:</span>
                        <span class="fw-bold">{{ $totalBookings }}</span>
                    </div>
                    <a href="{{ route('auth.logoff') }}" class="btn btn-outline-danger btn-sm btn-rounded mt-4">ĐĂNG XUẤT</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>