@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Lập lịch <i class="far fa-question-circle"></i></h5>
        <div class="d-flex ms-auto">
            <form action="/tim_nhan_su_theo_nhom" method="get" style="display: contents">              
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Chọn nhóm</label>
                        <input type="search" class="form-control" name="tennhom" value="{{ $tennhom }}"
                            list="listnhom" autocomplete="off" id="laplich_nhom">
                        <datalist id="listnhom">
                            @foreach ($dsnhom as $item)
                                <option value="{{ $item->tennhom }}"></option>
                            @endforeach
                        </datalist>
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
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fa-regular fa-calendar-days"></i>
                    Chọn ngày
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-center p-1">
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_them_tag">
                    <i class="fas fa-plus"></i>
                    Thêm tag
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
                        <th hidden></th>
                        <th class="text-center"><input type="checkbox" name="" id="select-all" class="ipcheckbox1"></th>
                        <th>Mã nhân sự</th>
                        <th>Tên nhân sự</th>                       
                    </tr>
                </thead>
                <tbody id="bangdsnhansubody">
                    @foreach ($dsnhansu as $item)
                        <tr>
                            <td hidden>{{ $item->nhom }}</td>
                            <td style="text-align: center"><input type="checkbox" name="" class="ipcheckbox"> </td>
                            <td style="white-space: nowrap;">{{ $item->manhansu }}</td>
                            <td style="white-space: nowrap;">{{ $item->tennhansu }}</td>                           
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
                    <h5 class="modal-title">Lập lịch làm việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ngày bắt đầu (*)</label>
                                            <input type="date" class="form-control" id="ngaybatdau">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ngày kết thúc (*)</label>
                                            <input type="date" class="form-control" id="ngayketthuc">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ca làm việc (*)</label>
                                            <input type="text" class="form-control" list="listca" id="ca">
                                            <datalist id="listca">
                                                @foreach ($dsca as $item)
                                                    <option value="{{ $item->tenca }}"></option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Giờ bắt đầu (*)</label>
                                            <input type="text" class="form-control" readonly id="giovao">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Giờ kết thúc (*)</label>
                                            <input type="text" class="form-control" readonly id="giora">
                                        </div>
                                    </div>
                                    <div class="col-md-3" hidden>
                                        <div class="mb-3">
                                            <input type="text" id="thoigiannghi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-danger">Xóa</button> --}}
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="lap_lich">Lập lịch</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="modal_them_tag">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content" style="height: 40%;">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nhập tag</label>
                                            <input type="text" class="form-control" id="input_tag">
                                        </div>
                                    </div>                             
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-danger">Xóa</button> --}}
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="them_tag">Thêm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/dist/jquery/jquery37.js"></script>
    <script src="/dist/jquery/chamcong_js/laplich.js"></script> 
@endsection
