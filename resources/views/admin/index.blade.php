@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h4 class="fw-normal text-primary m-0">Trang chủ <i class="far fa-question-circle"></i></h4>
        <div class="d-flex ms-auto">
            <div class="d-flex align-items-center justify-content-center p-2">
                <button class="btn btn-primary" style="visibility: hidden">     
                    gg             
                </button>
            </div>
        </div>
        {{-- <div class="d-flex ms-auto">
            <div class="input-custom ms-2">
                <div>
                    <label class="form-label text-secondary">Vật liệu</label>
                    <input type="text" class="form-control" value="26118FN000_04">
                </div>
                <div class="form-delete"><i class="fas fa-times"></i></div>
            </div>
            <div class="input-custom ms-2">
                <div>
                    <label class="form-label text-secondary">Mã lot</label>
                    <input type="text" class="form-control" value="26118FN000_04">
                </div>
                <div class="form-delete"><i class="fas fa-times"></i></div>
            </div>
            <div class="input-custom ms-2">
                <div>
                    <label class="form-label text-secondary">Vật liệu</label>
                    <select class="form-select">
                        <option selected>all</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="form-delete"><i class="fas fa-times"></i></div>
            </div>
            <div class="d-flex align-items-center justify-content-center p-2">
                <button class="btn btn-primary"><i class="fas fa-download"></i> Tải báo cáo</button>
            </div>
            <div class="d-flex align-items-center justify-content-center p-2">
                <button class="btn btn-primary"><i class="fas fa-list"></i> Sản phẩm mẫu</button>
            </div>
            <div class="d-flex align-items-center justify-content-center p-2 border-start">
                <button class="btn"><i class="fas fa-redo"></i></button>
            </div>
        </div> --}}
    </div>
    <div class="p-3">
        {{-- <div class="bg-white p-3">
            <table id="example" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tiger Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011-04-25</td>
                        <td>$320,800</td>
                    </tr>
                    <tr>
                        <td>Garrett Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                    </tr>
                    <tr>
                        <td>Ashton Cox</td>
                        <td>Junior Technical Author</td>
                        <td>San Francisco</td>
                        <td>66</td>
                        <td>2009-01-12</td>
                        <td>$86,000</td>
                    </tr>
                    <tr>
                        <td>Cedric Kelly</td>
                        <td>Senior Javascript Developer</td>
                        <td>Edinburgh</td>
                        <td>22</td>
                        <td>2012-03-29</td>
                        <td>$433,060</td>
                    </tr>
                    <tr>
                        <td>Airi Satou</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>33</td>
                        <td>2008-11-28</td>
                        <td>$162,700</td>
                    </tr>
                    <tr>
                        <td>Brielle Williamson</td>
                        <td>Integration Specialist</td>
                        <td>New York</td>
                        <td>61</td>
                        <td>2012-12-02</td>
                        <td>$372,000</td>
                    </tr>
                    <tr>
                        <td>Herrod Chandler</td>
                        <td>Sales Assistant</td>
                        <td>San Francisco</td>
                        <td>59</td>
                        <td>2012-08-06</td>
                        <td>$137,500</td>
                    </tr>
                    <tr>
                        <td>Rhona Davidson</td>
                        <td>Integration Specialist</td>
                        <td>Tokyo</td>
                        <td>55</td>
                        <td>2010-10-14</td>
                        <td>$327,900</td>
                    </tr>
                    <tr>
                        <td>Colleen Hurst</td>
                        <td>Javascript Developer</td>
                        <td>San Francisco</td>
                        <td>39</td>
                        <td>2009-09-15</td>
                        <td>$205,500</td>
                    </tr>
                    <tr>
                        <td>Sonya Frost</td>
                        <td>Software Engineer</td>
                        <td>Edinburgh</td>
                        <td>23</td>
                        <td>2008-12-13</td>
                        <td>$103,600</td>
                    </tr>
                    <tr>
                        <td>Jena Gaines</td>
                        <td>Office Manager</td>
                        <td>London</td>
                        <td>30</td>
                        <td>2008-12-19</td>
                        <td>$90,560</td>
                    </tr>
                    <tr>
                        <td>Quinn Flynn</td>
                        <td>Support Lead</td>
                        <td>Edinburgh</td>
                        <td>22</td>
                        <td>2013-03-03</td>
                        <td>$342,000</td>
                    </tr>
                    <tr>
                        <td>Charde Marshall</td>
                        <td>Regional Director</td>
                        <td>San Francisco</td>
                        <td>36</td>
                        <td>2008-10-16</td>
                        <td>$470,600</td>
                    </tr>
                    <tr>
                        <td>Haley Kennedy</td>
                        <td>Senior Marketing Designer</td>
                        <td>London</td>
                        <td>43</td>
                        <td>2012-12-18</td>
                        <td>$313,500</td>
                    </tr>
                    <tr>
                        <td>Tatyana Fitzpatrick</td>
                        <td>Regional Director</td>
                        <td>London</td>
                        <td>19</td>
                        <td>2010-03-17</td>
                        <td>$385,750</td>
                    </tr>
                </tbody>
                <!-- <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                    </tr>
                </tfoot> -->
            </table>
        </div> --}}
    </div>

    {{-- <div class="modal" tabindex="-1" id="exampleModal">
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
    </div> --}}

    <!-- Button trigger modal -->
    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button>  --}}
    {{-- <script>
        const tabList = $('.nav-tabs .nav-link');
        tabList.each(function (index, element) {
            $(element).on('click', function () {
                $('.nav-tabs .nav-link.active').removeClass('active');
                $(element).addClass('active');
                $('.tab-content.active').removeClass('active');
                var id = $(this).data('id');
                $("#" + id).addClass('active');
            });
        });
    </script> --}}
@endsection
