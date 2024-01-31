<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$label}}</title>   
    <link rel="stylesheet" href="/dist/css/bootstrap.min.css">   
    <link rel="stylesheet" href="/dist/fonts/fontawesome-free-6.4.2-web/css/all.min.css">   
    <link rel="stylesheet" href="/dist/css/style.css">
    <link rel="stylesheet" href="/dist/css/cssdatatable.css" />
    <link rel="stylesheet" href="/dist/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="/dist/css/bootstrapjs.css">    
</head>

<body  style="background-color: #fff">
    <div class="p-3">
        
            <table id="bangNhanVien" class="table table-bordered" style="width:100%; font-size: 12px;">
                <thead>
                    <tr>
                        <th>Mã NS</th>
                        <th>Tên nhân sự</th>
                        @for ($i = 1; $i <= 31; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ds as $item)
                        @php
                            $list = explode(',', $item->ngaydiemdanh_list);
                            $demngay = 1;
                        @endphp
                        <tr>
                            <td>{{ $item->manhansu }}</td>
                            <td style="white-space: nowrap;">{{ $item->tennhansu }}</td>
                            @for ($i = 1; $i <= 31; $i++)
                                <td>
                                    @php
                                        $ktrngay = false;
                                        $tonggiolam_rounded = 0;
                                        for ($j = 0; $j < count($list); $j++) {
                                            $ngaydanhgia = substr($list[$j], 0, 2);
                                            if ($ngaydanhgia == $i) {
                                                $chuoiCanCat = $list[$j];
                                                $phan1 = substr($chuoiCanCat, 0, 10);
                                                $phan2 = substr($chuoiCanCat, 10);
                                                $tonggiolam_rounded = $phan2;
                                                if (is_numeric($phan2)) {                                                  
                                                    $tonggiolam_rounded = round($phan2 / 60, 1);
                                                }
                                                $ktrngay = true;
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if ($ktrngay)
                                        {{ $tonggiolam_rounded }}
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
       
    </div>
    <script src="/dist/jquery/jquery37.js"></script>    
    <script src="/dist/jquery/jquerydatatable.js"></script>
    <script src="/dist/jquery/jquerydatatablesbuttons.js"></script>
    <script src="/dist/jquery/jqueryjszip.js"></script>
    <script src="/dist/jquery/jquerypdfmake.js"></script>
    <script src="/dist/jquery/jqueryvfsfonts.js"></script>
    <script src="/dist/jquery/jquerybuttonhtml5.js"></script>
    <script src="/dist/jquery/jquerybuttonprinf.js"></script>
    <script type="text/javascript">
        // Khai báo biến dataTable
        const dataTable = new DataTable('#bangNhanVien', {
            dom: 'Bfrtip',
            language: {
                sSearch: "Tìm kiếm: "
            },
            buttons: ['excelHtml5']
        });
    </script>
</body>

</html>
