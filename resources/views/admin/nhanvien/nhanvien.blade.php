@extends('admin.index')

@section('admin_content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="" style="margin-top: 10px">
                <strong>QUẢN LÝ NHÂN VIÊN</strong>&ensp;
                <i class="fas fa-users"></i>
            </h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">STT</th> <th scope="col">Tên</th>
                            <th scope="col">Chức vụ</th>
                            <th scope="col">Giới tính</th>
                            <th scope="col">Địa chỉ</th>
                            <th scope="col">SĐT</th>
                            <th scope="col">Tài khoản</th>
                            <th scope="col">Thay đổi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- BƯỚC 1: Khởi tạo biến đếm STT bắt đầu từ 1 --}}
                        @php $stt = 1; @endphp

                        @foreach ($nhanviens as $nhanvien)
                            @if ($nhanvien['ID_nha_hang'] == $data['id'])
                                <tr>
                                    {{-- BƯỚC 2: Hiển thị STT tự tăng --}}
                                    <th scope="row">{{ $stt++ }}</th>
                                    
                                    <td>{{ $nhanvien['ten_nhan_vien'] }}</td>
                                    <td>{{ $nhanvien['chuc_vu'] }}</td>
                                    <td>{{ $nhanvien['gioi_tinh'] }}</td>
                                    <td>{{ $nhanvien['dia_chi'] }}</td>
                                    <td>{{ $nhanvien['sdt'] }}</td>
                                    <td>{{ $nhanvien['tai_khoan'] }}</td>
                                    <td>
                                        <a href="/User/nhanvien/xem/id={{ $nhanvien['ID_nhan_vien'] }}" type="button" class="btn btn-success btn-rounded">Xem</a>
                                        <a href="/User/nhanvien/sua/id={{ $nhanvien['ID_nhan_vien'] }}" type="button" class="btn btn-warning btn-rounded">Sửa</a>
                                        <a href="/User/nhanvien/xoa/id={{ $nhanvien['ID_nhan_vien'] }}"
                                            onclick="return confirm('Bạn có thật sự muốn xóa ?');" type="button"
                                            class="btn btn-danger btn-rounded">Xóa</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>

                <script>
                    $(document).ready(function() {
                        $('#dataTable').DataTable();
                    });
                </script>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="card-title" style="margin-top: 10px">Tùy chỉnh:</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="/User/nhanvien/them" type="button" class="btn btn-info">Thêm nhân viên</a>
                </div>
            </div>
        </div>
    </div>

    <br>

@endsection