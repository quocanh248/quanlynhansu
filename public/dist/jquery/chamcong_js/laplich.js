//Khai báo
var ca = document.getElementById("ca");
var giovao = document.getElementById("giovao");
var giora = document.getElementById("giora");
var thoigiannghi = document.getElementById("thoigiannghi");
var ipbophan = document.getElementById("dsbophan");
var laplich_nhom = document.getElementById("laplich_nhom");
var laplich_tim = document.getElementById("laplich_tim");
var ngaybatdau = document.getElementById("ngaybatdau");
var ngayketthuc = document.getElementById("ngayketthuc");
var input_tag = document.getElementById("input_tag");

document.addEventListener("DOMContentLoaded", function () {
    //Chọn Nhân sự lập lịch
    const selectAllCheckbox = document.getElementById("select-all");
    const otherCheckboxes = document.querySelectorAll(".ipcheckbox");
    selectAllCheckbox.addEventListener("change", function () {
        const isChecked = this.checked;
        otherCheckboxes.forEach(function (checkbox) {
            // Kiểm tra xem checkbox thuộc phần đã lọc hay không
            const tr = checkbox.closest("tr");
            if (tr && tr.style.display !== "none") {
                checkbox.checked = isChecked;
            }
        });
    });
    otherCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            const allChecked = [...otherCheckboxes].every(function (checkbox) {
                return checkbox.checked;
            });

            selectAllCheckbox.checked = allChecked;
        });
    });
    //Lập lịch    
    const lapLichButton = document.getElementById("lap_lich");
    lapLichButton.addEventListener("click", function () {
        const selectedManhansuList = getSelectedManhansuList();
        console.log(ngaybatdau.value, ngayketthuc.value, thoigiannghi.value);
        if(!selectedManhansuList || giora.value == "" || ngayketthuc.value == "" || ngaybatdau.value == "")
        {
            alert("Chọn thông tin đầy đủ!");
        }else{
            $(function() {      
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });          
                $.ajax({
                    url: '/laplich',
                    method: 'POST',
                    data: {
                        listnhansu: selectedManhansuList,
                        ngaybatdau: ngaybatdau.value,
                        ngayketthuc: ngayketthuc.value,
                        giovao: giovao.value,
                        giora: giora.value,
                        thoigiannghi: thoigiannghi.value,                        
                    },
                    success: function(response) {
                        if (response.success) {
                            alert("Lập lịch thành công");
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });           
        }
    });
    function getSelectedManhansuList() {
        const selectedManhansuList = [];
        const checkboxes = document.querySelectorAll('.ipcheckbox:checked');
        checkboxes.forEach(function (checkbox) {
            const tr = checkbox.closest('tr');
            const manhansu = tr.querySelector('td:nth-child(3)').textContent.trim();
            selectedManhansuList.push(manhansu);
        });
        return selectedManhansuList;
    }
    const them_tag_Button = document.getElementById("them_tag");
    them_tag_Button.addEventListener("click", function () {
        const selectedManhansuList = getSelectedManhansuList();       
        if(!selectedManhansuList || input_tag.value == "")
        {
            alert("Chọn nhân sự để thêm tag");
        }else{
            $(function() {      
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });          
                $.ajax({
                    url: '/them_tag',
                    method: 'POST',
                    data: {
                        listnhansu: selectedManhansuList,
                        tag: input_tag.value                                       
                    },
                    success: function(response) {
                        if (response.success) {
                            alert("Thêm tag thành công");
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });           
        }
    });

});
//
laplich_nhom.addEventListener("change", function () {
    laplich_tim.click();
});
//Lấy thông tin ca
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
//Lọc theo nhóm
ipbophan.addEventListener("input", timKiem);
function timKiem() {
    var filter = ipbophan.value.toUpperCase();
    var table = document.getElementById("bangdsnhansubody");
    var tr = table.getElementsByTagName("tr");

    for (var i = 0; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            var txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
//Lập lịch
