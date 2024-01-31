<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Modelquyenuser;
use Auth;

class controller_chamcong extends Controller
{
    public function trang_lap_lich()
    {
        $role = Auth::user()->role;
        $dsnhom =  DB::table('nhomlamviec')
            ->select('nhomlamviec.manhom', 'tennhom');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhom =  $dsnhom->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhom = $dsnhom->orderBy('manhom')
            ->get();
        $dsnhansu = [];
        $dstag = [];
        $tennhom = "";
        $dsca = DB::table('calamviec')->get();
        return view('chamcong/trang_lap_lich', [
            'dsnhom' => $dsnhom,
            'dsnhansu' => $dsnhansu,
            'dstag' => $dstag,
            'dsca' => $dsca,
            'tennhom' => $tennhom,
        ]);
    }
    public function tim_nhan_su_theo_nhom(Request $r)
    {
        $tennhom = $r->tennhom;
        $dsnhansu = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('manhansu', 'tennhansu', 'nhom')
            ->where('tennhom', $tennhom)
            ->where('nhansu.trangthai', "dang_lam")
            ->get();
        $dstag = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('nhom')
            ->distinct()
            ->where('tennhom', $tennhom)
            ->get();
        $dsca = DB::table('calamviec')->get();
        $role = Auth::user()->role;
        $dsnhom =  DB::table('nhomlamviec')
            ->select('nhomlamviec.manhom', 'tennhom');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhom =  $dsnhom->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhom = $dsnhom->orderBy('manhom')
            ->get();
        return view('chamcong/trang_lap_lich', [
            'dsnhom' => $dsnhom,
            'dsnhansu' => $dsnhansu,
            'dstag' => $dstag,
            'dsca' => $dsca,
            'tennhom' => $tennhom,
        ]);
    }
    public function laythongtinca($tenca)
    {
        $dsca = DB::table('calamviec')
            ->where('tenca', $tenca)
            ->get();
        return response()->json($dsca);
    }
    public function diem_danh(Request $r)
    {
        // {"scope":"VT1b","tag":"64295966","date":"2023-07-06","time":"08:58:35"}       
        $mayquet = $r->scope;
        $tag = $r->tag;
        $ngay = $r->date;
        $thoigianquet = $r->time;
        $startDate = Carbon::createFromFormat('Y-m-d', $ngay);
        $for = $startDate->format('Y-m-d');
        $mnv = $this->tag_to_mnv($tag);
        if ($mnv != 0) {
            $thu = $this->get_thu($for);
            $sumlaplich = 0;
            $ngay = Carbon::createFromFormat('Y-m-d', $for)->format('d-m-Y');
            $id = $mnv . '-' . $ngay;
            $this->kiem_tra_lich_quet_the($id, $mnv, $ngay, $thoigianquet, $mayquet, $thu);
            return response()->json("OK");
        } else {
            return response()->json("không có nhân viên này");
        }
    }
    public function laplich(Request $r)
    {
        $ip = $r->ip();
        $listnhansu = $r->listnhansu;
        $giovao = $r->giovao;
        $giora = $r->giora;
        $thoigiannghi = $r->thoigiannghi;
        //Tính số giờ làm 
        $gioVaoCarbon = Carbon::parse($giovao);
        $gioRaCarbon = Carbon::parse($giora);
        $chenhLechPhut = $gioVaoCarbon->diffInMinutes($gioRaCarbon);
        $sogiolaplich = $chenhLechPhut - $thoigiannghi;
        //Ngày
        $ngaybatdau = $r->ngaybatdau;
        $ngayketthuc = $r->ngayketthuc;
        if ($this->kiem_tra_chot_cong($ngaybatdau, $ngayketthuc) == 1) {
            return response()->json(['success' => false, 'message' => 'Tháng này đã chốt công']);
        }
        $ngaybatdau = Carbon::createFromFormat('Y-m-d', $ngaybatdau)->format('d-m-Y');
        $ngayketthuc = Carbon::createFromFormat('Y-m-d', $ngayketthuc)->format('d-m-Y');
        $ngaybatdau = Carbon::createFromFormat('d-m-Y', $ngaybatdau)->startOfDay();
        $ngayketthuc = Carbon::createFromFormat('d-m-Y', $ngayketthuc)->endOfDay();
        $listnay = [];
        $currentDate = $ngaybatdau->copy();
        while ($currentDate->lte($ngayketthuc)) {
            $listnay[] = $currentDate->format('d-m-Y');
            $currentDate->addDay();
        }
        //Xử lý
        for ($i = 0; $i < count($listnay); $i++) {
            for ($j = 0; $j < count($listnhansu); $j++) {
                $id = $listnhansu[$j] . '-' . $listnay[$i];
                $thu = $this->get_thu($listnay[$i]);
                $this->kiem_tra_lich($id, $listnhansu[$j], $listnay[$i], $gioVaoCarbon->format('H:i:s'), $gioRaCarbon->format('H:i:s'),  $thoigiannghi, $sogiolaplich, $thu, "", "", $ip);
            }
        }
        return response()->json(['success' => true, 'message' => 'thành công']);
    }
    private function them_data_diemdanh($id, $manhansu, $ngay, $thoigianvao, $thoigianra, $laplichvao, $laplichra, $mayquet, $tonggiolam, $thoigiannghi, $sum, $sumlaplich, $thu, $sophuttre, $hoso, $ghichu, $maluong)
    {
        DB::table('diemdanh1')->insert([
            'madiemdanh' => $id,
            'manhansu' => $manhansu,
            'ngaydiemdanh' => $ngay,
            'laplichvao' => $laplichvao,
            'laplichra' => $laplichra,
            'thoigianvao' => $thoigianvao,
            'thoigianra' => $thoigianra,
            'thoigiannghi' => $thoigiannghi,
            'tonggiolam' => $tonggiolam,
            'tonglaplich' => $sumlaplich,
            'tonggiolamthucte' => $sum,
            'thu' => $thu,
            'maluong' => $maluong,
            'sophuttre' => $sophuttre,
            'hoso' => $hoso,
            'ghichu' => $ghichu,
            'mayquet' => $mayquet,
        ]);
    }
    private function get_thu($for)
    {
        $thuTrongTuan = date('N', strtotime($for));
        switch ($thuTrongTuan) {
            case 1:
                $thu = '2';
                break;
            case 2:
                $thu = '3';
                break;
            case 3:
                $thu = '4';
                break;
            case 4:
                $thu = '5';
                break;
            case 5:
                $thu = '6';
                break;
            case 6:
                $thu = '7';
                break;
            case 7:
                $thu = '8';
                break;
            default:
                $thu = '0';
                break;
        }
        return $thu;
    }
    private function kiem_tra_lich($id, $manhansu, $ngay, $laplichvao, $laplichra,  $thoigiannghi, $tonglaplich, $thu, $hoso, $ghichu, $ip)
    {
        $ktr = DB::table('diemdanh1')
            ->select('madiemdanh', 'thoigianvao', 'thoigianra')
            ->where('madiemdanh', $id)
            ->first();
        if (!$ktr) {
            $this->them_data_diemdanh($id, $manhansu, $ngay, null, null, $laplichvao, $laplichra, null, 0, $thoigiannghi, 0, $tonglaplich, $thu, 0, $hoso, $ghichu, null);
        } else if ($ktr->thoigianvao != "") {
            //Có dữ liệu điểm danh
            if ($ktr->thoigianra != "") {
                $sumtre = 0;
                $thoigianra = Carbon::parse($ktr->thoigianra);
                $thoigianvao = Carbon::parse($ktr->thoigianvao);
                $lapLichVao = Carbon::parse($laplichvao);
                $laplichra = Carbon::parse($laplichra);
                if ($thoigianvao->greaterThan($lapLichVao)) {
                    //Đi trễ
                    $durationtre = $lapLichVao->diffAsCarbonInterval($thoigianvao);
                    $ht = $durationtre->h;
                    $mt = $durationtre->i;
                    $sumtre =  $sumtre + ($ht * 60 + $mt);
                } else {
                    $thoigianvao = $lapLichVao;
                }
                if ($laplichra->greaterThan($thoigianra)) {
                    //Về sớm
                    $durationsom = $thoigianra->diffAsCarbonInterval($laplichra);
                    $hs = $durationsom->h;
                    $ms = $durationsom->i;
                    $sumtre =  $sumtre + ($hs * 60 + $ms);
                } else {
                    $thoigianra = $laplichra;
                }
                $duration = $thoigianvao->diffAsCarbonInterval($thoigianra);
                $hour = $duration->h;
                $min = $duration->i;
                $sum =   ($hour * 60 + $min);
                if ($sum >= 540) {
                    $sumtong = $sum - $thoigiannghi;
                } else if ($sum < 270) {
                    $sumtong = $sum;
                } else {
                    $sumtong = $sum - 60;
                }
                $this->ghi_log($id, $ip);
                DB::table('diemdanh1')
                    ->where('madiemdanh', '=', $id)
                    ->update([
                        'laplichvao' => $lapLichVao,
                        'laplichra' => $laplichra,
                        'tonggiolam' => $sum,
                        'tonggiolamthucte' => $sumtong,
                        'sophuttre' => $sumtre,
                        'tonglaplich' => $tonglaplich,
                        'thoigiannghi' => $thoigiannghi,
                        'maluong' => null,
                    ]);
            } else {
                $this->ghi_log($id, $ip);
                DB::table('diemdanh1')
                    ->where('madiemdanh', '=', $id)
                    ->update([
                        'laplichvao' => $laplichvao,
                        'laplichra' => $laplichra,
                        'thoigiannghi' => $thoigiannghi,
                        'tonglaplich' => $tonglaplich,
                        'maluong' => null,
                    ]);
            }
        } else {
            $this->ghi_log($id, $ip);
            DB::table('diemdanh1')
                ->where('madiemdanh', '=', $id)
                ->update([
                    'laplichvao' => $laplichvao,
                    'laplichra' => $laplichra,
                    'thoigiannghi' => $thoigiannghi,
                    'tonglaplich' => $tonglaplich,
                    'maluong' => null,
                ]);
        }
    }
    private function tag_to_mnv($tag)
    {
        $kt = DB::table('nhansu')
            ->select('manhansu')
            ->where('tag', '=', $tag)
            ->first();
        if ($kt) {
            return $kt->manhansu;
        }
        return 0;
    }
    private function kiem_tra_lich_quet_the($id, $manhansu, $ngay, $thoigianquet, $mayquet,  $thu)
    {
        $sumtre = 0;
        $kt = DB::table('diemdanh1')
            ->select('madiemdanh', 'thoigianvao', 'laplichvao', 'laplichra', 'thoigiannghi')
            ->where('madiemdanh', $id)
            ->first();
        if (!$kt) {
            $this->them_data_diemdanh($id, $manhansu, $ngay, $thoigianquet, null, null, null, $mayquet, 0, 0, 0, 0, $thu, 0, null, null, null);
        } elseif ($kt->laplichvao == null && $kt->thoigianvao != null) {
            // Chưa lập lịch, nhưng đã quẹt thẻ
            $thoigianvao = Carbon::parse($kt->thoigianvao);
            $thoigianquet = Carbon::parse($thoigianquet);
            $durationtong = $thoigianvao->diffAsCarbonInterval($thoigianquet);
            $h = $durationtong->h;
            $m = $durationtong->i;
            $tonggiolam =  ($h * 60 + $m);
            DB::table('diemdanh1')
                ->where('madiemdanh', '=', $id)
                ->update([
                    'thoigianra' => $thoigianquet,
                    'tonggiolam' => $tonggiolam,
                ]);
        } elseif ($kt->thoigianvao == null && $kt->laplichvao != null) {
            // Đã lập lịch, nhưng chưa quẹt thẻ
            DB::table('diemdanh1')
                ->where('madiemdanh', '=', $id)
                ->update([
                    'thoigianvao' => $thoigianquet,
                ]);
        } else {
            // Đã lập lịch và đã quẹt thẻ
            $thoigianra = Carbon::parse($thoigianquet);
            $thoigianvao = Carbon::parse($kt->thoigianvao);
            $thoigiannghi = Carbon::parse($kt->thoigiannghi);
            $lapLichVao = Carbon::parse($kt->laplichvao);
            $laplichra = Carbon::parse($kt->laplichra);
            // So sánh hai giá trị
            if ($thoigianvao->greaterThan($lapLichVao)) {
                //Đi trễ
                $durationtre = $lapLichVao->diffAsCarbonInterval($thoigianvao);
                $ht = $durationtre->h;
                $mt = $durationtre->i;
                $sumtre =  $sumtre + ($ht * 60 + $mt);
            } else {
                $thoigianvao = $lapLichVao;
            }
            if ($laplichra->greaterThan($thoigianra)) {
                //Về sớm
                $durationsom = $thoigianra->diffAsCarbonInterval($laplichra);
                $hs = $durationsom->h;
                $ms = $durationsom->i;
                $sumtre =  $sumtre + ($hs * 60 + $ms);
            } else {
                $thoigianra = $laplichra;
            }
            $duration = $thoigianvao->diffAsCarbonInterval($thoigianra);
            $hour = $duration->h;
            $min = $duration->i;
            $sum =   ($hour * 60 + $min);
            if ($sum >= 540) {
                $sumtong = $sum - $kt->thoigiannghi;
            } else if ($sum < 270) {
                $sumtong = $sum;
            } else {
                $sumtong = $sum - 60;
            }
            DB::table('diemdanh1')
                ->where('madiemdanh', '=', $id)
                ->update([
                    'thoigianra' => $thoigianquet,
                    'tonggiolam' => $sum,
                    'tonggiolamthucte' => $sumtong,
                    'sophuttre' => $sumtre,
                ]);
        }
    }
    public function danh_sach_lap_lich()
    {
        $dsnhansu = [];
        $dstag = [];
        $tennhom = "";
        $role = Auth::user()->role;
        $dsnhom =  DB::table('nhomlamviec')
            ->select('nhomlamviec.manhom', 'tennhom');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhom =  $dsnhom->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhom = $dsnhom->orderBy('manhom')
            ->get();
        $today = Carbon::now()->format('Y-m-d');
        return view('chamcong/danh_sach_lap_lich', [
            'dsnhom' => $dsnhom,
            'dsnhansu' => $dsnhansu,
            'dstag' => $dstag,
            'tennhom' => $tennhom,
            'today' => $today
        ]);
    }
    public function lay_danh_sach_lap_lich_theo_nhom(Request $r)
    {
        $tennhom = $r->tennhom;
        $ngay = $r->ngay;
        $bang = $this->kiem_tra_bang_diem_danh($ngay);
        $ngaydiemdanh = Carbon::createFromFormat('Y-m-d', $ngay)->format('d-m-Y');
        $dsnhansu = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->leftjoin($bang, $bang . '.manhansu', '=', 'nhansu.manhansu')
            ->select('nhansu.manhansu', 'tennhansu', 'nhom', 'tennhom', 'thoigianvao', 'thoigianra', 'laplichvao', 'laplichra', 'ngaydiemdanh', 'maluong', 'mayquet', 'tonggiolamthucte', 'tonglaplich', 'sophuttre')
            ->where('tennhom', $tennhom)
            ->where('nhansu.trangthai', "dang_lam")
            ->where('ngaydiemdanh', $ngaydiemdanh)
            ->get();
        $dstag = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('nhom')
            ->distinct()
            ->where('tennhom', $tennhom)
            ->get();
        $role = Auth::user()->role;
        $dsnhom =  DB::table('nhomlamviec')
            ->select('nhomlamviec.manhom', 'tennhom');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhom =  $dsnhom->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhom = $dsnhom->orderBy('manhom')
            ->get();
        return view('chamcong/danh_sach_lap_lich', [
            'dsnhom' => $dsnhom,
            'dsnhansu' => $dsnhansu,
            'dstag' => $dstag,
            'tennhom' => $tennhom,
            'today' => $ngay
        ]);
    }
    public function trang_lap_lich_nhom($tennhom)
    {
        $dsnhansu = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('manhansu', 'tennhansu', 'nhom')
            ->where('tennhom', $tennhom)
            ->where('nhansu.trangthai', "dang_lam")
            ->get();
        $dstag = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('nhom')
            ->distinct()
            ->where('tennhom', $tennhom)
            ->get();
        $dsca = DB::table('calamviec')->get();
        $role = Auth::user()->role;
        $dsnhom =  DB::table('nhomlamviec')
            ->select('nhomlamviec.manhom', 'tennhom');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhom =  $dsnhom->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhom = $dsnhom->orderBy('manhom')
            ->get();
        return view('chamcong/trang_lap_lich', [
            'dsnhom' => $dsnhom,
            'dsnhansu' => $dsnhansu,
            'dstag' => $dstag,
            'dsca' => $dsca,
            'tennhom' => $tennhom,
        ]);
    }
    public function danh_sach_diem_danh()
    {
        $month = Carbon::now()->startOfMonth();
        $startOfMonth = Carbon::now()->startOfMonth();
        $selectedMonth = $startOfMonth->month;
        $selectedYear = $startOfMonth->year;
        $calendarData = [];
        $manhansu = "";
        $nhom = "";
        $dsca = [];
        $role = Auth::user()->role;
        $dsnhansu = DB::table('nhansu')
            ->select('manhansu', 'tennhansu');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhansu = $dsnhansu->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
                ->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhansu = $dsnhansu->where('nhansu.trangthai', "dang_lam")->get();
        $month = $month->format('Y-m');
        $label = "Chọn nhân sự";
        return view('chamcong/danh_sach_diem_danh', compact('selectedMonth', 'dsca', 'selectedYear', 'manhansu', 'nhom', 'month', 'label', 'calendarData', 'selectedMonth', 'dsnhansu'));
    }
    public function lay_danh_sach_diem_danh_theo_nhan_su(Request $r)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $month = $r->thang;
        $ktr_chot_cong =  $this->kiem_tra_chot_cong_thang($month);
        $view = "danh_sach_diem_danh";
        if ($ktr_chot_cong == 1) {
            $view = "danh_sach_diem_danh_da_khoa";
        }
        $bang = $this->kiem_tra_bang_diem_danh($month);
        $dateComponents = date_parse($month);
        $selectedYear = $dateComponents['year'];
        $selectedMonth = $dateComponents['month'];
        $manhansu = $r->manhansu;
        $calendarData = $this->calculateCalendarData($selectedYear, $selectedMonth, $manhansu, $bang);
        $laynhom = DB::table('nhansu')
            ->leftjoin('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
            ->select('tennhom', 'tennhansu')
            ->where('manhansu', $manhansu)
            ->first();
        $nhom = $laynhom->tennhom;
        $role = Auth::user()->role;
        $dsnhansu = DB::table('nhansu')
            ->select('manhansu', 'tennhansu');
        if ($role == "hoso") {
            $userid = Auth::user()->id;
            $dsnhansu = $dsnhansu->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
                ->join('nhomquanly', 'nhomquanly.manhom', '=', 'nhomlamviec.manhom')
                ->where('userid', $userid);
        }
        $dsnhansu = $dsnhansu->where('nhansu.trangthai', "dang_lam")->get();
        $label = $laynhom->tennhansu;
        $dsca = DB::table('calamviec')->get();
        return view('chamcong/' . $view, compact('selectedMonth', 'dsca', 'selectedYear', 'manhansu', 'nhom', 'month', 'label', 'calendarData', 'selectedMonth', 'dsnhansu'));
    }
    private function calculateCalendarData($year, $month, $manhansu, $bang)
    {
        $calendarData = [];
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $weekIndex = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $ngaydiemdanh = $date->format('d-m-Y');
            $dayOfWeek = $date->dayOfWeek;
            if ($day === 1 || $dayOfWeek === 0) {
                $calendarData[$weekIndex] = [];
            }
            if ($manhansu) {
                $dslaplich = DB::table($bang)
                    ->select('madiemdanh',  'manhansu', 'thoigianvao', 'thoigianra', 'tonglaplich', 'tonggiolamthucte', 'laplichvao', 'laplichra', 'thu', 'maluong')
                    ->where('manhansu', $manhansu)
                    ->where('ngaydiemdanh', $ngaydiemdanh)
                    ->orderBy('thoigianvao', 'desc')
                    ->first();
                if ($dslaplich) {
                    $tongll = $dslaplich->tonglaplich;
                    if (is_numeric($tongll)) {
                        $tongll /= 60;
                    }
                    $thoigianvao = substr($dslaplich->thoigianvao, 0, -3);
                    $thoigianra = substr($dslaplich->thoigianra, 0, -3);
                    $tonglaplich = $tongll;
                    $tonggiolam_rounded = round($dslaplich->tonggiolamthucte / 60, 1);
                    $laplichvao = substr($dslaplich->laplichvao, 0, -3);
                    $laplichra = substr($dslaplich->laplichra, 0, -3);
                    $thu = $dslaplich->thu;
                    $maluong = $dslaplich->maluong;
                } else {
                    $thoigianvao = "";
                    $thoigianra = "";
                    $laplichvao = "";
                    $tonglaplich = "";
                    $tonggiolam_rounded = "";
                    $laplichra = "";
                    $thu = "";
                    $maluong = "";
                }
            } else {
                $thoigianvao = "";
                $thoigianra = "";
                $laplichvao = "";
                $tonglaplich = "";
                $tonggiolam_rounded = "";
                $laplichra = "";
                $thu = "";
                $maluong = "";
            }
            $calendarData[$weekIndex][] = [
                'day' => $day,
                'date' => $date,
                'thoigianvao' => $thoigianvao,
                'thoigianra' => $thoigianra,
                'laplichvao' => $laplichvao,
                'tonglaplich' => $tonglaplich,
                'ngaydiemdanh' => $ngaydiemdanh,
                'tonggiolamthucte' => $tonggiolam_rounded,
                'laplichra' => $laplichra,
                'thu' => $thu,
                'maluong' => $maluong,
            ];
            if ($dayOfWeek === 6) {
                // Tăng chỉ số của tuần
                $weekIndex++;
            }
        }

        return $calendarData;
    }
    public function lay_thong_tin_diem_danh(Request $r)
    {
        $madiemdanh = $r->madiemdanh;
        $laynhom = DB::table('nhansu')
            ->leftjoin('diemdanh1', 'diemdanh1.manhansu', '=', 'nhansu.manhansu')
            ->select('madiemdanh',  'nhansu.manhansu', 'tennhansu', 'thoigianvao', 'thoigianra', 'tonglaplich', 'thoigiannghi', 'tonggiolamthucte', 'laplichvao', 'laplichra', 'thu', 'maluong')
            ->where('madiemdanh', $madiemdanh)
            ->first();
        if ($laynhom) {
            $manhansu = $laynhom->manhansu;
            $thoigianvao = $laynhom->thoigianvao;
            $thoigianra = $laynhom->thoigianra;
            $thoigiannghi = $laynhom->thoigiannghi;
            $laplichvao = $laynhom->laplichvao;
            $laplichra = $laynhom->laplichra;
            $tennhansu = $laynhom->tennhansu;
        } else {
            $manhansu = substr($madiemdanh, 0, 7);
            $laynhom = DB::table('nhansu')
                ->select('manhansu', 'tennhansu')
                ->where('manhansu', $manhansu)
                ->first();
            $thoigianvao = "";
            $thoigianra = "";
            $thoigiannghi = "";
            $laplichvao = "";
            $laplichra = "";
            $tennhansu = $laynhom->tennhansu;
        }
        $ngaydiemdanh = substr($madiemdanh, 8, 10);
        return response()->json([
            'manhansu' => $manhansu,
            'thoigianvao' =>  $thoigianvao,
            'ngaydiemdanh' =>  $ngaydiemdanh,
            'thoigianra' =>  $thoigianra,
            'thoigiannghi' =>  $thoigiannghi,
            'laplichvao' =>  $laplichvao,
            'laplichra' =>  $laplichra,
            'tennhansu' =>  $tennhansu,
        ]);
    }
    public function cap_nhat_thong_tin_diem_danh(Request $r)
    {
        $ip = $r->ip();
        $manhansu = $r->manhansu;
        $ngaydiemdanh = $r->ngaydiemdanh;
        $giovao = $r->giovao;
        $giora = $r->giora;
        $diemdanhra = $r->diemdanhra;
        $diemdanhvao = $r->diemdanhvao;
        $id = $manhansu . '-' . $ngaydiemdanh;
        $thoigiannghi = $r->thoigiannghi;
        //Tính số giờ làm 
        $gioVaoCarbon = Carbon::parse($giovao);
        $gioRaCarbon = Carbon::parse($giora);
        $chenhLechPhut = $gioVaoCarbon->diffInMinutes($gioRaCarbon);
        $sogiolaplich = $chenhLechPhut - $thoigiannghi;
        $thu = $this->get_thu($ngaydiemdanh);
        if ($diemdanhra) {
            $diemdanhra = Carbon::parse($diemdanhra)->format('H:i:s');
        } else {
            $diemdanhra = null;
        }
        if ($diemdanhvao) {
            $diemdanhvao = Carbon::parse($diemdanhvao)->format('H:i:s');
        } else {
            $diemdanhvao = null;
        }
        $this->ghi_log($id, $ip);
        $this->cap_nhat_lich_quet_the($id, $manhansu, $ngaydiemdanh,  $diemdanhvao, $diemdanhra, $gioVaoCarbon->format('H:i:s'), $gioRaCarbon->format('H:i:s'),  $thu,  $thoigiannghi, $sogiolaplich, "");

        return response()->json(['success' => true, 'message' => 'thành công']);
    }
    private function cap_nhat_lich_quet_the($id, $manhansu, $ngay, $diemdanhvao, $diemdanhra, $laplichvao, $laplichra,  $thu,  $thoigiannghi, $sogiolaplich, $ghichu)
    {
        $sumtre = 0;
        $kt = DB::table('diemdanh1')
            ->select('madiemdanh', 'thoigianvao', 'laplichvao', 'laplichra', 'thoigiannghi')
            ->where('madiemdanh', $id)
            ->first();
        if (!$kt) {
            if ($diemdanhra) {
                $thoigianra = Carbon::parse($diemdanhra);
                $thoigianvao = Carbon::parse($diemdanhvao);
                $thoigiannghi = Carbon::parse($thoigiannghi);
                $lapLichVao = Carbon::parse($laplichvao);
                $laplichra = Carbon::parse($laplichra);
                // So sánh hai giá trị
                if ($thoigianvao->greaterThan($lapLichVao)) {
                    //Đi trễ
                    $durationtre = $lapLichVao->diffAsCarbonInterval($thoigianvao);
                    $ht = $durationtre->h;
                    $mt = $durationtre->i;
                    $sumtre =  $sumtre + ($ht * 60 + $mt);
                } else {
                    $thoigianvao = $lapLichVao;
                }
                if ($laplichra->greaterThan($thoigianra)) {
                    //Về sớm
                    $durationsom = $thoigianra->diffAsCarbonInterval($laplichra);
                    $hs = $durationsom->h;
                    $ms = $durationsom->i;
                    $sumtre =  $sumtre + ($hs * 60 + $ms);
                } else {
                    $thoigianra = $laplichra;
                }
                $duration = $thoigianvao->diffAsCarbonInterval($thoigianra);
                $hour = $duration->h;
                $min = $duration->i;
                $sum =   ($hour * 60 + $min);
                if ($sum >= 540) {
                    $sumtong = $sum - $thoigiannghi;
                } else if ($sum < 270) {
                    $sumtong = $sum;
                } else {
                    $sumtong = $sum - 60;
                }
                //($id, $manhansu, $ngay, $thoigianvao, $thoigianra, $laplichvao, $laplichra, $mayquet, $tonggiolam, $thoigiannghi, $sum, $sumlaplich, $thu, $sophuttre, $hoso, $ghichu)
                $this->them_data_diemdanh($id, $manhansu, $ngay, $diemdanhvao, $diemdanhra, $laplichvao, $laplichra, "Chấm tay", $sumtong, $thoigiannghi, $sum, $sogiolaplich, $thu, $sumtre, null, null, null);
            } else {
                $this->them_data_diemdanh($id, $manhansu, $ngay, $diemdanhvao, $diemdanhra, $laplichvao, $laplichra, "Chấm tay", 0, $thoigiannghi, 0, $sogiolaplich, $thu, 0, null, null, null);
            }
        } else {
            $thoigianvao = Carbon::parse($diemdanhvao);
            if ($diemdanhra) {
                $diemdanhra = Carbon::parse($diemdanhra);
                $lapLichVao = Carbon::parse($laplichvao);
                $laplichra = Carbon::parse($laplichra);
                // So sánh hai giá trị
                if ($thoigianvao->greaterThan($lapLichVao)) {
                    //Đi trễ
                    $durationtre = $lapLichVao->diffAsCarbonInterval($thoigianvao);
                    $ht = $durationtre->h;
                    $mt = $durationtre->i;
                    $sumtre =  $sumtre + ($ht * 60 + $mt);
                    $tmpvao = $thoigianvao;
                } else {
                    $tmpvao = $lapLichVao;
                }
                if ($laplichra->greaterThan($diemdanhra)) {
                    //Về sớm
                    $durationsom = $diemdanhra->diffAsCarbonInterval($laplichra);
                    $hs = $durationsom->h;
                    $ms = $durationsom->i;
                    $sumtre =  $sumtre + ($hs * 60 + $ms);
                    $tmpra = $diemdanhra;
                } else {
                    $tmpra = $laplichra;
                }
                $duration = $tmpvao->diffAsCarbonInterval($tmpra);
                $hour = $duration->h;
                $min = $duration->i;
                $sum =   ($hour * 60 + $min);
                if ($sum >= 540) {
                    $sumtong = $sum - $thoigiannghi;
                } else if ($sum < 270) {
                    $sumtong = $sum;
                } else {
                    $sumtong = $sum - 60;
                }
                DB::table('diemdanh1')
                    ->where('madiemdanh', '=', $id)
                    ->update([
                        'thoigianra' => $diemdanhra->format('H:i:s'),
                        'thoigianvao' => $thoigianvao->format('H:i:s'),
                        'laplichvao' => $laplichvao,
                        'laplichra' => $laplichra,
                        'tonggiolam' => $sum,
                        'tonggiolamthucte' => $sumtong,
                        'thoigiannghi' => $thoigiannghi,
                        'tonglaplich' => $sogiolaplich,
                        'sophuttre' => $sumtre,
                        'ghichu' => $ghichu,
                    ]);
            } else {
                DB::table('diemdanh1')
                    ->where('madiemdanh', '=', $id)
                    ->update([
                        'thoigianvao' => $thoigianvao->format('H:i:s'),
                        'laplichvao' => $laplichvao,
                        'laplichra' => $laplichra,
                        'tonglaplich' => $sogiolaplich,
                        'thoigiannghi' => $thoigiannghi,
                        'ghichu' => $ghichu,
                    ]);
            }
        }
    }
    public function lay_thong_tin_xin_nghi(Request $r)
    {
        $madiemdanh = $r->madiemdanh;
        $laynhom = DB::table('nhansu')
            ->leftjoin('diemdanh1', 'diemdanh1.manhansu', '=', 'nhansu.manhansu')
            ->select('madiemdanh',  'nhansu.manhansu', 'tennhansu', 'diemdanh1.ghichu', 'maluong')
            ->where('madiemdanh', $madiemdanh)
            ->first();
        if ($laynhom) {
            $maluong = $laynhom->maluong;
            $lydo = $laynhom->ghichu;
        } else {
            $manhansu = substr($madiemdanh, 0, 7);
            $laynhom = DB::table('nhansu')
                ->select('manhansu', 'tennhansu')
                ->where('manhansu', $manhansu)
                ->first();
            $maluong = "";
            $lydo = "";
        }
        return response()->json([
            'manhansu' => $laynhom->manhansu,
            'tennhansu' =>  $laynhom->tennhansu,
            'maluong' =>  $maluong,
            'lydo' =>  $lydo,
        ]);
    }
    public function lap_lich_nghi(Request $r)
    {
        $madiemdanh = $r->madiemdanh;
        $ngay = substr($madiemdanh, 8, 10);
        $ip = $r->ip();
        if ($this->kiem_tra_chot_cong($ngay, $ngay) == 1) {
            return response()->json(['success' => false, 'message' => 'Tháng này đã chốt công']);
        }
        $lydo = $r->lydo;
        $loaingaynghi = $r->maluong;
        $ktr = DB::table('diemdanh1')
            ->select('manhansu')
            ->where('madiemdanh', $madiemdanh)
            ->first();
        if ($ktr) {
            DB::table('diemdanh1')
                ->where('madiemdanh', '=', $madiemdanh)
                ->update([
                    'ghichu' => $lydo,
                    'maluong' => $loaingaynghi,
                    'tonglaplich' => $loaingaynghi,
                    'laplichvao' => null,
                    'laplichra' => null,
                ]);
        } else {
            $manhansu = substr($madiemdanh, 0, 7);
            $ngay = substr($madiemdanh, 8, 10);
            $this->ghi_log($madiemdanh, $ip);
            $this->them_data_diemdanh($madiemdanh, $manhansu, $ngay, null, null, null, null, null, null, null, null, null, null, null, null, $lydo, $loaingaynghi);
        }
        return response()->json(['success' => true, 'message' => 'thành công']);
    }
    public function huy_lich_nghi(Request $r)
    {
        $madiemdanh = $r->madiemdanh;
        $ktr = DB::table('diemdanh1')
            ->select('manhansu')
            ->where('madiemdanh', $madiemdanh)
            ->first();
        if ($ktr) {
            DB::table('diemdanh1')
                ->where('madiemdanh', '=', $madiemdanh)
                ->update([
                    'ghichu' => "",
                    'maluong' => "",
                ]);
        }
        return response()->json(['success' => true, 'message' => 'thành công']);
    }
    public function trang_xuat_du_lieu_lap_lich()
    {
        $month = Carbon::now()->startOfMonth();
        $month = $month->format('Y-m');
        $ds = [];
        return view('chamcong/xuat_du_lieu_lap_lich', compact('ds', 'month'));
    }
    public function xuat_lap_lich(Request $r)
    {
        $month = $r->thang;
        $ngayBatDau = date('Y-m-01', strtotime($month));
        $ngayKetThuc = date('Y-m-t', strtotime($month));
        $startTime = date('d-m-Y', strtotime($ngayBatDau));
        $endTime = date('d-m-Y', strtotime($ngayKetThuc));
        $laplich = $r->laplich;
        $diemdanh = $r->diemdanh;
        if ($laplich) {
            $ds = DB::table('diemdanh1')
                ->join('nhansu', 'nhansu.manhansu', '=', 'diemdanh1.manhansu')
                ->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
                ->select('nhansu.manhansu', 'tennhansu', 'tennhom', DB::raw("GROUP_CONCAT(DISTINCT diemdanh1.ngaydiemdanh, tonglaplich) as ngaydiemdanh_list"))
                ->whereBetween('ngaydiemdanh', [$startTime, $endTime])
                ->groupBy('tennhom', 'nhansu.manhansu', 'tennhansu', 'tennhom')
                ->get();
            $label = "Dữ liệu lập lịch: " . $month;
        } else {
            $ds = DB::table('diemdanh1')
                ->join('nhansu', 'nhansu.manhansu', '=', 'diemdanh1.manhansu')
                ->join('nhomlamviec', 'nhomlamviec.manhom', '=', 'nhansu.manhom')
                ->select('nhansu.manhansu', 'tennhansu', 'tennhom', DB::raw("GROUP_CONCAT(DISTINCT diemdanh1.ngaydiemdanh, tonggiolamthucte) as ngaydiemdanh_list"))
                ->whereBetween('ngaydiemdanh', [$startTime, $endTime])
                ->groupBy('tennhom', 'nhansu.manhansu', 'tennhansu', 'tennhom')
                ->get();
            $label = "Dữ liệu điểm danh: " . $month;
        }
        return view('chamcong/xuat_excel_lap_lich', compact('ds', 'label'));
    }
    public function ghi_log($id, $ip)
    {
        $dataToInsert = DB::table('diemdanh1')->where('madiemdanh', $id)->first();
        if ($dataToInsert) {
            $month = Carbon::now();
            $hoso = Auth::user()->manhansu;
            DB::table('logs_diemdanh')->insert([
                'madiemdanh' => $id,
                'manhansu' => $dataToInsert->manhansu,
                'ngaydiemdanh' => $dataToInsert->ngaydiemdanh,
                'laplichvao' => $dataToInsert->laplichvao,
                'laplichra' => $dataToInsert->laplichra,
                'thoigianvao' => $dataToInsert->thoigianvao,
                'thoigianra' => $dataToInsert->thoigianra,
                'thoigiannghi' => $dataToInsert->thoigiannghi,
                'tonggiolam' => $dataToInsert->tonggiolam,
                'tonglaplich' => $dataToInsert->tonglaplich,
                'tonggiolamthucte' => $dataToInsert->tonggiolamthucte,
                'thu' => $dataToInsert->thu,
                'maluong' => $dataToInsert->maluong,
                'sophuttre' => $dataToInsert->sophuttre,
                'hoso' => $hoso,
                'ghichu' => $dataToInsert->ghichu,
                'ip' => $ip,
                'updated_at' => $month,
            ]);
        }
    }
    public function trang_chot_cong()
    {
        $month = Carbon::now()->startOfMonth();
        $month = $month->format('Y-m');
        $ds = DB::table('chot_cong')->get();
        return view('chamcong/trang_chot_cong', compact('ds', 'month'));
    }
    public function chot_cong(Request $r)
    {
        $month = $r->thang;
        $ktr = DB::table('chot_cong')
            ->select('tt')
            ->where('thang', $month)
            ->first();
        if (!$ktr) {
            DB::table('chot_cong')
                ->insert([
                    'thang' => $month,
                    'tt' => "dc",
                ]);
        }
        $ds = DB::table('chot_cong')->get();
        return view('chamcong/trang_chot_cong', compact('ds', 'month'));
    }
    public function cap_nhat_chot_cong(Request $r)
    {
        $id = $r->id;
        $ds = DB::table('chot_cong')
            ->where('id', $id)
            ->first();
        $tt = "mo";
        if ($ds->tt == "mo") {
            $tt = "dc";
        }
        DB::table('chot_cong')
            ->where('id', '=', $id)
            ->update([
                'tt' => $tt
            ]);
        return response()->json(['success' => true, 'message' => 'thành công']);
    }
    private function kiem_tra_chot_cong($ngayBatDau, $ngayKetThuc)
    {
        $ngayBatDau = substr($ngayBatDau, 0, 7);
        $ngayKetThuc = substr($ngayKetThuc, 0, 7);
        $ds = DB::table('chot_cong')
            ->select('thang')
            ->where(function ($query) use ($ngayBatDau, $ngayKetThuc) {
                $query->where('thang', $ngayBatDau)
                    ->orWhere('thang', $ngayKetThuc);
            })
            ->where('tt', 'dc')
            ->first();
        if ($ds) {
            return 1;
        }
        return 0;
    }
    private function kiem_tra_chot_cong_thang($thang)
    {
        $ds = DB::table('chot_cong')
            ->select('thang')
            ->where('thang', $thang)
            ->where('tt', 'dc')
            ->first();
        if ($ds) {
            return 1;
        }
        return 0;
    }
    private function kiem_tra_bang_diem_danh($thang)
    {
        $inputDate = Carbon::parse($thang);
        $currentDate = Carbon::now();
        $twoMonthsAgo = $currentDate->subMonths(2);
        $check =  $inputDate->greaterThan($twoMonthsAgo);
        if ($check) {
            return "diemdanh1";
        }
        return "diemdanh_old";
    }
}
