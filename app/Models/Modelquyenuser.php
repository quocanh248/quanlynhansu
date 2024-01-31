<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Modelquyenuser extends Model
{
    use HasFactory;

    protected $table = 'quyenuser';
    protected $fillable = [
        'maquyen',
        'id',

    ];
    public function getquyenuser($manhanvien)
    {
        $gv = DB::table('nhansu')
            ->crossJoin('users')
            ->crossJoin('quyenuser')
            ->crossJoin('quyen')
            ->select('quyen.maquyen', 'tenquyen')
            ->where('nhansu.manhanvien', '=', DB::raw('users.manhanvien'))
            ->where('users.id', '=', DB::raw('quyenuser.id'))
            ->where('quyenuser.maquyen', '=', DB::raw('quyen.maquyen'))
            ->where('nhansu.manhanvien', '=', $manhanvien)
            ->get();



        return $gv;
    }
    public function getdsdiemdanh($manhanvien, $ngaydautuan, $ngaycuoituan)
    {
        $dslaplich = DB::table('diemdanh1')        
        ->select('tag', 'thoigianvao', 'thoigianra', 'ngaydiemdanh', 'laplichvao', 'laplichra', 'thu')
        ->where('tag', $manhanvien)
        ->whereBetween('ngaydiemdanh', [$ngaydautuan, $ngaycuoituan])      
        ->orderBy('thoigianvao', 'desc')
        ->distinct()
        ->get();
        return $dslaplich;
    }
    public function getdsdiemdanhngay($manhanvien, $ngaydautuan, $ngaycuoituan)
    {
        $dslaplich = DB::table('diemdanh1')        
        ->select('manhansu', 'thoigianvao', 'thoigianra', 'ngaydiemdanh', 'laplichvao', 'laplichra', 'thu', 'maluong')
        ->where('manhansu', $manhanvien)
        ->where('ngaydiemdanh', $ngaydautuan)     
        ->where('thu', $ngaycuoituan)      
        ->orderBy('thoigianvao', 'desc')
        ->distinct()
        ->get();
        // dd($dslaplich);
        return $dslaplich;
    }
    public function laydiemdanhnhansu($ngay, $thang, $nam, $mnv)
    {     
        if(strlen($thang) == 1)
        {
            $thang = "0".$thang;
        }   
        $ntn = $ngay.'-'.$thang.'-'.$nam;
        $dslaplich = DB::table('diemdanh1')        
            ->select('madiemdanh',  'manhansu', 'thoigianvao', 'thoigianra', 'ngaydiemdanh', 'laplichvao', 'laplichra', 'thu', 'maluong')
            ->where('manhansu', $mnv)
            ->where('ngaydiemdanh', $ntn)      
            ->orderBy('thoigianvao', 'desc')
            ->first();
        // dd($ntn);    
               
        return $dslaplich;     
    }
}
