<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use DateTime;

class modeldiemdanh extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'diemdanh';
    protected $fillable = [
        'id',
        'gio',
        'xuong',
        'manhanvien'
    ];
    public function laybaocom($ngay)
    {
        $ngayParts = explode('-', str_replace('=', '-', $ngay));
        $formattedNgay = sprintf('%s-%s-%s', $ngayParts[2], $ngayParts[1], $ngayParts[0]);
        $soluongcom = DB::table('soluongcom')
            ->select(DB::raw('DATE(created_at) AS date, mansang1 + manchieu1 AS man1, chaysang1 + chaychieu1 AS chay1, chaysang2 + chaychieu2 AS chay2, mansang2 + manchieu2 AS man2, diemdanh'))
            ->whereDate("created_at", $formattedNgay)
            ->first();
        return $soluongcom;
    }
    public function laycommanxuong2($ngay)
    {
        $commands = DB::table('commands')
            ->selectRaw('COUNT(*) AS total_commands')
            ->where(function ($query) {
                $query->where('ip', '30.30.30.42')
                    ->orWhere('ip', '30.30.30.43')
                    ->orWhere('ip', '30.30.30.44')
                    ->orWhere('ip', '30.30.30.45');
            })
            ->where("created_at", $ngay)
            ->first();
        $tongcomman = DB::table('commands')
            ->selectRaw('COUNT(*) AS total_commands')
            ->where("created_at", $ngay)
            ->first();
        return [
            'commands' => $commands,
            'tongcomman' => $tongcomman,
        ];
    }
    public function laycomchayxuong2($ngay)
    {
        $danhanvt2 = DB::table('comchayds')
            ->selectRaw('COUNT(*) AS total_dannhanvt2')
            ->where(function ($query) {
                $query->where('ip', '30.30.30.31')
                    ->orWhere('ip', '30.30.30.41');
            })
            ->where("ngaydk", $ngay)
            ->where('tt', 'dn')
            ->first();
        $tongdangkyvt2 = DB::table('comchayds')
            ->selectRaw('COUNT(*) AS total_dandkvt2')
            ->where(function ($query) {
                $query->where('ip', '30.30.30.31')
                    ->orWhere('ip', '30.30.30.41');
            })
            ->where("ngaydk", $ngay)
            ->first();
        return [
            'danhanvt2' => $danhanvt2,
            'tongdangkyvt2' => $tongdangkyvt2,
        ];
    }
    public function laycomchayxuong1($ngay)
    {
        $danhanvt1 = DB::table('comchayds')
            ->selectRaw('COUNT(*) AS total_dannhanvt1')
            ->where(function ($query) {
                $query->where('ip', '30.30.30.32')
                    ->orWhere('ip', '30.30.30.36');
            })
            ->where("ngaydk", $ngay)
            ->where('tt', 'dn')
            ->first();
        $tongdangkyvt1 = DB::table('comchayds')
            ->selectRaw('COUNT(*) AS total_dandkvt1')
            ->where(function ($query) {
                $query->where('ip', '30.30.30.32')
                    ->orWhere('ip', '30.30.30.36');
            })
            ->where("ngaydk", $ngay)
            ->first();
        return [
            'danhanvt1' => $danhanvt1,
            'tongdangkyvt1' => $tongdangkyvt1,
        ];
    }
    // public function kiemtradangkytangca1($manhansu)
    // {
    //     $currentDate = Carbon::now();
    //     $t = $currentDate->setTimezone('Asia/Ho_Chi_Minh');
    //     $ngayThangNam = $t->format('d-m-Y');
    //     $ktr = DB::table('tangca')
    //         ->select('id')
    //         ->where('created_at', $ngayThangNam)
    //         ->where('manhansu', $manhansu)
    //         ->first();
    //     if ($ktr) {
    //         return 1;
    //     }
    //     return 0;
    // }
    public function kiemtradangkytangca1($manhansu)
    {
        $currentDate = Carbon::now();
        $t = $currentDate->setTimezone('Asia/Ho_Chi_Minh');
        $ngayThangNam = $t->format('d-m-Y');
        $ktr = DB::table('commands')
            ->select('id', 'loaicomnhan')
            ->where('created_at', $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->where('thongtinnhan', 'tangca')
            ->where('loaicomnhan', 'nuoc')
            ->first();
        if ($ktr) {
            return 1;
        }
        return 0;
    }
    public function kiemtradangkytangcas($manhansu)
    {
        $currentDate = Carbon::now();
        $t = $currentDate->setTimezone('Asia/Ho_Chi_Minh');
        $ngayThangNam = $t->format('d-m-Y');
        $ktr = DB::table('commands')
            ->select('id', 'loaicomnhan', 'tt')
            ->where('created_at', $ngayThangNam)
            ->where('khunggio', '<>', 'C')
            ->where('manhansu', $manhansu)
            ->first();
        if ($ktr) {
            if ($ktr->tt == 'dk') {
                return 1;
            }
            return 2;
        }
        return 0;
    }
    public function kiemtradangkytangcac($manhansu)
    {
        $currentDate = Carbon::now();
        $t = $currentDate->setTimezone('Asia/Ho_Chi_Minh');
        $ngayThangNam = $t->format('d-m-Y');
        $ktr = DB::table('commands')
            ->select('id', 'loaicomnhan', 'tt')
            ->where('created_at', $ngayThangNam)
            ->where('khunggio',  'C')
            ->where('manhansu', $manhansu)
            ->first();
        if ($ktr) {
            if ($ktr->tt == 'dk') {
                if ($ktr->loaicomnhan == 'nuoc') {
                    return 1;
                } else {
                    return 2;
                }
            } else {
                if ($ktr->loaicomnhan == 'nuoc') {
                    return 3;
                } else {
                    return 4;
                }
            }
        }
        return 0;
    }
    public function kiemtradangkytangca($manhansu, $ngay, $bangman)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ktr = DB::table($bangman)
            ->select('id', 'loaicomnhan', 'tt')
            ->where('created_at', $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->where('thongtinnhan', 'tangca')
            ->where('loaicomnhan', 'nuoc')
            ->first();
        if ($ktr) {
            if ($ktr->tt == 'dk') {
                return 2;
            } elseif ($ktr->tt == 'dn') {
                return 8;
            }
        }
        return 0;
    }
    public function kiemtradangkychay($manhansu, $ngay, $bangchay)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ktr = DB::table($bangchay)
            ->select('id', 'tt', 'khunggio')
            ->where('ngaydk', $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->first();
        if ($ktr) {
            if ($ktr->khunggio != "C") {
                if ($ktr->tt == 'dk') {
                    return 1;
                }
                return 2;
            } else {
                if ($ktr->tt == 'dk') {
                    return 3;
                }
                return 4;
            }
        }
        return 0;
    }
    public function kiemtradangkychay1($manhansu)
    {
        $currentDate = Carbon::now();
        $t = $currentDate->setTimezone('Asia/Ho_Chi_Minh');
        $ngayThangNam = $t->format('d-m-Y');
        $ktr = DB::table('comchayds')
            ->select('id')
            ->where('ngaydk', $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->where('tt', "dn")
            ->first();
        if ($ktr) {
            return 2;
        }
        $ktr1 = DB::table('comchayds')
            ->select('id')
            ->where('ngaydk', $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->where('tt', "dk")
            ->first();
        if ($ktr1) {
            return 1;
        }
        return 0;
    }
    public function ktrnhancommansang($manhansu, $ngay, $bangman)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ktr = DB::table($bangman)
            ->select('id', 'tt', 'thongtinnhan')
            ->where("created_at", $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->where('khunggio', '<>', 'C')
            ->first();

        if ($ktr) {
            if ($ktr->thongtinnhan == 'macdinh') {
                if ($ktr->tt == 'dk') {
                    return 1;
                }
                return 2;
            } else {
                if ($ktr->tt == 'dk') {
                    return 3;
                }
                return 4;
            }
        }
        return 0;
    }
    public function ktrnhancommanchieu($manhansu, $ngay, $bangman)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ktr = DB::table($bangman)
            ->select('id', 'tt', 'thongtinnhan')
            ->where("created_at", $ngayThangNam)
            ->where('manhansu', $manhansu)
            ->where('khunggio', 'C')
            ->where(function ($query) {
                $query->where('loaicomnhan', null)
                    ->orWhere('loaicomnhan', 'man');
            })
            ->first();

        if ($ktr) {
            if ($ktr->thongtinnhan == 'macdinh') {
                if ($ktr->tt == 'dk') {
                    return 1;
                }
                return 2;
            } else {
                if ($ktr->tt == 'dk') {
                    return 3;
                }
                return 4;
            }
        }
        return 0;
    }
    public function getdulieucom($thang, $nam)
    {
        $startOfMonth = new DateTime("$nam-$thang-01");
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->modify('last day of this month');

        $dslaplich = DB::table('soluongcom')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderBy('created_at', 'asc')
            ->distinct()
            ->get();
        return $dslaplich;
    }
    public function kiemtrachaycv($ngay)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ntn = $ngayThangNamFormatted->format('Y-m-d');
        $ktr = DB::table('comchayds')
            ->whereNotIn('manhansu', function ($query) use ($ntn) {
                $query->select('manhansu')
                    ->from('diemdanh1')
                    ->where('ngaydiemdanh', $ntn);
            })
            ->where('ngaydk', $ngayThangNam)
            ->get();
        $dem = ['S' => 0, 'C' => 0];       
        if (count($ktr) > 0) {
            foreach ($ktr as $d) {
                if ($d->khunggio != 'C') {
                    $dem['S']++;
                } else {
                    $dem['C']++;
                }
            }
            return $dem;
        }
        return $dem;
    }
    public function kiemtramansangcv($ngay)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ntn = $ngayThangNamFormatted->format('Y-m-d');
        $ktr = DB::table('commands')
            ->whereNotIn('manhansu', function ($query) use ($ntn) {
                $query->select('manhansu')
                    ->from('diemdanh1')
                    ->where('ngaydiemdanh', $ntn);
            })
            ->where('created_at', $ngayThangNam)
            ->get();
        $dem = ['S' => 0, 'C' => 0];
        if (count($ktr) > 0) {
            foreach ($ktr as $d) {
                if ($d->khunggio != 'C') {
                    $dem['S']++;
                } else {
                    $dem['C']++;
                }
            }
            return $dem;
        }
        return $dem;
    }
    public function kiemtratcnuoccv($ngay)
    {
        $ngayThangNamFormatted = new \DateTime($ngay);
        $ngayThangNam = $ngayThangNamFormatted->format('d-m-Y');
        $ntn = $ngayThangNamFormatted->format('Y-m-d');
        $ktr = DB::table('commands')
            ->whereNotIn('manhansu', function ($query) use ($ntn) {
                $query->select('manhansu')
                    ->from('diemdanh1')
                    ->where('ngaydiemdanh', $ntn);
            })
            ->where('created_at', $ngayThangNam)
            ->where('khunggio', 'C')
            ->where('loaicomnhan', 'nuoc')
            ->get();
        return count($ktr);
    }
}
