<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Việt Trần</title>
    <link rel="icon" href="/dist/images/logo.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dist/fonts/fontawesome-free-6.4.2-web/css/all.min.css">
    <link rel="stylesheet" href="/dist/css/style.css">
    <link rel="stylesheet" href="/dist/css/cssdatatable.css" />
    <link rel="stylesheet" href="/dist/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="/dist/css/bootstrapjs.css">
</head>

<body>
    <header class="d-flex bg-dark">
        <div class="p-9px">
            <img class="header__img" src="/dist/images/logo.png" alt="Logo">
        </div>
        @php
            $manhansu = $role = Auth::user()->manhansu;
        @endphp
        <div class="nav flex-grow-1 scroll-x-view">
            <div class="nav__item">
                <a href="#" class="nav__link">
                    <i class="far fa-user"></i>
                    <span>Nhân sự</span>
                </a>
                <div class="nav__submenu">
                    <div class="nav__item">
                        <a href="/danh_sach_nhan_su/true" class="nav__link">
                            <span>Danh sách nhân sự</span>
                        </a>
                    </div>
                    <div class="nav__item">
                        <a href="/trang_lap_lich" class="nav__link">
                            <span>Lập lịch</span>
                        </a>
                    </div>
                    <div class="nav__item">
                        <a href="/danh_sach_lap_lich" class="nav__link">
                            <span>Danh sách lập lịch</span>
                        </a>
                    </div>
                    <div class="nav__item">
                        <a href="/danh_sach_diem_danh" class="nav__link">
                            <span>Danh sách điểm danh</span>
                        </a>
                    </div>
                    <div class="nav__item">
                        <a href="/trang_xuat_du_lieu_lap_lich" class="nav__link">
                            <span>Xuất dữ liệu</span>
                        </a>
                    </div>
                    <div class="nav__item">
                        <a href="/trang_chot_cong" class="nav__link">
                            <span>Chốt công</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="nav__item">
                <a href="#" class="nav__link">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Quản trị</span>
                </a>
                <div class="nav__submenu">
                    <div class="nav__item">
                        <a href="/trang_cap_tai_khoan" class="nav__link">
                            <span>Danh sách tài khoản</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav flex text-nowrap">
            <div class="p-9px">
                <style>
                    .rounded-pill {
                        width: 34px;
                        height: 34px;
                    }
                </style>
                <img class="header__img rounded-pill" src="{{ Session::get('avarta') }}" alt="Logo">
            </div>
            <div class="nav__item">
                @if (Session::get('username') != null)
                    <a href="#" class="nav__link">
                        <span>{{ Session::get('username') }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                @endif
                <div class="nav__submenu" style="right: 0">
                    <div class="nav__item">
                        <a class="nav__link" onclick="dangxuat()">
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                    <div class="nav__item">
                        <a class="nav__link tt_ca_nhan bt-tt-none" data-custom-value="{{ $manhansu }}"
                            data-bs-toggle="modal" data-bs-target="#madal_thongtincanhan">
                            <span>Thông tin cá nhân</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </header>
    @yield('content')
    <div class="modal" tabindex="-1" id="madal_thongtincanhan">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin cá nhân</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="trang_chu_tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-id="trangchu_basic">Cơ bản</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-id="trangchu_advanced">Nâng cao</a>
                        </li>
                    </ul>

                    <div id="trangchu_basic" class="tab-content active mt-2">
                        <div class="row">
                            <div class="col-md-3 ">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img id="trangchu_previewUpload" src="" alt=""
                                        class="img-thumbnail">
                                </div>
                                <input type="file" id="trangchu_myFile" class="form-control">
                                <input type="hidden" value="" id="trangchu_filename"
                                    name="trangchu_filename">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mã nhân sự (*)</label>
                                            <input type="text" class="form-control" id="trangchu_manhansu"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên nhân sự (*)</label>
                                            <input type="text" class="form-control" id="trangchu_tennhansu">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ngày sinh (*)</label>
                                            <input type="date" class="form-control" id="trangchu_ngaysinh">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Giới tính (*)</label>
                                            <select class="form-select" id="trangchu_gioitinh">

                                            </select>
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
                                    <select class="form-select" id="trangchu_nhomlamviec">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nơi làm việc hiện tại</label>

                                    <input type="text" class="form-control" id="trangchu_noilamviec">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại (*)</label>
                                    <input type="tel" class="form-control" id="trangchu_sdt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="trangchu_advanced" class="tab-content mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Địa chỉ (*)</label>
                                        <input type="text" id="trangchu_diachi" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Trình độ học vấn (*)</label>
                                    <input type="text" id="trangchu_trinhdo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Dân tộc</label>
                                    <input type="text" id="trangchu_dantoc" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tôn giáo</label>
                                    <input type="text" id="trangchu_tongiao" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Số CCCD (*)</label>
                                    <input type="text" id="trangchu_cccd" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ngày cấp</label>
                                    <input type="date" id="trangchu_ngaycap" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nơi cấp</label>
                                    <input type="text" id="trangchu_noicap" class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu làm </label>
                                    <input type="date" id="trangchu_ngaybatdaulam" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày ký hợp đồng</label>
                                    <input type="date" id="trangchu_ngaykyhopdong" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="trang_chu_cap_nhat_ttcn">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 -->
    <script src="/dist/jquery/js/bootstrap.bundle.min.js"></script>
    <script src="/dist/jquery/jquery37.js"></script>
    <script src="/dist/jquery/nhansu_js/thongtincanhan.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    <!-- DataTable JS -->
    {{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script> --}}
    {{-- <script>
        $(document).ready(function () {
            $('#example').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Hiện thị _MENU_ dòng",
                    "sSearch": "Tìm kiếm: ",
                    "oPaginate": {
                        "sPrevious": "<",
                        "sNext": ">",
                        "sFirst": "<<",
                        "sLast": ">>",
                    },
                    "sInfo": "Hiển thị từ _START_ đến _END_ của _TOTAL_ dòng",
                    "sZeroRecords": "Không có kết quả",
                }
            });
        });
    </script> --}}
    <script type="text/javascript">
        function dangxuat() {
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                sessionStorage.clear();
                localStorage.clear();
                $.ajax({
                    url: '/dang_xuat',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response) {
                            window.location.href = "/";
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }
    </script>
</body>

</html>
