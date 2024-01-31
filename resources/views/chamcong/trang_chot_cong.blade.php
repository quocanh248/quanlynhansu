@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Danh sách lập lịch <i class="far fa-question-circle"></i> </h5>
        <div class="d-flex ms-auto">
            <form action="/chot_cong" method="get" style="display: contents">             
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Chọn tháng</label>
                        <input type="month" class="form-control" name="thang" value="{{ $month }}">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center p-1">
                    <button class="btn btn-primary">
                        <i class="fa-regular fa-circle-check"></i>
                        Chốt
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="p-3">
        <div class="bg-white p-3">
            <table id="bangNhanVien" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="bangdsnhansubody">
                    @foreach ($ds as $item)
                        <tr>
                            <td>{{ $item->thang }}</td>
                            <td>
                                @if ($item->tt == 'dc')
                                    ĐÃ CHỐT
                                @else
                                    ĐANG MỞ
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $content = 'KHÓA';
                                    if ($item->tt == 'dc') {
                                        $content = 'MỞ';
                                    }
                                @endphp
                                <button type="button" class="btn btn-info"  value="{{ $item->id }}">{{ $content }}</button>
                            </td>
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
        $('.btn-info').click(function() {
            var button = $(this);
            var id = button.val();    
            console.log(id);       
            $(document).ready(function() {
                $.ajax({
                    url: '/cap_nhat_chot_cong',
                    method: 'POST',
                    data: {
                        id: id,                     
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success)
                        {
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
