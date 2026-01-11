<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restaurant Manager</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL('images2/spatula2.svg') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ URL('css/vinhuy.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body style="padding: 0; margin: 0;">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand me-2" href="/"><img src="{{ URL('images1/logo.png') }}" height="44" alt=""
                    loading="lazy"></a>

            <a class="nav-link dropdown-toggle text-reset" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-2 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search" action="/tim-kiem" method="POST">
                    @csrf
                    <div class="input-group" style="width:300px; margin-top:2px">
                        <input type="text" name="tim_kiem" class="form-control bg-light border-0 small" placeholder="Tìm kiếm nhà hàng..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarButtons"
                aria-controls="navbarButtons" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarButtons">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>

                <div class="d-flex align-items-center">
                    <a href="/"><button type="button" class="btn btn-link px-3 me-2">TRANG CHỦ</button></a>
                    <a href="/GioiThieu"><button type="button" class="btn btn-link px-3 me-2">GIỚI THIỆU</button></a>
                    <a href="/NhaHang"><button type="button" class="btn btn-link px-3 me-2">NHÀ HÀNG</button></a>

                    @if (Session::get('DangNhap'))
                        @if (Session::get('UserRole') === 'quan_ly' || Session::get('CheckRole') !== null)
                            <a href="{{ url('/User/trangchu') }}" class="btn btn-primary btn-rounded me-2">
                                <i class="fas fa-tasks"></i> QUẢN LÝ
                            </a>
                        @else
                            <a href="{{ route('khachhang.index') }}" class="btn btn-info btn-rounded me-2 text-white">
                                <i class="fas fa-user"></i> TRANG CÁ NHÂN
                            </a>
                        @endif

                        <a class="btn btn-outline-danger btn-rounded" href="{{ route('auth.logoff') }}">
                            <i class="fas fa-sign-out-alt"></i> ĐĂNG XUẤT
                        </a>
                    @else
                        <a class="btn btn-outline-primary btn-rounded" href="{{ route('auth.login') }}">ĐĂNG NHẬP</a>
                        &ensp;
                        <a class="btn btn-primary btn-rounded" href="{{ route('auth.register') }}">ĐĂNG KÝ MIỄN PHÍ</a>
                    @endif

                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="text-center text-lg-start bg-light text-muted">
        <section class="border-top">
            <div class="container text-center text-md-start mt-5">
                <div class="row mt-3">
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <h6 class="text-uppercase fw-bold mb-4"> Liên hệ </h6>
                        <p><i class="fas fa-home me-3"></i> Hà Tĩnh, Việt Nam</p>
                        <p><i class="fas fa-envelope me-3"></i> restaurantsp@gmail.com</p>
                        <p><i class="fas fa-phone me-3"></i> +84 964196652</p>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <h6 class="text-uppercase fw-bold mb-4">Danh mục</h6>
                        <p><a href="/NhaHang" class="text-reset">Nhà hàng</a></p>
                    </div>
                </div>
            </div>
        </section>
        <section class="text-center p-4 border-top" style="background-color: #757575;">
            <span class="text-white">© 2025 RestaurantManager</span>
        </section>
    </footer>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>
    <script src="{{ URL('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // 1. Xử lý thông báo ĐẶT BÀN THÀNH CÔNG (Dựa trên biến alert từ Controller)
            @if(session('alert') == '1')
                Swal.fire({
                    icon: 'success',
                    title: 'Đặt bàn thành công!',
                    text: 'Yêu cầu của bạn đã được gửi tới nhà hàng thành công.',
                    confirmButtonColor: '#4e73df'
                });
            @endif

            // 2. Xử lý thông báo thành công chung (Đăng nhập, cập nhật hồ sơ, v.v.)
            @if(session('thanhcong'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: '{{ session("thanhcong") }}',
                    confirmButtonColor: '#4e73df'
                });
            @endif

            // 3. Xử lý thông báo lỗi (Thất bại)
            @if(session('thatbai'))
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại!',
                    text: '{{ session("thatbai") }}',
                    confirmButtonColor: '#e74a3b'
                });
            @endif
        });
    </script>
</body>
</html>