var manhansu = document.getElementById("manhansu");
var tennhansu = document.getElementById("tennhansu");
var list_nhan_su = document.getElementById("list_nhan_su");
var vaitro = document.getElementById("vaitro");
var xacnhanmatkhau = document.getElementById("xacnhanmatkhau");
var matkhau = document.getElementById("matkhau");
var tt_manhansu = document.getElementById("tt_manhansu");
var tt_tennhansu = document.getElementById("tt_tennhansu");
var tt_ds_nhom = document.getElementById("tt_ds_nhom");
var tt_vaitro = document.getElementById("tt_vaitro");
var tt_matkhau = document.getElementById("tt_matkhau");
var tbody = document.getElementById("bangvaitroBody");
manhansu.addEventListener("change", function () {
    var sele = manhansu.value;
    var options = list_nhan_su.options;
    var optiontext, optionvalue;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value == sele) {
            optiontext = options[i].label;
            optionvalue = options[i].value;
            break;
        }
    }
    tennhansu.value = optiontext;
});
$(document).ready(function () {
    //Cấp tài khoản
    $("#cap_tai_khoan").click(function () {
        if (
            manhansu.value == "" ||
            matkhau.value == "" ||
            xacnhanmatkhau.value == "" ||
            vaitro.value == ""
        ) {
            alert("nhập đầy đủ thông tin");
        } else if (matkhau.value != xacnhanmatkhau.value) {
            alert("Mật khẩu không khớp!");
        } else {
            $(function () {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    url: "/them_tai_khoan",
                    method: "POST",
                    data: {
                        manhansu: manhansu.value,
                        matkhau: matkhau.value,
                        vaitro: vaitro.value,
                    },
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                });
            });
        }
    });
    //Chi tiết
    $(".btn-info").click(function () {
        var button = $(this);
        var userid = button.val();
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/lay_thong_tin_user",
                method: "POST",
                data: {
                    id: userid,
                },
                success: function (response) {
                    var tt = response.tt;                   
                    var ds = response.ds;                   
                    tt_manhansu.value = tt.manhansu;
                    tt_tennhansu.value = tt.tennhansu;
                    tt_vaitro.value = tt.role;                  
                    tbody.innerHTML = "";
                    if (ds.length > 0) {         
                        $.each(ds, function (i, item) {
                            var row = document.createElement("tr");                           
                            var cell1 = document.createElement("td");                          
                            cell1.textContent = item.manhom;
                            var cell2 = document.createElement("td");                           
                            cell2.textContent = item.tennhom;
                            var button = document.createElement("button");
                            button.textContent = "Xóa";
                            button.setAttribute("type", "button");
                            button.setAttribute("value", item.manhom);
                            button.className = "btn btn-danger";
                            button.onclick = function() {            
                                this.closest('tr').remove();
                            };   
                            var td = document.createElement("td");                       
                            // Thêm button vào ô td
                            td.appendChild(button);
                            row.appendChild(cell1);
                            row.appendChild(cell2);
                            row.appendChild(td);
                            // Thêm hàng vào tbody
                            tbody.appendChild(row);
                        });
                        var dsnhom = response.dsnhom;
                        $('#tt_ds_nhom').empty();
                        $.each(dsnhom, function (i, item) {
                            $('#tt_ds_nhom').append($('<option>', {
                                value: item.manhom,
                                text: item.tennhom
                            }));
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    $('#them_nhom').on('click', function() {
        // Lấy giá trị của option được chọn
        var selectedOption = $('#tt_ds_nhom option:selected');
        var manhom = selectedOption.val();
        var tennhom = selectedOption.text();
        var row = document.createElement("tr");                           
        var cell1 = document.createElement("td");                          
        cell1.textContent = manhom;
        var cell2 = document.createElement("td");                           
        cell2.textContent = tennhom;
        var button = document.createElement("button");
        button.textContent = "Xóa";
        button.setAttribute("type", "button");
        button.setAttribute("value", manhom);
        button.className = "btn btn-danger";
        button.onclick = function() {            
            this.closest('tr').remove();
        };    
        var td = document.createElement("td");    
        td.appendChild(button);
        row.appendChild(cell1);
        row.appendChild(cell2);
        row.appendChild(td);       
        tbody.appendChild(row);
      
        // Xóa option được chọn khỏi select
        selectedOption.remove();
    });
    $('#cap_nhat_thong_tin_user').on('click', function() {       
        var manhomArray = [];    
        $('#bangvaitroBody tr').each(function() {          
            var manhom = $(this).find('td:eq(0)').text();      
            manhomArray.push(manhom);
        });        
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "/cap_nhat_thong_tin_user",
                method: "POST",
                data: {
                    manhansu: tt_manhansu.value,
                    vaitro: tt_vaitro.value,
                    matkhau: tt_matkhau.value,
                    dsnhom: manhomArray                   
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
   
});
