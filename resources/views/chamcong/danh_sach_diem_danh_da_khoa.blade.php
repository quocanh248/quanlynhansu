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
                                                <button class="text-danger bt-none_1" disabled>
                                                    <i class="fas fa-calendar-times"></i>
                                                </button>                                              
                                            </div>
                                            <span class="fw-bold">{{ $dayData['day'] }}</span>
                                            <span class="hide-hover">{{ $nhom }}</span>
                                            @if($classml == "")
                                                <div class="show-hover">
                                                    <button class="text-primary bt-none" disabled>
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
    
@endsection
