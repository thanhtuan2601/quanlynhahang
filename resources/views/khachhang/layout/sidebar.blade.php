<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-utensils"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KHÁCH HÀNG</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-home"></i>
            <span>Về Trang chủ Nhà hàng</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Tài khoản cá nhân
    </div>

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('khachhang.index') }}">
            <i class="fas fa-fw fa-user-cog"></i>
            <span>Thông tin tài khoản</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-history"></i>
            <span>Lịch sử đặt bàn (Sắp ra mắt)</span></a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>