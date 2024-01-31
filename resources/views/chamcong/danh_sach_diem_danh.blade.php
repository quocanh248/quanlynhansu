@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Danh sách điểm danh<i class="far fa-question-circle"></i></h5>
        <div class="d-flex ms-auto">
            <form action="/lay_danh_sach_diem_danh_theo_nhan_su" method="get" style="display: contents">
                @csrf
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">{{$label}}</label>
                        <input type="search" class="form-control" name="manhansu" value="{{ $manhansu }}" required
                            autocomplete="off" list="list_nhan_su">
                        <datalist id="list_nhan_su">
                            @foreach ($dsnhansu as $item)
                                <option value="{{ $item->manhansu }}">{{ $item->tennhansu }}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Chọn tháng</label>
                        <input type="month" class="form-control" name="thang" value="{{ $month }}">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center p-1">
                    <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i>Tìm</button>
                </div>
            </form>
            {{-- <div class="d-flex align-items-center justify-content-center p-1">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fa-regular fa-calendar-days"></i>
                    Chọn ngày
                </button>
            </div> --}}
            <div class="d-flex align-items-center justify-content-center p-1 border-start">
                <button class="btn" id="reload"><i class="fas fa-redo"></i></button>
            </div>
        </div>
    </div>
    <div class="p-3">
        <div class="bg-white">
            <table class="table table-bordered table-calendar">
                <thead>
                    <tr class="text-center">
                        <th class="w-70p">Chủ nhật</th>
                        <th class="w-70p">Thứ hai</th>
                        <th class="w-70p">Thứ ba</th>
                        <th class="w-70p">Thứ tư</th>
                        <th class="w-70p">Thứ năm</th>
                        <th class="w-70p">Thứ sáu</th>
                        <th class="w-70p">Thứ bảy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calendarData as $week)
                        <tr>
                            @for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++)
                                @php
                                    $dayData = collect($week)->first(function ($day) use ($dayOfWeek) {
                                        return $day['date']->dayOfWeek === $dayOfWeek - 1;
                                    });
                                    $classml = "";                                   
                                    if(isset($dayData['maluong']) && $dayData['maluong'] != null)
                                    {                                       
                                        $classml = "td-maluong";
                                    }
                                @endphp
                                <td class="{{$classml}}">
                                    @if ($dayData)
                                        @php
                                            $id = $manhansu . '-' . $dayData['ngaydiemdanh'];
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="show-hover text-primary">
                                                {{-- <a href="#" class="text-primary text-decoration-none"><i
                                                        class="fas fa-calendar-day"></i></a> --}}
                                                <button class="text-danger bt-none_1" value="{{ $id }}"
                                                    data-bs-toggle="modal" data-bs-target="#modal_nghiphep">
                                                    <i class="fas fa-calendar-times"></i>
                                                </button>                                              
                                            </div>
                                            <span class="fw-bold">{{ $dayData['day'] }}</span>
                                            <span class="hide-hover">{{ $nhom }}</span>
                                            @if($classml == "")
                                                <div class="show-hover">
                                                    <button class="text-primary bt-none" value="{{ $id }}"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            @if ($dayData['thoigianvao'] != '' || $dayData['laplichvao'])
                                                <div class="w-50 text-secondary border-end pe-3">
                                                    <div>{{ $dayData['laplichvao'] }}</div>
                                                    <div class="text-end text-danger"><b>{{ $dayData['tonglaplich'] }}</b></div>
                                                    <div>{{ $dayData['laplichra'] }}</div>
                                                </div>
                                                <div class="w-50 text-primary border-start ps-3">
                                                    @php
                                                        $classvao = "";
                                                        $classra = "";
                                                        if($dayData['thoigianvao'] > $dayData['laplichvao'])
                                                        {
                                                            $classvao = "text-danger";
                                                        }
                                                        if($dayData['thoigianra'] < $dayData['laplichra'])
                                                        {
                                                            $classra = "text-danger";
                                                        }
                                                    @endphp
                                                    <div class="text-end {{$classvao}}">{{ $dayData['thoigianvao'] }}</div>
                                                    <div class="text-success"><b>{{ $dayData['tonggiolamthucte'] }}</b>
                                                    </div>
                                                    <div class="text-end {{$classra}}">{{ $dayData['thoigianra'] }}</div>
                                                </div>
                                            @else
                                                <div class="w-50 text-danger border-end pe-3 ">
                                                    <div class="color-none">0</div>
                                                    <div class="text-end color-none"><b>0</b></div>
                                                    <div class="color-none">0</div>
                                                </div>
                                                <div class="w-50 text-primary border-start ps-3 color-none">
                                                    <div class="text-end color-none">0</div>
                                                    <div class="text-success"><b class="color-none">0</b></div>
                                                    <div class="text-end color-none">0</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="modal_nghiphep">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lập lịch nghỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mã nhân sự (*)</label>
                                    <input type="text" class="form-control" id="manhansu_np" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên nhân sự (*)</label>
                                    <input type="text" class="form-control" id="tennhansu_np" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>                  
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mã tính lương:</label>
                                    <select name="" id="loaingaynghi" class="form-select">
                                        <option value="NV">[NV] Nghỉ ngưng việc hưởng 70% lương</option>
                                        <option value="P">[P] Nghỉ phép năm hưởng 100% mức lương cơ bản (ngày nghỉ P tính
                                            trọn 1 ngày không tính số lẻ) </option>
                                        <option value="Ô">[Ô] Nghỉ Ô không hưởng lương</option>
                                        <option value="O">[O] Nghỉ không phép</option>
                                        <option value="N">[N] Nghỉ không lương</option>
                                        <option value="L">[L] Nghỉ Lễ (các ngày Lễ theo quy định nhà nước)</option>
                                        <option value="CĐ">[CĐ] Nghỉ đám cưới, đám tang năm hưởng 100% mức lương cơ bản
                                        </option>
                                        <option value="TS">[TS] Nghỉ thai sản không hưởng lương</option>
                                        <option value="R">[R] Nghỉ việc riêng không hưởng lương</option>
                                        <option value="C">[Co] Nghỉ con ốm không hưởng lương</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lý do:</label>
                                    <input type="text" class="form-control" id="lydo" autocomplete="off">
                                </div>
                            </div>                            
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="huy_lich_nghi" class="btn btn-danger">Hủy lịch</button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="lap_lich_nghi">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="exampleModal">
        <div class="modal-dialog" style="width: 80%; max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin điểm danh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="basic" class="tab-content active">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mã nhân sự (*)</label>
                                    <input type="text" class="form-control" id="manhansu" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên nhân sự (*)</label>
                                    <input type="text" class="form-control" id="tennhansu" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
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
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ngày</label>
                                    <input type="text" class="form-control" id="ngaydiemdanh" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Điểm danh (Giờ vào)</label>
                                    <input type="time" class="form-control" id="diemdanhvao">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Điểm danh (Giờ ra)</label>
                                    <input type="time" class="form-control" id="diemdanhra">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-danger">Xóa</button> --}}
                    <div class="ms-auto">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="cap_nhat">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/dist/jquery/jquery37.js"></script>
    <script src="/dist/jquery/chamcong_js/diemdanh.js"></script>
@endsection
