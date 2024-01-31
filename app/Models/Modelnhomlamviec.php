<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;
class Modelnhomlamviec extends Model
{
    use HasFactory;
    protected $primaryKey = 'manhom';
    protected $table = 'nhomlamviec';
    protected $fillable = [
        'manhom',
        'tennhom',        
        
    ];
    public function getdiemdanhnhanvien($tag, $thang, $nam)
    {
        $startOfMonth = new DateTime("$nam-$thang-01");       
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->modify('last day of this month');       
        $dslaplich = DB::table('diemdanh1')        
            ->select('sumlaplich', 'ngaydiemdanh', 'ghichu')
            ->where('manhansu', $tag)
            ->whereBetween('ngaydiemdanh', [$startOfMonth, $endOfMonth])      
            ->orderBy('ngaydiemdanh', 'asc')
            ->distinct()
            ->get();       
        return $dslaplich;
    }
    public function getdiemdanh($tag, $thang, $nam)
    {
        $startOfMonth = new DateTime("$nam-$thang-01");       
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->modify('last day of this month');       
        $dslaplich = DB::table('diemdanh1')        
        ->select('sum', 'ngaydiemdanh', 'ghichu')
        ->where('manhansu', $tag)
        ->whereBetween('ngaydiemdanh', [$startOfMonth, $endOfMonth])      
        ->orderBy('ngaydiemdanh', 'asc')
        ->distinct()
        ->get();       
        return $dslaplich;
    }
}
