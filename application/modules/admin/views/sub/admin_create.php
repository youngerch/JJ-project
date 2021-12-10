<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-09
 */

$attributes = array('class' => 'kt-form', 'id' => 'frmCreate', 'method' => 'POST', 'enctype' => 'multipart/form-data');
echo form_open('', $attributes);
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">관리자 등록</h3>
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
                        <tr>
                            <th scope="row"><label for="email">이메일</label></th>
                            <td>
                                <div class="form-group form-inline">
                                    <input type="text" id="email" name="email" class="form-control" placeholder="이메일" style="width:300px;">
                                    <input type="hidden" name="email_chk" id="email_chk">
                                    <button class="btn btn-light ml-2 btn-email-check"><i class="nc-icon nc-refresh-69"></i> 중복체크</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="password">비밀번호</label></th>
                            <td>
                                <input class="form-control" type="password" id="password" name="password">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="password_re">비밀번호 확인</label></th>
                            <td>
                                <input class="form-control" type="password" id="password_re" name="password_re">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="name">관리자명</label></th>
                            <td>
                                <input class="form-control" type="text" id="name" name="name">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="hp">휴대폰 번호</label></th>
                            <td>
                                <input class="form-control" type="text" id="hp" name="hp">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="level">관리자 등급</label></th>
                            <td>
                                <select class="form-control" id="level" name="level" style="width: 300px;">
                                    <option value = "">선택하세요</option>
                                    <?php
                                    foreach ($level_list as $key => $row ) {
                                        ?>
                                        <option value = "<?=$key?>"> <?=$row?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="permission">관리자 권한</label></th>
                            <td>
                                <select class="form-control" id="permission" name="permission" style="width: 300px;">
                                    <option value = "">선택하세요</option>
                                    <?php
                                    foreach ($permission_list as $key => $row ) {
                                        ?>
                                        <option value = "<?=$row->seq?>"> <?=$row->name?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="department">소속</label></th>
                            <td>
                                <input class="form-control" type="text" id="department" name="department">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="position">부서</label></th>
                            <td>
                                <input class="form-control" type="text" id="position" name="position">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="is_use">사용여부</label></th>
                            <td>
                                <div class="col-form-block form-inline">
                                    <div class="kt-radio-inline pt-2">
                                        <label class="kt-radio">
                                            <input type="radio" id="is_use_1" name="is_use" value="1" CHECKED>예<span class="form-check-sign"></span>
                                        </label>
                                        <label class="kt-radio">
                                            <input type="radio" id="is_use_0" name="is_use" value="0">아니오<span class="form-check-sign"></span>
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
    $(".btn-save").on("click", function(e){
        e.preventDefault();

        if(!$("#email").val()){
            swal.fire("관리자 이메일을 입력해 주세요.");
            return false;
        }

        if($("#email_chk").val() !== "Y"){
            swal.fire("이메일 중복체크를 실행해 주세요.");
            return false;
        }

        if(!$("#password").val()){
            swal.fire("비밀번호를 입력해 주세요.");
            return false;
        }

        if(!$("#password_re").val()){
            swal.fire("비밀번호를 다시 한번 입력해 주세요.");
            return false;
        }

        if($("#password").val() !== $("#password_re").val()){
            swal.fire("비밀번호가 맞지 않습니다.");
            return false;
        }

        if(!$("#name").val()){
            swal.fire("관리자명을 입력해 주세요.");
            return false;
        }

        if(!$("#hp").val()){
            swal.fire("휴대폰 번호를 입력해 주세요.");
            return false;
        }

        // if(!$("#level").val()){
        //     swal.fire("관리자 등급을 선택해 주세요.");
        //     return false;
        // }

        if(!$("#permission").val()){
            swal.fire("관리자 권한을 선택해 주세요.");
            return false;
        }

        if(!$("#department").val()){
            swal.fire("소속을 입력해 주세요.");
            return false;
        }

        if(!$("#position").val()){
            swal.fire("부서를 입력해 주세요.");
            return false;
        }

        var ok = function () {


            $.ajax({
                type: 'POST',
                url: '/admin/create_process_ajax',
                dataType: 'json',
                data : $("#frmCreate").serialize(),
                error: function (xhr, textStatus, errorThrown) {
                    swal.fire('error :' + xhr.status);
                    return false;
                },
                success: function (data) {

                    if(data.result === "SUCCESS"){

                        swal.fire({
                            title: __TITLE__,
                            text: data.msg,
                            timer: 1000,
                            onOpen: function() {
                                swal.showLoading()
                            }
                        }).then(function(result) {
                            if (result.dismiss === 'timer') {
                                location.href = '/admin/lists';
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
            swal.fire("관리자 등록을 취소했습니다.");
        };

        swal.fire({
            title: __TITLE__,
            html: "새로운 관리자를 등록하시겠습니까?",
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

    $(".btn-email-check").on("click", function(e){
        e.preventDefault();

        if(!$("#email").val()){
            swal.fire("관리자 이메일을 입력해 주세요.");
            return false;
        }

        $("#email_chk").val("");

        $.ajax({
            type: 'GET',
            url: '/admin/email_check_ajax/' + encodeURIComponent($("#email").val()),
            dataType: 'json',
            error: function (xhr, textStatus, errorThrown) {
                alert('error :' + xhr.status);
                return false;
            },
            success: function (data) {

                if(data.result === "SUCCESS"){

                    swal.fire(data.msg);
                    $("#email_chk").val("Y");

                }else{

                    swal.fire(data.msg);
                    $("#email").val("");

                    return false;
                }

            },
            complete: function () {
            }
        }); //ajax end

    });

    $(".btn-cancel").on("click", function(e){
        e.preventDefault();
        location.href = "/admin/lists";
    });

</script>
