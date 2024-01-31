@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Danh sách nhân sự <i class="far fa-question-circle"></i></h5>
        <div class="d-flex ms-auto">
            <div class="d-flex align-items-center justify-content-center p-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_them_nhan_su">
                    Thêm mới
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-center p-1 border-start">
                <div class="form-check form-switch">                        
                    <input class="form-check-input" type="checkbox" role="switch"
                        @if ($an_nghi_viec == "true") checked @endif id="danhsach_trangthai_lamviec">
                    <label class="form-check-label" for="danhsach_trangthai_lamviec">Ẩn nghỉ việc</label>

                </div>
            </div>
        </div>
    </div>
    <div class="p-3">
        <div class="bg-white p-3">
            <table id="bangNhanVien" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Mã nhân sự</th>
                        <th>Tên nhân sự</th>
                        <th>Bộ phận</th>
                        <th>Tag</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>                    
                    @foreach ($dsnhansu as $item)
                        @php
                            $opacity = 1;                           
                            if($item->trangthai == "da_nghi")
                            {
                                $opacity = 0.2;
                            }
                           
                        @endphp
                        <tr style="opacity: {{$opacity}}">
                            <td>{{ $item->manhansu }}</td>
                            <td>{{ $item->tennhansu }}</td>
                            <td>{{ $item->tennhom }}</td>
                            <td>{{ $item->tag }}</td>
                            <td class="d-flex justify-content-center">
                                <button value="{{ $item->manhansu }}" class="btn btn-info btn-danhsach"
                                    data-bs-toggle="modal" data-bs-target="#danh_sach_ttnv">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    CHI TIẾT
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="danh_sach_ttnv">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin chi tiết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="ds_chitiet_tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-id="basic">Cơ bản</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-id="advanced">Nâng cao</a>
                        </li>
                    </ul>
                    <div id="basic" class="tab-content active mt-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img id="danhsach_previewUpload" class="img-thumbnail" src="" alt="">
                                </div>
                                <input type="file" id="danhsach_myFile" class="form-control">
                                <input type="hidden" value="" id="danhsach_filename" name="danhsach_filename">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mã nhân sự (*)</label>
                                            <input type="text" class="form-control" id="danhsach_manhansu" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên nhân sự (*)</label>
                                            <input type="text" class="form-control" id="danhsach_tennhansu">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Ngày sinh (*)</label>
                                            <input type="date" class="form-control" id="danhsach_ngaysinh">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Giới tính (*)</label>
                                            <select class="form-select" id="danhsach_gioitinh">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" style="visibility: hidden">Đang làm việc</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="danhsach_trangthai">
                                                <label class="form-check-label" for="danhsach_trangthai">Đang làm
                                                    việc</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nhóm làm việc (*)</label>
                                    <select class="form-select" id="danhsach_nhomlamviec">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nơi làm việc hiện tại</label>

                                    <input type="text" class="form-control" id="danhsach_noilamviec">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại (*)</label>
                                    <input type="tel" class="form-control" id="danhsach_sdt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="advanced" class="tab-content mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Địa chỉ (*)</label>
                                        <input type="text" id="danhsach_diachi" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Trình độ học vấn (*)</label>
                                    <input type="text" id="danhsach_trinhdo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Dân tộc</label>
                                    <input type="text" id="danhsach_dantoc" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tôn giáo</label>
                                    <input type="text" id="danhsach_tongiao" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Số CCCD (*)</label>
                                    <input type="text" id="danhsach_cccd" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ngày cấp</label>
                                    <input type="date" id="danhsach_ngaycap" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nơi cấp</label>
                                    <input type="text" id="danhsach_noicap" class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu làm </label>
                                    <input type="date" id="danhsach_ngaybatdaulam" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày ký hợp đồng</label>
                                    <input type="date" id="danhsach_ngaykyhopdong" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="danh_sach_cap_nhat_ttcn">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="modal_them_nhan_su">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm mới nhân sự</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/them_nhan_su_moi" method="POST">
                    @csrf
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="ds_themmoi-ds_tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-id="danhsach_basic">Cơ bản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-id="danhsach_advanced">Nâng cao</a>
                            </li>
                        </ul>
                        <div id="danhsach_basic" class="tab-content active mt-2">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <img id="previewUpload" src="" alt="" class="img-thumbnail">
                                    </div>
                                    <input type="file" id="myFile" class="form-control">
                                    <input type="hidden" value="" name="filename">
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mã nhân sự (*)</label>
                                                <input type="text" class="form-control" name="danhsach_manhansu"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tên nhân sự (*)</label>
                                                <input type="text" class="form-control" name="danhsach_tennhansu"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Ngày sinh (*)</label>
                                                <input type="date" class="form-control" name="danhsach_ngaysinh"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Giới tính (*)</label>
                                                <select class="form-select" name="danhsach_gioitinh">
                                                    <option value="male">male</option>
                                                    <option value="female">female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nhóm làm việc (*)</label>
                                        <select class="form-select" name="danhsach_nhomlamviec">
                                            @foreach ($dsnhom as $item)
                                                <option value="{{ $item->manhom }}">{{ $item->tennhom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nơi làm việc hiện tại</label>

                                        <input type="text" class="form-control" name="danhsach_noilamviec">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại (*)</label>
                                        <input type="tel" class="form-control" name="danhsach_sdt">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="danhsach_advanced" class="tab-content mt-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Địa chỉ (*)</label>
                                            <input type="text" name="danhsach_diachi" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Trình độ học vấn (*)</label>
                                        <input type="text" name="danhsach_trinhdo" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Dân tộc</label>
                                        <input type="text" name="danhsach_dantoc" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tôn giáo</label>
                                        <input type="text" name="danhsach_tongiao" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Số CCCD (*)</label>
                                        <input type="text" name="danhsach_cccd" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày cấp</label>
                                        <input type="date" name="danhsach_ngaycap" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nơi cấp</label>
                                        <input type="text" name="danhsach_noicap" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày bắt đầu làm </label>
                                        <input type="date" name="danhsach_ngaybatdaulam" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày ký hợp đồng</label>
                                        <input type="date" name="danhsach_ngaykyhopdong" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="ms-auto">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary" id="danh_sach_cap_nhat_ttcn">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/dist/jquery/jquery37.js"></script>
    <script src="/dist/jquery/jquerydatatable.js"></script>
    <script src="/dist/jquery/jquerydatatablesbuttons.js"></script>
    <script src="/dist/jquery/jqueryjszip.js"></script>
    <script src="/dist/jquery/jquerybuttonhtml5.js"></script>
    <script type="text/javascript">
        // Khai báo biến dataTable
        const dataTable = new DataTable('#bangNhanVien', {
            dom: 'Bfrtip',
            language: {
                sSearch: "Tìm kiếm: "
            },
            buttons: ['excelHtml5'],
            paging: false
        });
    </script>
    <script>
        const ds_themmoi_tabList = $("#ds_themmoi-ds_tabs .nav-link");
        ds_themmoi_tabList.each(function(index, element) {
            $(element).on("click", function() {
                $("#ds_themmoi-ds_tabs .nav-link.active").removeClass("active");
                $(element).addClass("active");
                $(".tab-content.active").removeClass("active");
                var id = $(this).data("id");
                $("#" + id).addClass("active");
            });
        });

        const ds_chitiet_tabs = $("#ds_chitiet_tabs .nav-link");
        ds_chitiet_tabs.each(function(index, element) {
            $(element).on("click", function() {
                $("#ds_chitiet_tabs .nav-link.active").removeClass("active");
                $(element).addClass("active");
                $(".tab-content.active").removeClass("active");
                var id = $(this).data("id");
                $("#" + id).addClass("active");
            });
        });
        $(document).ready(function() {
            $('#reload').click(function() {
                location.reload();
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const danhsach_trangthai_lamviec = document.getElementById("danhsach_trangthai_lamviec");
            danhsach_trangthai_lamviec.addEventListener("change", function() {
                const isChecked = this.checked;
                const url = isChecked ? "/danh_sach_nhan_su/true" : "/danh_sach_nhan_su/false";
                window.location.href = url;
            });
        });
        document.querySelector('#myFile').onchange = function(e) {
            var formData = new FormData();
            formData.append('tmpFile', $(this)[0].files[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/upload_hinhanh/image',
                method: "POST",
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $('input[name="filename"]').val(res);
                    $('#previewUpload').attr('src', res);
                }
            })
        }
        document.querySelector('#danhsach_myFile').onchange = function(e) {
            var formData = new FormData();
            formData.append('tmpFile', $(this)[0].files[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/upload_hinhanh/image',
                method: "POST",
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $('input[name="danhsach_filename"]').val(res);
                    $('#danhsach_previewUpload').attr('src', res);
                }
            })
        }
    </script>
@endsection
