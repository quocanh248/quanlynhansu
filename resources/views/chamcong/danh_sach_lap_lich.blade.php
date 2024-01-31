@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Danh sách lập lịch <i class="far fa-question-circle"></i> <span
                style="color: red">(QT: quẹt thẻ; LL: Lập lịch)</span></h5>
        <div class="d-flex ms-auto">
            <form action="/lay_danh_sach_lap_lich_theo_nhom" method="GET" style="display: contents">
                @csrf
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Chọn nhóm</label>
                        <input type="search" class="form-control" name="tennhom" value="{{ $tennhom }}" id="ten_nhom"
                            list="listnhom" autocomplete="off">
                        <datalist id="listnhom">
                            @foreach ($dsnhom as $item)
                                <option value="{{ $item->tennhom }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Chọn ngày</label>
                        <input type="date" class="form-control" name="ngay" value="{{ $today }}">
                    </div>
                </div>
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Tag</label>
                        <input type="text" class="form-control" value="" list="listtag" id="dsbophan" autocomplete="off">
                        <datalist id="listtag">
                            @foreach ($dstag as $item)
                                <option value="{{ $item->nhom }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center p-1">
                    <button class="btn btn-primary" id="laplich_tim"><i class="fa-solid fa-magnifying-glass"></i>Tìm</button>
                </div>
            </form>
            <div class="d-flex align-items-center justify-content-center p-1">
                <button class="btn btn-primary" id="trang_lap_lich">
                    <i class="fa-regular fa-calendar-days"></i>
                    Lập lịch
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-center p-1 border-start">
                <button class="btn" id="reload"><i class="fas fa-redo"></i></button>
            </div>
        </div>
    </div>
    <div class="p-3">
        <div class="bg-white p-3">
            <table id="bangNhanVien" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th hidden>Tag</th>
                        <th>nhân sự</th>
                        <th>Bộ phận</th>
                        <th>Ngày</th>
                        <th>LL(vào)</th>
                        <th>LL(ra)</th>
                        <th>Tổng(h)</th>
                        <th>QT(vào)</th>
                        <th>QT(ra)</th>
                        <th>Tổng(h)</th>
                        <th>Trễ(p)</th>
                    </tr>
                </thead>
                <tbody id="bangdsnhansubody">
                    @foreach ($dsnhansu as $item)
                        @php
                            $tonggiolam_rounded = 0;
                            $tonglaplich = $item->tonglaplich;
                            $quetthevao = $item->thoigianvao;
                            $quetthera = $item->thoigianra;
                            $giolaplich = $gioquetthe = $sophuttre = '';

                            if ($tonglaplich) {
                                $tonglaplich = $item->tonglaplich / 60;
                                $giolaplich = "$item->laplichvao -> $item->laplichra ($tonglaplich)";
                            }

                            if ($quetthevao && $quetthera) {
                                $tonggiolam_rounded = round($item->tonggiolamthucte / 60, 1);
                                $sophuttre = $item->sophuttre;
                            } elseif ($quetthevao) {
                                $gioquetthe = $quetthevao;
                            }
                        @endphp
                        <tr>
                            <td hidden>{{ $item->nhom }}</td>
                            <td style="white-space: nowrap;">{{ $item->manhansu }} - {{ $item->tennhansu }}</td>
                            <td>{{ $item->tennhom }}</td>
                            <td>{{ $item->ngaydiemdanh }}</td>
                            <td>{{ $item->laplichvao }}</td>
                            <td>{{ $item->laplichra }}</td>
                            <td>{{ $tonglaplich }}</td>
                            <td>{{ $gioquetthe }}</td>
                            <td>{{ $item->thoigianra }}</td>
                            <td>{{ $tonggiolam_rounded }}</td>
                            <td>{{ $sophuttre }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="exampleModal">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-id="basic">Cơ bản</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-id="advanced">Nâng cao</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-id="attr">Thuộc tính</a>
                        </li>
                    </ul>

                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo-shadow.png"
                                    alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mã (*)</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên (*)</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ngày sinh (*)</label>
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Giới tính (*)</label>
                                            <select class="form-select">
                                                <option selected>-- Giới tính --</option>
                                                <option value="1">Nam</option>
                                                <option value="2">Nữ</option>
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
                                    <select class="form-select">
                                        <option selected>CL</option>
                                        <option value="1">Nam</option>
                                        <option value="2">Nữ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ca làm việc mặc định</label>
                                    <select class="form-select">
                                        <option selected>CL</option>
                                        <option value="1">Nam</option>
                                        <option value="2">Nữ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nơi làm việc (*)</label>
                                    <select class="form-select">
                                        <option selected>CL</option>
                                        <option value="1">Nam</option>
                                        <option value="2">Nữ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại (*)</label>
                                    <input type="tel" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="advanced" class="tab-content">
                        Nâng cao
                    </div>

                    <div id="attr" class="tab-content">
                        Thuộc tính
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Xóa</button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
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
        });
    </script>

    <script>
        var ipbophan = document.getElementById("dsbophan");
        ipbophan.addEventListener("input", timKiem);

        function timKiem() {
            var filter = ipbophan.value.toUpperCase();
            var table = document.getElementById("bangdsnhansubody");
            var tr = table.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    var txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
    <script>
        var ten_nhom = document.getElementById("ten_nhom");      
        var laplich_tim = document.getElementById("laplich_tim");
        $("#trang_lap_lich").click(function() {
            if (ten_nhom.value == "") {
                alert("Chọn nhóm")
            } else {
                window.location.href = "/trang_lap_lich_nhom/" + ten_nhom.value;
            }
        });
        ten_nhom.addEventListener("change", function() {
            laplich_tim.click();
        });
    </script>
@endsection
