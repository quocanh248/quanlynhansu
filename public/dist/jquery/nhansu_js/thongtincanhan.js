//KHAI BÁO
var trangchu_manhansu = document.getElementById("trangchu_manhansu");
var trangchu_tennhansu = document.getElementById("trangchu_tennhansu");
var trangchu_ngaysinh = document.getElementById("trangchu_ngaysinh");
var trangchu_gioitinh = document.getElementById("trangchu_gioitinh");
var trangchu_nhomlamviec = document.getElementById("trangchu_nhomlamviec");
var trangchu_noilamviec = document.getElementById("trangchu_noilamviec");
var trangchu_sdt = document.getElementById("trangchu_sdt");
var trangchu_diachi = document.getElementById("trangchu_diachi");
var trangchu_trinhdo = document.getElementById("trangchu_trinhdo");
var trangchu_dantoc = document.getElementById("trangchu_dantoc");
var trangchu_tongiao = document.getElementById("trangchu_tongiao");
var trangchu_cccd = document.getElementById("trangchu_cccd");
var trangchu_ngaycap = document.getElementById("trangchu_ngaycap");
var trangchu_noicap = document.getElementById("trangchu_noicap");
var trangchu_ngaybatdaulam = document.getElementById("trangchu_ngaybatdaulam");
var trangchu_ngaykyhopdong = document.getElementById("trangchu_ngaykyhopdong");
var trang_chu_cap_nhat_ttcn = document.getElementById(
    "trang_chu_cap_nhat_ttcn"
);
var trangchu_previewUpload = document.getElementById("trangchu_previewUpload");
var trangchu_myFile = document.getElementById("trangchu_myFile");
var trangchu_filename = document.getElementById("trangchu_filename");

var danhsach_manhansu = document.getElementById("danhsach_manhansu");
var danhsach_tennhansu = document.getElementById("danhsach_tennhansu");
var danhsach_ngaysinh = document.getElementById("danhsach_ngaysinh");
var danhsach_gioitinh = document.getElementById("danhsach_gioitinh");
var danhsach_nhomlamviec = document.getElementById("danhsach_nhomlamviec");
var danhsach_noilamviec = document.getElementById("danhsach_noilamviec");
var danhsach_sdt = document.getElementById("danhsach_sdt");
var danhsach_diachi = document.getElementById("danhsach_diachi");
var danhsach_trinhdo = document.getElementById("danhsach_trinhdo");
var danhsach_dantoc = document.getElementById("danhsach_dantoc");
var danhsach_tongiao = document.getElementById("danhsach_tongiao");
var danhsach_cccd = document.getElementById("danhsach_cccd");
var danhsach_ngaycap = document.getElementById("danhsach_ngaycap");
var danhsach_noicap = document.getElementById("danhsach_noicap");
var danhsach_ngaybatdaulam = document.getElementById("danhsach_ngaybatdaulam");
var danhsach_ngaykyhopdong = document.getElementById("danhsach_ngaykyhopdong");
var danh_sach_cap_nhat_ttcn = document.getElementById(
    "danh_sach_cap_nhat_ttcn"
);
var danhsach_filename = document.getElementById("danhsach_filename");
var danhsach_previewUpload = document.getElementById("danhsach_previewUpload");
var danhsach_trangthai = document.getElementById("danhsach_trangthai");
//thông tin
$(document).ready(function () {
    document.querySelector("#trangchu_myFile").onchange = function (e) {
        var formData = new FormData();
        formData.append("tmpFile", $(this)[0].files[0]);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: "/upload_hinhanh/image",
            method: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                $('input[name="trangchu_filename"]').val(res);
                $("#trangchu_previewUpload").attr("src", res);
            },
        });
    };    
    //Cấp tài khoản
    $(".tt_ca_nhan").click(function () {
        var manhansu = this.getAttribute("data-custom-value");
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/thong_tin_ca_nhan/" + manhansu,
                method: "GET",
                success: function (response) {
                    $('#trangchu_gioitinh').empty();
                    var tt = response.tt;
                    var dsnhom = response.dsnhom;
                    var nhom = tt.nhom;
                    if (nhom == "null") {
                        nhom = "";
                    }
                    trangchu_manhansu.value = tt.manhansu;
                    trangchu_tennhansu.value = tt.tennhansu;
                    trangchu_ngaysinh.value = tt.ngaysinh;
                    var option_1 = document.createElement("option");
                    option_1.value = tt.gioitinh;
                    option_1.text = tt.gioitinh;
                    if (tt.gioitinh == "male") {
                        var gt = "female";
                    } else {
                        var gt = "male";
                    }
                    var option_2 = document.createElement("option");
                    option_2.value = gt;
                    option_2.text = gt;
                    trangchu_gioitinh.appendChild(option_1);
                    trangchu_gioitinh.appendChild(option_2);
                    var optionnhomlamviec = document.createElement("option");
                    optionnhomlamviec.value = tt.manhom;
                    optionnhomlamviec.text = tt.tennhom;
                    trangchu_nhomlamviec.appendChild(optionnhomlamviec);
                    trangchu_noilamviec.value = nhom;
                    trangchu_sdt.value = tt.sdt;
                    trangchu_diachi.value = tt.diachi;
                    trangchu_trinhdo.value = tt.trinhdo;
                    trangchu_dantoc.value = tt.dantoc;
                    trangchu_tongiao.value = tt.tongiao;
                    trangchu_cccd.value = tt.sochungminh;
                    trangchu_ngaycap.value = tt.ngaycap;
                    trangchu_noicap.value = tt.noicap;
                    trangchu_ngaybatdaulam.value = tt.ngaybatdaulam;
                    trangchu_ngaykyhopdong.value = tt.ngaykyhopdong;
                    trangchu_filename.value = tt.hinhanh;
                    trangchu_previewUpload.src = tt.hinhanh;

                    //nhóm
                    $.each(dsnhom, function (i, item) {
                        $("#trangchu_nhomlamviec").append(
                            $("<option>", {
                                value: item.manhom,
                                text: item.tennhom,
                            })
                        );
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    $("#trang_chu_cap_nhat_ttcn").click(function () {
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/cap_nhat_thong_tin_ca_nhan",
                method: "POST",
                data: {
                    trangchu_manhansu: trangchu_manhansu.value,
                    trangchu_tennhansu: trangchu_tennhansu.value,
                    trangchu_ngaysinh: trangchu_ngaysinh.value,
                    trangchu_gioitinh: trangchu_gioitinh.value,
                    trangchu_nhomlamviec: trangchu_nhomlamviec.value,
                    trangchu_noilamviec: trangchu_noilamviec.value,
                    trangchu_sdt: trangchu_sdt.value,
                    trangchu_diachi: trangchu_diachi.value,
                    trangchu_trinhdo: trangchu_trinhdo.value,
                    trangchu_dantoc: trangchu_dantoc.value,
                    trangchu_tongiao: trangchu_tongiao.value,
                    trangchu_cccd: trangchu_cccd.value,
                    trangchu_ngaycap: trangchu_ngaycap.value,
                    trangchu_noicap: trangchu_noicap.value,
                    trangchu_ngaybatdaulam: trangchu_ngaybatdaulam.value,
                    trangchu_ngaykyhopdong: trangchu_ngaykyhopdong.value,
                    trangchu_hinhanh: trangchu_filename.value,
                },
                success: function (response) {
                    alert(response.message);                    
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });

    //Danh sách
    $(".btn-danhsach").click(function () {
        var button = $(this);
        var manhansu = button.val();
        console.log("dô");
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/thong_tin_ca_nhan/" + manhansu,
                method: "GET",
                success: function (response) {
                    $('#danhsach_gioitinh').empty();
                    var tt = response.tt;
                    var dsnhom = response.dsnhom;
                    var nhom = tt.nhom;
                    if (nhom == "null") {
                        nhom = "";
                    }
                    danhsach_manhansu.value = tt.manhansu;
                    danhsach_tennhansu.value = tt.tennhansu;
                    danhsach_ngaysinh.value = tt.ngaysinh;
                    var option_1 = document.createElement("option");
                    option_1.value = tt.gioitinh;
                    option_1.text = tt.gioitinh;
                    if (tt.gioitinh == "male") {
                        var gt = "female";
                    } else {
                        var gt = "male";
                    }
                    var option_2 = document.createElement("option");
                    option_2.value = gt;
                    option_2.text = gt;
                    danhsach_gioitinh.appendChild(option_1);
                    danhsach_gioitinh.appendChild(option_2);
                    var optionnhomlamviec = document.createElement("option");
                    optionnhomlamviec.value = tt.manhom;
                    optionnhomlamviec.text = tt.tennhom;
                    danhsach_nhomlamviec.appendChild(optionnhomlamviec);
                    danhsach_noilamviec.value = nhom;
                    danhsach_sdt.value = tt.sdt;
                    danhsach_diachi.value = tt.diachi;
                    danhsach_trinhdo.value = tt.trinhdo;
                    danhsach_dantoc.value = tt.dantoc;
                    danhsach_tongiao.value = tt.tongiao;
                    danhsach_cccd.value = tt.sochungminh;
                    danhsach_ngaycap.value = tt.ngaycap;
                    danhsach_noicap.value = tt.noicap;
                    danhsach_ngaybatdaulam.value = tt.ngaybatdaulam;
                    danhsach_ngaykyhopdong.value = tt.ngaykyhopdong;
                    danhsach_filename.value = tt.hinhanh;
                    danhsach_previewUpload.src = tt.hinhanh;
                    danhsach_trangthai.checked = false; 
                    if(tt.trangthai == "dang_lam")
                    {
                        danhsach_trangthai.checked = true; 
                    }
                    //nhómdanhsach_trangthai
                    $.each(dsnhom, function (i, item) {
                        $("#danhsach_nhomlamviec").append(
                            $("<option>", {
                                value: item.manhom,
                                text: item.tennhom,
                            })
                        );
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    $("#danh_sach_cap_nhat_ttcn").click(function () {
        var trang_thai = "da_nghi";
        if(danhsach_trangthai.checked){
            trang_thai = "dang_lam";
        }
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/cap_nhat_thong_tin_ca_nhan",
                method: "POST",
                data: {
                    trangchu_manhansu: danhsach_manhansu.value,
                    trangchu_tennhansu: danhsach_tennhansu.value,
                    trangchu_ngaysinh: danhsach_ngaysinh.value,
                    trangchu_gioitinh: danhsach_gioitinh.value,
                    trangchu_nhomlamviec: danhsach_nhomlamviec.value,
                    trangchu_noilamviec: danhsach_noilamviec.value,
                    trangchu_sdt: danhsach_sdt.value,
                    trangchu_diachi: danhsach_diachi.value,
                    trangchu_trinhdo: danhsach_trinhdo.value,
                    trangchu_dantoc: danhsach_dantoc.value,
                    trangchu_tongiao: danhsach_tongiao.value,
                    trangchu_cccd: danhsach_cccd.value,
                    trangchu_ngaycap: danhsach_ngaycap.value,
                    trangchu_noicap: danhsach_noicap.value,
                    trangchu_ngaybatdaulam: danhsach_ngaybatdaulam.value,
                    trangchu_ngaykyhopdong: danhsach_ngaykyhopdong.value,
                    trangchu_hinhanh: danhsach_filename.value,
                    trangchu_trangthai: trang_thai,
                },
                success: function (response) {
                    alert(response.message);                    
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
});

//TAB-MODAL
const tabList = $("#trang_chu_tabs .nav-link");
tabList.each(function (index, element) {
    $(element).on("click", function () {
        $("#trang_chu_tabs .nav-link.active").removeClass("active");
        $(element).addClass("active");
        $(".tab-content.active").removeClass("active");
        var id = $(this).data("id");
        $("#" + id).addClass("active");
    });
});
