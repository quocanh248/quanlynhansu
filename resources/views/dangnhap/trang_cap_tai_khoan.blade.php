@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h4 class="fw-normal text-primary m-0">Danh sách tài khoản <i class="far fa-question-circle"></i></h4>
        <div class="d-flex ms-auto">
            <div class="d-flex align-items-center justify-content-center p-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-plus"></i>
                    Thêm tài khoản
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-center p-2 border-start">
                <button class="btn"><i class="fas fa-redo"></i></button>
            </div>
        </div>
    </div>
    <div class="p-3">
        <div class="bg-white p-3">
            <table id="example" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Mã nhân sự</th>
                        <th>Tên nhân sự</th>
                        <th>Vai trò</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dstaikhoan as $item)
                        <tr>
                            <td>{{ $item->manhansu }}</td>
                            <td>{{ $item->tennhansu }}</td>
                            <td>{{ $item->role }}</td>
                            <td class="d-flex justify-content-center">
                                <button class="btn  btn-info" value="{{ $item->id }}" data-bs-toggle="modal"
                                    data-bs-target="#modal_chitiet">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    Chi tiết
                                </button>
                            </td>
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
                    <h5 class="modal-title">Cấp tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mã nhân sự (*)</label>
                                    <input type="text" class="form-control" autocomplete="off" list="list_nhan_su"
                                        id="manhansu">
                                    <datalist id="list_nhan_su">
                                        @foreach ($dsnhansu as $item)
                                            <option value="{{ $item->manhansu }}">{{ $item->tennhansu }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên nhân sự</label>
                                    <input type="text" class="form-control" readonly id="tennhansu">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu (*)</label>
                                    <input type="password" class="form-control" autocomplete="off" id="matkhau">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu (*)</label>
                                    <input type="password" class="form-control" autocomplete="off" id="xacnhanmatkhau">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Vai trò (*)</label>
                                    <select class="form-select" id="vaitro">
                                        <option value="admin">Admin</option>
                                        <option value="hanhchinh">Hành chính</option>
                                        <option value="hoso">Hồ Sơ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-danger">Xóa</button> --}}
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="cap_tai_khoan">Thêm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="modal_chitiet">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Mã nhân sự (*)</label>
                                            <input type="text" class="form-control" id="tt_manhansu">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Tên nhân sự</label>
                                            <input type="text" class="form-control" readonly id="tt_tennhansu">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu</label>
                                            <input type="password" class="form-control" autocomplete="off" id="tt_matkhau">
                                        </div>
                                    </div>    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Vai trò (*)</label>
                                            <select class="form-select" id="tt_vaitro">
                                                <option value="admin">Admin</option>
                                                <option value="hanhchinh">Hành chính</option>
                                                <option value="hoso">Hồ Sơ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="col-md-8 border-start">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="mb-3">
                                            <label class="form-label">Nhóm</label>
                                            <select class="form-select" id="tt_ds_nhom">
                                               
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label color-none">click</label>
                                            <button type="button" class="btn btn-success" id="them_nhom">Thêm nhóm</button>
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="row">
                                    <table id="" class="table table-bordered" style="width:98%; margin-left: 5px">
                                        <thead>
                                            <tr>
                                                <th>Mã nhóm</th>
                                                <th>Tên nhóm</th>                                                
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="bangvaitroBody">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                     
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Xóa</button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="cap_nhat_thong_tin_user">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/dist/jquery/jquery37.js"></script>
    <script src="/dist/jquery/nhansu_js/nhansu.js"></script>
@endsection
