<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Session;

class controller_dangnhap extends Controller
{
    public function trang_dang_nhap()
    {
        $this->chuyen_du_lieu();
        return view('dangnhap/trang_dang_nhap');
    }   
    public function dang_nhap(Request $r)
    {
        $currentDate = Carbon::now();
        $isFirstDayOfMonth = $currentDate->format('d') === '30';
        if($isFirstDayOfMonth)
        {
            $this->chuyen_du_lieu();
        }
        if (Auth::attempt(['manhansu' => $r->input('username'), 'password' => $r->input('password')])) {
            $userid = Auth::user()->id;
            $username = Auth::user()->manhansu;
            $kt = DB::table('nhansu')
                ->select('tennhansu', 'hinhanh')
                ->where('manhansu', $username)
                ->first();
            Session::put('userid', $userid);
            Session::put('username', $kt->tennhansu);
            Session::put('avarta', $kt->hinhanh);
            return view('admin/index');
        }
        return redirect('trang_dang_nhap');
    }
    public function trang_cap_tai_khoan()
    {
        $dsnhansu = DB::table('nhansu')
            ->select('nhansu.manhansu', 'tennhansu')
            ->get();
        $dstaikhoan = DB::table('nhansu')
            ->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->join('users', 'users.manhansu', '=', 'nhansu.manhansu')
            ->select('nhomlamviec.manhom', 'nhansu.manhansu', 'tennhom', 'tennhansu', 'role', 'users.id')
            ->groupBy('nhomlamviec.manhom', 'tennhom', 'nhansu.manhansu', 'tennhansu', 'role', 'users.id')
            ->orderBy('role')
            ->get();
        return view('dangnhap/trang_cap_tai_khoan', [
            'dstaikhoan' => $dstaikhoan,
            'dsnhansu' => $dsnhansu
        ]);
    }
    public function them_tai_khoan(Request $r)
    {
        $manhansu = $r->manhansu;
        $matkhau = $r->matkhau;
        $vaitro = $r->vaitro;
        $ktr =  DB::table('users')
            ->select('users.manhansu')
            ->where('manhansu', $manhansu)
            ->first();
        if ($ktr) {
            return response()->json(['success' => true, 'message' => 'Tài khoản đã tồn tại!']);
        }
        DB::table('users')->insert([
            'manhansu' => $manhansu,
            'password' => bcrypt($matkhau),
            'role' => $vaitro,
        ]);
        return response()->json(['success' => true, 'message' => 'Thêm thành công']);
    }
    public function lay_thong_tin_user(Request $r)
    {
        $id = $r->id;
        $tt = DB::table('nhansu')
            ->join('users', 'users.manhansu', '=', 'nhansu.manhansu')
            ->select('nhansu.manhansu', 'tennhansu', 'role', 'users.id')
            ->where('users.id', $id)
            ->first();
        $ds = [];
        $dsnhom = [];
        if ($tt->role == 'hoso') {
            $ds = DB::table('users')
                ->join('nhomquanly', 'nhomquanly.userid', '=', 'users.id')
                ->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhomquanly.manhom')
                ->select('nhomquanly.manhom', 'tennhom')
                ->where('users.id', $id)
                ->get();
            $dsnhom = DB::table('nhomlamviec')
                ->select('nhomlamviec.manhom', 'tennhom')
                ->whereNotIn('manhom', function ($query) use ($id) {
                    $query->select('manhom')
                        ->from('nhomquanly')
                        ->where('userid', $id);
                })
                ->get();
            if (!$dsnhom) {
                $dsnhom = DB::table('nhomlamviec')
                    ->select('nhomlamviec.manhom', 'tennhom')
                    ->get();
            }
        }
        return response()->json([
            'tt' => $tt,
            'ds' => $ds,
            'dsnhom' => $dsnhom,
        ]);
    }
    public function cap_nhat_thong_tin_user(Request $r)
    {
        $manhansu = $r->manhansu;
        $vaitro = $r->vaitro;
        $matkhau = $r->matkhau;
        $dsnhom = $r->dsnhom;
        if ($matkhau != "") {
            DB::table('users')
                ->where('manhansu', '=', $manhansu)
                ->update([
                    'password' => bcrypt($matkhau),
                ]);
        }
        DB::table('users')
            ->where('manhansu', '=', $manhansu)
            ->update([
                'role' => $vaitro,
            ]);
        if ($vaitro == "hoso") {
            $lay_id = DB::table('users')
                ->select('id')
                ->where('manhansu', $manhansu)
                ->first();
            if ($dsnhom) {
                DB::table('nhomquanly')
                    ->where('userid', $lay_id->id)
                    ->whereNotIn('manhom', $dsnhom)
                    ->delete();
                foreach ($dsnhom as $manhom) {
                    $ktr =  DB::table('nhomquanly')
                        ->select('userid')
                        ->where('userid', $lay_id->id)
                        ->where('manhom', $manhom)
                        ->first();
                    if (!$ktr) {
                        DB::table('nhomquanly')->insert(
                            ['userid' =>  $lay_id->id, 'manhom' => $manhom]
                        );
                    }
                }
            } else {
                DB::table('nhomquanly')
                    ->where('userid', $lay_id->id)
                    ->delete();
            }
        }
        return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
    }
    public function dang_xuat(Request $r)
    {
        Auth::logout();
        return response()->json(['success' => true, 'message' => 'thành công']);
    }
    public function trang_logs()
    {
        $dslog = DB::table('logs_diemdanh')
            ->join('nhansu', 'nhansu.manhansu', '=', 'logs_diemdanh.hoso')
            ->select('tennhansu', 'maluong', 'updated_at', 'ip', 'hoso', 'thoigianvao', 'thoigianra', 'laplichvao', 'laplichra', 'logs_diemdanh.manhansu', 'ngaydiemdanh')
            ->orderBy('ngaydiemdanh', 'desc')
            ->get();
        return view(
            'admin/trang_logs',
            [
                'dslog' => $dslog,
            ]
        );
    }
    public function chuyen_du_lieu()
    {
        $currentDate = Carbon::now();
        $twoMonthsAgo = $currentDate->subMonths(2);
        $formattedTwoMonthsAgo = $twoMonthsAgo->format('Y-m-d');
        $ds = DB::table('diemdanh1')
            ->where(DB::raw("STR_TO_DATE(ngaydiemdanh, '%d-%m-%Y')"), '<', $formattedTwoMonthsAgo)
            ->get();
        foreach ($ds as $item) {
            DB::table('diemdanh_old')->insert([
                'madiemdanh' => $item->madiemdanh,
                'manhansu' => $item->manhansu,
                'ngaydiemdanh' => $item->ngaydiemdanh,
                'laplichvao' => $item->laplichvao,
                'laplichra' => $item->laplichra,
                'thoigianvao' => $item->thoigianvao,
                'thoigianra' => $item->thoigianra,
                'thoigiannghi' => $item->thoigiannghi,
                'tonggiolam' => $item->tonggiolam,
                'tonglaplich' => $item->tonglaplich,
                'tonggiolamthucte' => $item->tonggiolamthucte,
                'thu' => $item->thu,
                'maluong' => $item->maluong,
                'sophuttre' => $item->sophuttre,
                'hoso' => $item->hoso,
                'ghichu' => $item->ghichu,
            ]);
        }
        DB::table('diemdanh1')
            ->where(DB::raw("STR_TO_DATE(ngaydiemdanh, '%d-%m-%Y')"), '<', $formattedTwoMonthsAgo)
            ->delete();
    }
}
