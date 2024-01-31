<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use DateTime;

class Modelnhansu extends Model
{
    use HasFactory;
    protected $primaryKey = 'manhansu';
    protected $table = 'nhansu';
    protected $fillable = [
        'manhansu',
        'tennhansu',
        'ngaysinh',
        'manhom',
        'calamviec',
        'tag',
        'sdt',
        'sochungminh',
        'diachi',
        'trinhdo',
        'dantoc',
        'tongiao',
        'ngaycap',
        'noicap',
        'ngaybatdaulam',
        'ngaykyhopdong',
        'ghichu',
        'gioitinh',

    ];
    public function getvaitro($userid)
    {
        $dschitiet = DB::table('quyen')
            ->select('tenquyen')
            ->join('quyen_user', 'quyen_user.maquyen', '=', 'quyen.maquyen')
            ->where('quyen_user.id', $userid)
            ->get();
        return $dschitiet;
    }
    public function getlichsu($tag, $thang, $nam)
    {
        $startOfMonth = new DateTime("$nam-$thang-01");
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->modify('last day of this month');
        $dslaplich = DB::table('lichsuimpot')
            ->select('ngaytao', 'ghichu')
            ->where('manhansu', $tag)
            ->whereBetween('update_at', [$startOfMonth, $endOfMonth])           
            ->distinct()
            ->get();       
        return $dslaplich;
    }
}
