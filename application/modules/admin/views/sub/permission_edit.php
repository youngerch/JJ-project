<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-09
 */

$attributes = array('class' => 'kt-form', 'id' => 'frmEdit', 'method' => 'POST', 'enctype' => 'multipart/form-data');
echo form_open('', $attributes);
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">운영자 권한 설정</h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="row">
            <div class="col-xl-8">
                <div class="table-wrapper">
                    <table class="table-write">
                        <colgroup>
                            <col style="width:10%">
                            <col>
                        </colgroup>
                        <tbody>
                        <input type="hidden" id="seq" name="seq" value="<?=$permission->seq?>">
                        <tr>
                            <th scope="row"><label for="name">등급명</label></th>
                            <td>
                                <input type="text" id="name" name="name" value="<?=$permission->name?>" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <input type="hidden" class="form-control" name="_accessible_menu" id="_accessible_menu">
                            <th scope="row"><label>접근가능 메뉴</label></th>
                            <td>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" id="accessible_menu_all" name="accessible_menu_all" <?=(count($accessible_arr)==8)?"CHECKED":"";?>>전체<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="accessible_menu[]" value="member" <?=(in_array("member", $accessible_arr))?"CHECKED":"";?>>회원관리<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="accessible_menu[]" value="cscenter" <?=(in_array("cscenter", $accessible_arr))?"CHECKED":"";?>>고객센터<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="accessible_menu[]" value="item" <?=(in_array("item", $accessible_arr))?"CHECKED":"";?>>상품관리<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="accessible_menu[]" value="operation" <?=(in_array("operation", $accessible_arr))?"CHECKED":"";?>>운영관리<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="accessible_menu[]" value="admin" <?=(in_array("admin", $accessible_arr))?"CHECKED":"";?>>운영자관리<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-form-block form-inline">
                                    <div class="kt-checkbox-inline">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="accessible_menu[]" value="siteinfo" <?=(in_array("siteinfo", $accessible_arr))?"CHECKED":"";?>>사이트설정<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="is_use">사용여부</label></th>
                            <td>
                                <div class="col-form-block form-inline">
                                    <div class="kt-radio-inline pt-2">
                                        <label class="kt-radio">
                                            <input type="radio" id="is_use_1" name="is_use" value="1" <?=$permission->is_use === "1" ? "CHECKED" : "";?>>예<span class="form-check-sign"></span>
                                        </label>
                                        <label class="kt-radio">
                                            <input type="radio" id="is_use_0" name="is_use" value="0" <?=$permission->is_use === "0" ? "CHECKED" : "";?>>아니오<span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot kt-portlet__foot--center">
        <button type="button" class="btn btn-secondary btn-wide btn-cancel">취소</button>
        <button type="button" class="btn btn-primary btn-wide btn-save">저장</button>
    </div>
</div>

<?php
echo form_close();
?>

<script>

    $('#accessible_menu_all').on('click', function(e) {
        $("input[name='accessible_menu[]']").prop("checked", $(this).prop('checked'));
    });

    $("input[name='accessible_menu[]']").on('click', function(e) {
        var _len = $("input[name='accessible_menu[]']").length;
        var _checked_len = $("input[name='accessible_menu[]']:checked").length;

        if (_len == _checked_len) {
            $('#accessible_menu_all').prop('checked', true);
        }
        else {
            $('#accessible_menu_all').prop('checked', false);
        } // End if
    });

    $(".btn-save").on("click", function(e){
        e.preventDefault();

        if(!$("#name").val()){
            swal.fire("등급명을 입력해 주세요.");
            return false;
        }

        var _checked_len = $("input[name='accessible_menu[]']:checked").length;

        if (_checked_len == 0) {
            swal.fire("선택된 접근가능 메뉴가 없습니다.<br>접근가능 메뉴를 선택해주세요.");
            return false;
        }

        var _checked_val = '';

        $('input:checkbox[name="accessible_menu[]"]').each(function() {

            if(this.checked){//checked 처리된 항목의 값
                _checked_val += this.value + "|";
            }

        });

        $("#_accessible_menu").val(_checked_val.slice(0,-1));

        var ok = function () {

            $.ajax({
                type: 'POST',
                url: '/admin/permission_edit_process_ajax',
                dataType: 'json',
                data : $("#frmEdit").serialize(),
                error: function (xhr, textStatus, errorThrown) {
                    alert('error :' + xhr.status);
                    return false;
                },
                success: function (data) {

                    if(data.result === "SUCCESS"){
                        swal.fire({
                            text: data.msg,
                            timer: 1000,
                            onOpen: function() {
                                swal.showLoading()

                            }
                        }).then(function(result) {
                            if (result.dismiss === 'timer') {
                                location.href = '/admin/permission';
                            }
                        });

                    }else{
                        swal.fire(data.msg);
                        return false;
                    }

                },
                complete: function () {
                }
            }); //ajax end
        };

        var cancel = function () {
            swal.fire("관리자 등급 수정을 취소했습니다.");
        };

        swal.fire({
            title: __TITLE__,
            html: "관리자 등급을 수정하시겠습니까?",
            showCancelButton: true,
            confirmButtonText: '확인',
            cancelButtonText: '취소',
            reverseButtons: true
        }).then(function(result){
            if (result.value) {
                ok()
            } else if (result.dismiss === 'cancel') {
                cancel()
            }
        });
    });

    $(".btn-cancel").on("click", function(e){
        e.preventDefault();
        location.href = "/admin/permission";
    });

</script>
