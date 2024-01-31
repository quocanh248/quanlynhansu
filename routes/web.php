<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controller_dangnhap;
use App\Http\Controllers\controller_nhansu;
use App\Http\Controllers\controller_chamcong;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Đăng nhập
Route::get('/', function () {
    return view('dangnhap/trang_dang_nhap');
});
Route::get('/trang_dang_nhap', [controller_dangnhap::class, 'trang_dang_nhap'])->name('login');
Route::post('/dang_nhap', [controller_dangnhap::class, 'dang_nhap']);

Route::get('/home', function () {
    return view('admin/trang_index_loi');
})->name('home');
//Điểm danh quẹt thẻ
Route::get('/diem_danh', [controller_chamcong::class, 'diem_danh']);

Route::middleware('auth')->group(function () {
    Route::group(['middleware' => 'check-access-admin'], function () {
        //admin        
        //Quản trị
        Route::get('/trang_cap_tai_khoan', [controller_dangnhap::class, 'trang_cap_tai_khoan']);        
        Route::post('/them_tai_khoan', [controller_dangnhap::class, 'them_tai_khoan']);
        Route::post('/lay_thong_tin_user', [controller_dangnhap::class, 'lay_thong_tin_user']);
        Route::post('/cap_nhat_thong_tin_user', [controller_dangnhap::class, 'cap_nhat_thong_tin_user']);
    });
    Route::group(['middleware' => 'check-access-zenbee'], function () {
        Route::get('/trang_chot_cong', [controller_chamcong::class, 'trang_chot_cong']);
        Route::get('/chot_cong', [controller_chamcong::class, 'chot_cong']);
        Route::post('/cap_nhat_chot_cong', [controller_chamcong::class, 'cap_nhat_chot_cong']);
        Route::get('/trang_xuat_du_lieu_lap_lich', [controller_chamcong::class, 'trang_xuat_du_lieu_lap_lich']);
        Route::get('/xuat_lap_lich', [controller_chamcong::class, 'xuat_lap_lich']);
        Route::post('/them_nhan_su_moi', [controller_nhansu::class, 'them_nhan_su_moi']);
        Route::post('/upload_hinhanh/image', [controller_nhansu::class, 'uploadImage']);
    });
    Route::middleware('checkRoleHoso')->group(function () {
        //nhân sự
        Route::get('/danh_sach_nhan_su/{true}', [controller_nhansu::class, 'danh_sach_nhan_su']);
        Route::get('/thong_tin_ca_nhan/{manhansu}', [controller_nhansu::class, 'thong_tin_ca_nhan']);   
        Route::post('/cap_nhat_thong_tin_ca_nhan', [controller_nhansu::class, 'cap_nhat_thong_tin_ca_nhan']);   
        Route::post('/them_tag', [controller_nhansu::class, 'them_tag']);     
        //chấm công
        Route::get('/trang_lap_lich', [controller_chamcong::class, 'trang_lap_lich']);
        Route::get('/tim_nhan_su_theo_nhom', [controller_chamcong::class, 'tim_nhan_su_theo_nhom']);
        Route::get('/laythongtinca/{tenca}', [controller_chamcong::class, 'laythongtinca']);
        Route::post('/laplich', [controller_chamcong::class, 'laplich']);
        Route::get('/danh_sach_lap_lich', [controller_chamcong::class, 'danh_sach_lap_lich']);
        Route::get('/lay_danh_sach_lap_lich_theo_nhom', [controller_chamcong::class, 'lay_danh_sach_lap_lich_theo_nhom']);
        Route::get('/danh_sach_diem_danh', [controller_chamcong::class, 'danh_sach_diem_danh']);
        Route::get('/lay_danh_sach_diem_danh_theo_nhan_su', [controller_chamcong::class, 'lay_danh_sach_diem_danh_theo_nhan_su']);
        Route::post('/lay_thong_tin_diem_danh', [controller_chamcong::class, 'lay_thong_tin_diem_danh']);
        Route::post('/cap_nhat_thong_tin_diem_danh', [controller_chamcong::class, 'cap_nhat_thong_tin_diem_danh']);
        Route::post('/lay_thong_tin_xin_nghi', [controller_chamcong::class, 'lay_thong_tin_xin_nghi']);
        Route::post('/lap_lich_nghi', [controller_chamcong::class, 'lap_lich_nghi']);
        Route::post('/huy_lich_nghi', [controller_chamcong::class, 'huy_lich_nghi']);
        Route::get('/trang_lap_lich_nhom/{tennhom}', [controller_chamcong::class, 'trang_lap_lich_nhom']);   
        
       
        Route::get('/trang_logs', [controller_dangnhap::class, 'trang_logs']);
        Route::post('/dang_xuat', [controller_dangnhap::class, 'dang_xuat']);        
    });
});
