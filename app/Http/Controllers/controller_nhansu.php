<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class controller_nhansu extends Controller
{
    public function danh_sach_nhan_su($check)
    {
        $dsnhom = DB::table('nhomlamviec')->get();
        $role = Auth::user()->role;
        $userid = Auth::user()->id;
        $an_nghi_viec = $check;
        $dsnhansu = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->leftjoin('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
            ->select('nhomlamviec.manhom', 'nhansu.manhansu', 'tennhom', 'tennhansu', 'tag', 'nhansu.trangthai');
        if ($role == "hoso") {
            $dsnhansu->where('userid', $userid);
        }         
        if ($an_nghi_viec == "true") {
            $dsnhansu->where('nhansu.trangthai', "dang_lam");
        }
        $dsnhansu->groupBy('nhomlamviec.manhom', 'tennhom', 'nhansu.manhansu', 'tennhansu', 'tag', 'nhansu.trangthai')
            ->orderBy('manhom');      
        $dsnhansu = $dsnhansu->get();    
        return view('nhansu/danh_sach', [
            'dsnhansu' => $dsnhansu,
            'dsnhom' => $dsnhom,
            'an_nghi_viec' => $an_nghi_viec
        ]);
    }
    public function thong_tin_ca_nhan($manhansu)
    {
        $tt = DB::table('nhansu')
            ->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->where('manhansu', $manhansu)
            ->first();

        $dsnhom = DB::table('nhomlamviec')
            ->select('nhomlamviec.manhom', 'tennhom')
            ->whereNotIn('manhom', function ($query) use ($manhansu) {
                $query->select('manhom')
                    ->from('nhansu')
                    ->where('manhansu', $manhansu);
            })
            ->get();
        return response()->json([
            'tt' => $tt,
            'dsnhom' => $dsnhom,
        ]);
    }
    public function cap_nhat_thong_tin_ca_nhan(Request $r)
    {
        $manhansu = $r->trangchu_manhansu;
        $tennhansu = $r->trangchu_tennhansu;
        $ngaysinh = $r->trangchu_ngaysinh;
        $gioitinh = $r->trangchu_gioitinh;
        $nhomlamviec = $r->trangchu_nhomlamviec;
        $noilamviec = $r->trangchu_noilamviec;
        $sdt = $r->trangchu_sdt;
        $diachi = $r->trangchu_diachi;
        $trinhdo = $r->trangchu_trinhdo;
        $dantoc = $r->trangchu_dantoc;
        $tongiao = $r->trangchu_tongiao;
        $cccd = $r->trangchu_cccd;
        $ngaycap = $r->trangchu_ngaycap;
        $noicap = $r->trangchu_noicap;
        $ngaybatdaulam = $r->trangchu_ngaybatdaulam;
        $ngaykyhopdong = $r->trangchu_ngaykyhopdong;
        $hinhanh = $r->trangchu_hinhanh;
        $trangthai = $r->trangchu_trangthai;
        if (!$trangthai) {
            $trangthai = "dang_lam";
        }
        DB::table('nhansu')
            ->where('manhansu', '=', $manhansu)
            ->update([
                'tennhansu' => $tennhansu,
                'ngaysinh' => $ngaysinh,
                'gioitinh' => $gioitinh,
                'manhom' => $nhomlamviec,
                'nhom' => $noilamviec,
                'sdt' => $sdt,
                'diachi' => $diachi,
                'trinhdo' => $trinhdo,
                'dantoc' => $dantoc,
                'tongiao' => $tongiao,
                'sochungminh' => $cccd,
                'ngaycap' => $ngaycap,
                'noicap' => $noicap,
                'ngaybatdaulam' => $ngaybatdaulam,
                'ngaykyhopdong' => $ngaykyhopdong,
                'hinhanh' => $hinhanh,
                'trangthai' => $trangthai,
            ]);
        return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
    }
    public function them_nhan_su_moi(Request $r)
    {
        $file_path = "";
        if ($r->filename == "") {
            $file_path = '/images/user.png';
        } else {
            $file_path = $r->filename;
        }
        $dsnhom = DB::table('nhomlamviec')->get();
        $manhansu = $r->danhsach_manhansu;
        if (strlen($manhansu) == 7) {
            $ktr =  DB::table('nhansu')
                ->select('manhansu')
                ->where('manhansu', $manhansu)
                ->first();
            if (!$ktr) {
                $tennhansu = $r->danhsach_tennhansu;
                $ngaysinh = $r->danhsach_ngaysinh;
                $gioitinh = $r->danhsach_gioitinh;
                $nhomlamviec = $r->danhsach_nhomlamviec;
                $noilamviec = $r->danhsach_noilamviec;
                $sdt = $r->danhsach_sdt;
                $diachi = $r->danhsach_diachi;
                $trinhdo = $r->danhsach_trinhdo;
                $dantoc = $r->danhsach_dantoc;
                $tongiao = $r->danhsach_tongiao;
                $cccd = $r->danhsach_cccd;
                $ngaycap = $r->danhsach_ngaycap;
                $noicap = $r->danhsach_noicap;
                $ngaybatdaulam = $r->danhsach_ngaybatdaulam;
                $ngaykyhopdong = $r->danhsach_ngaykyhopdong;
                DB::table('nhansu')
                    ->insert([
                        'manhansu' => $manhansu,
                        'tennhansu' => $tennhansu,
                        'ngaysinh' => $ngaysinh,
                        'gioitinh' => $gioitinh,
                        'manhom' => $nhomlamviec,
                        'nhom' => $noilamviec,
                        'calamviec' => "Mặc định",
                        'sdt' => $sdt,
                        'diachi' => $diachi,
                        'trinhdo' => $trinhdo,
                        'dantoc' => $dantoc,
                        'tongiao' => $tongiao,
                        'sochungminh' => $cccd,
                        'ngaycap' => $ngaycap,
                        'noicap' => $noicap,
                        'ngaybatdaulam' => $ngaybatdaulam,
                        'ngaykyhopdong' => $ngaykyhopdong,
                        'hinhanh' => $file_path,
                    ]);
            }
        }
        $dsnhansu =  DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('nhomlamviec.manhom', 'nhansu.manhansu', 'tennhom', 'tennhansu', 'tag')
            ->groupBy('nhomlamviec.manhom', 'tennhom', 'nhansu.manhansu', 'tennhansu', 'tag')
            ->orderBy('manhom')
            ->get();
        return view('nhansu/danh_sach', [
            'dsnhansu' => $dsnhansu,
            'dsnhom' => $dsnhom,
        ]);
    }
    public function uploadImage(Request $r)
    {
        if ($r->file('tmpFile')) {
            $file = $r->file('tmpFile');
            // $filename = date('YmdHis') . ' - ' . $file->getClientOriginalName();
            $filename = $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            return response()->json('/images/' . $filename);
        }
    }
    public function them_tag(Request $r)
    {
        $listnhansu = $r->listnhansu;
        $tag = $r->tag;
        for ($j = 0; $j < count($listnhansu); $j++) {
            DB::table('nhansu')
                ->where('manhansu', '=',  $listnhansu[$j])
                ->update([
                    'nhom' => $tag,
                ]);
        }
        return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
    }
}
