<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ khách hàng - Restaurant Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL('css/sb-admin-2.min.css') }}">
    <style>
        body { background-color: #f8f9fc; font-family: 'Nunito', sans-serif; }
        .card { border: none; border-radius: 0.75rem; }
        .shadow-custom { box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important; }
        .btn-rounded { border-radius: 50px; }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="mb-4">
            <a href="/" class="btn btn-light btn-sm btn-rounded shadow-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left me-2"></i> QUAY LẠI TRANG CHỦ
            </a>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">BẢNG ĐIỀU KHIỂN KHÁCH HÀNG</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-custom mb-4">
                    <div class="card-header py-3 bg-white border-bottom-0">
                        <h6 class="m-0 font-weight-bold text-primary text-uppercase">
                            <i class="fas fa-user-edit me-2"></i> Chỉnh sửa hồ sơ cá nhân
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if(session('thanhcong'))
                            <div class="alert alert-success shadow-sm border-0">{{ session('thanhcong') }}</div>
                        @endif

                        <form action="{{ route('khachhang.update') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold small text-muted">Tên tài khoản</label>
                                    <input type="text" class="form-control bg-light border-0" value="{{ $data->Ten_dang_nhap }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold small text-muted">Địa chỉ Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $data->email }}" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold small text-muted">Số điện thoại</label>
                                    <input type="text" name="SDT" class="form-control" value="{{ $data->SDT }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold small text-muted">Trạng thái</label>
                                    <input type="text" class="form-control bg-light border-0" value="Khách hàng thành viên" readonly>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 mb-4">
                                <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-key me-2"></i> Bảo mật tài khoản</h6>
                                <p class="text-muted small">* Chỉ điền nếu bạn muốn thay đổi mật khẩu đăng nhập.</p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu mới">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu mới">
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-2">
                                <button type="submit" class="btn btn-primary btn-rounded px-5 shadow-sm">
                                    <i class="fas fa-save me-2"></i> LƯU THÔNG TIN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-custom text-center p-4">
                    <div class="mb-3 mt-2">
                        <img src="https://ui-avatars.com/api/?name={{ $data->Ten_dang_nhap }}&background=4e73df&color=fff&size=128" 
                             class="rounded-circle shadow-sm" width="100" alt="Avatar">
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">{{ $data->Ten_dang_nhap }}</h5>
                    <p class="text-primary small font-weight-bold mb-3 text-uppercase">Customer Account</p>
                    
                    <hr class="my-3">
                    
                    <div class="text-left px-2">
                        <div class="d-flex justify-content-between mb-2 small font-weight-bold text-muted">
                            <span>Lượt đặt bàn:</span>
                            <span class="text-dark">05</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small font-weight-bold text-muted">
                            <span>Phản hồi:</span>
                            <span class="text-dark">03</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 mb-2">
                        <a href="{{ route('auth.logoff') }}" class="btn btn-outline-danger btn-sm btn-rounded btn-block">
                            <i class="fas fa-sign-out-alt me-2"></i> ĐĂNG XUẤT TÀI KHOẢN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>
    <script src="{{ URL('js/jquery.min.js') }}"></script>
    <script src="{{ URL('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>