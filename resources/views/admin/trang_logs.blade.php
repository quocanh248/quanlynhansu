@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Logs <i class="far fa-question-circle"></i> </h5>
        <div class="d-flex ms-auto">
            <div class="d-flex align-items-center justify-content-center p-2">
                <button class="btn btn-primary" style="visibility: hidden">     
                    gg             
                </button>
            </div>
        </div>
    </div>
    <div class="p-3">
        <div class="bg-white p-3">
            <table id="bangNhanVien" class="table table-bordered" style="width:100%; font-size: 12px">
                <thead>
                    <tr>
                        <th>Người chỉnh sửa</th>
                        <th>ip</th>
                        <th>Thời gian điều chỉnh</th>
                        <th>Mã nhân sự</th>
                        <th>Ngày điểm danh</th>
                        <th>Lập lịch (vào)</th>
                        <th>Lập lịch (ra)</th>
                        <th>Điểm danh (vào)</th>
                        <th>Điểm danh (ra)</th>
                    </tr>
                </thead>
                <tbody id="bangdsnhansubody">
                    @foreach ($dslog as $item)
                        <tr>
                            <td>{{ $item->hoso}} - {{ $item->tennhansu }}</td>
                            <td>{{ $item->ip }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $item->manhansu }}</td>
                            <td>{{ $item->ngaydiemdanh }}</td>
                            <td>{{ $item->laplichvao }}</td>
                            <td>{{ $item->laplichra }}</td>
                            <td>{{ $item->thoigianvao }}</td>
                            <td>{{ $item->thoigianra }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="/dist/jquery/jquery37.js"></script>
    <script src="/dist/jquery/jquerydatatable.js"></script>
    <script src="/dist/jquery/jquerydatatablesbuttons.js"></script>
    <script type="text/javascript">
        // Khai báo biến dataTable
        const dataTable = new DataTable('#bangNhanVien', {
            dom: 'Bfrtip',
            language: {
                sSearch: "Tìm kiếm: "
            }
        });
    </script>
    <script>
        $('#khoa').click(function() {
            var button = $(this);
            var id = button.val();
            $(document).ready(function() {
                $.ajax({
                    url: '/cap_nhat_chot_cong',
                    method: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert("Cập nhật thành công");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
        });
    </script>
@endsection
