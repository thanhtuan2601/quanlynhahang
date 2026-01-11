@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="" style="margin-top: 10px">
            <strong>QUẢN LÝ MÓN ĂN</strong>&ensp;
            <i class="fas fa-utensils"></i>
        </h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">STT</th> <th scope="col">Loại</th>
                        <th scope="col">Tên món</th>
                        <th scope="col">Đơn giá</th>
                        <th scope="col">Thay đổi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Khởi tạo biến đếm STT bắt đầu từ 1 --}}
                    @php $stt = 1; @endphp

                    @foreach ($monans as $monan)
                        {{-- Chỉ đếm và hiển thị các món thuộc nhà hàng này --}}
                        @if($monan['ID_nha_hang'] == $data['id'])
                        <tr>
                            {{-- Hiển thị STT và tự tăng lên 1 cho dòng kế tiếp --}}
                            <th scope="row">{{ $stt++ }}</th>
                            
                            <td>{{$monan['loai']}}</td>
                            <td>{{$monan['ten_mon']}}</td>
                            <td>{{number_format($monan['don_gia'])}} VNĐ</td>
                            <td>
                                {{-- Nút Sửa/Xóa vẫn giữ nguyên ID_mon để tìm đúng dữ liệu trong DB --}}
                                <a href="/User/monan/sua/id={{$monan['ID_mon']}}" type="button" class="btn btn-warning btn-rounded">Sửa</a>
                                <a href="/User/monan/xoa/id={{$monan['ID_mon']}}" onclick="return confirm('Bạn có thật sự muốn xóa ?');" type="button"
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
        <a href="/User/monan/them" type="button" class="btn btn-info">Thêm Món ăn</a>
    </div>
</div>

<br>
@endsection