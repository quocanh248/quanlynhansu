var ca = document.getElementById("ca");
var manhansu = document.getElementById("manhansu");
var tennhansu = document.getElementById("tennhansu");
var manhansu_np = document.getElementById("manhansu_np");
var tennhansu_np = document.getElementById("tennhansu_np");
var giovao = document.getElementById("giovao");
var giora = document.getElementById("giora");
var ngaydiemdanh = document.getElementById("ngaydiemdanh");
var diemdanhvao = document.getElementById("diemdanhvao");
var diemdanhra = document.getElementById("diemdanhra");
var loaingaynghi = document.getElementById("loaingaynghi");
var lap_lich_nghi = document.getElementById("lap_lich_nghi");
var huy_lich_nghi = document.getElementById("huy_lich_nghi");
var lydo = document.getElementById("lydo");
ca.addEventListener("change", function () {
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            processData: false,
            contentType: false,
            type: "GET",
            dataType: "JSON",
            url: "/laythongtinca/" + ca.value,
            success: function (res) {
                console.log(res);
                giora.value = res[0].giora;
                giovao.value = res[0].giovao;
                thoigiannghi.value = res[0].sophutnghi;
            },
        });
    });
});
$(document).ready(function () {
    //Điểm danh
    $(".bt-none").click(function () {
        var button = $(this);
        var madiemdanh = button.val();
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/lay_thong_tin_diem_danh",
                method: "POST",
                data: {
                    madiemdanh: madiemdanh,
                },
                success: function (response) {
                    manhansu.value = response.manhansu;
                    ngaydiemdanh.value = response.ngaydiemdanh;
                    tennhansu.value = response.tennhansu;
                    giovao.value = response.laplichvao;
                    giora.value = response.laplichra;
                    diemdanhra.value = response.thoigianra;
                    diemdanhvao.value = response.thoigianvao;
                    thoigiannghi.value = response.thoigiannghi;
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    //Lấy thông tin nghỉ
    $(".bt-none_1").click(function () {
        var button = $(this);
        var madiemdanh = button.val();
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/lay_thong_tin_xin_nghi",
                method: "POST",
                data: {
                    madiemdanh: madiemdanh,
                },
                success: function (response) {
                    manhansu_np.value = response.manhansu;
                    tennhansu_np.value = response.tennhansu;
                    lap_lich_nghi.value = madiemdanh;
                    huy_lich_nghi.value = madiemdanh;
                    lydo.value = response.lydo;
                    loaingaynghi.value = response.maluong;
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    $("#lap_lich_nghi").click(function () {
        var button = $(this);
        var madiemdanh = button.val();
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/lap_lich_nghi",
                method: "POST",
                data: {
                    madiemdanh: madiemdanh,
                    lydo: lydo.value,
                    maluong: loaingaynghi.value,
                },
                success: function (response) {
                    if (response.success) {
                        alert("Thành công!");
                        location.reload();
                    }else{
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    $("#huy_lich_nghi").click(function () {
        var button = $(this);
        var madiemdanh = button.val();
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/huy_lich_nghi",
                method: "POST",
                data: {
                    madiemdanh: madiemdanh,                   
                },
                success: function (response) {
                    if (response.success) {
                        alert("Thành công!");
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    //Cập nhật điểm  danh
    $("#cap_nhat").click(function () {
        if (giovao.value != "") {
            $(function () {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    url: "/cap_nhat_thong_tin_diem_danh",
                    method: "POST",
                    data: {
                        manhansu: manhansu.value,
                        ngaydiemdanh: ngaydiemdanh.value,
                        giovao: giovao.value,
                        giora: giora.value,
                        diemdanhra: diemdanhra.value,
                        diemdanhvao: diemdanhvao.value,
                        thoigiannghi: thoigiannghi.value,
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("Thành công!");
                            location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                });
            });
        } else {
            alert("Chọn ca!");
        }
    });
});
