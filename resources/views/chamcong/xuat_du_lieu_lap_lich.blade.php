@extends('admin/trang_chu')

@section('content')
    <div class="d-flex align-items-center bg-white px-4 py-1">
        <h5 class="fw-normal text-primary m-0">Xuất dữ liệu <i class="far fa-question-circle"></i></h5>
        <div class="d-flex ms-auto">
            <form action="/xuat_lap_lich" method="get" style="display: contents">              
                <div class="input-custom ms-2">
                    <div>
                        <label class="form-label text-secondary">Chọn tháng</label>
                        <input type="month" class="form-control" name="thang" value="{{ $month }}">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center p-1">
                    <button class="btn btn-primary" name="laplich" value="laplich">
                        <i class="fa-solid fa-file-export"></i>
                        Xuất lập lịch 
                    </button>
                </div>
                <div class="d-flex align-items-center justify-content-center p-1">
                    <button class="btn btn-success"  name="diemdanh" value="diemdanh">
                        <i class="fa-solid fa-file-export"></i>
                        Xuất điểm danh 
                    </button>
                </div>
            </form>
            <div class="d-flex align-items-center justify-content-center p-1 border-start">
                <button class="btn" id="reload"><i class="fas fa-redo"></i></button>
            </div>
        </div>
    </div>
    <div class="p-3">
        
    </div>   
@endsection
