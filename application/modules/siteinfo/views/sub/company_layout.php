<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-07-08
 */

$attributes = array('class' => 'kt-form', 'id' => 'frmWrite', 'method' => 'POST', 'enctype' => 'multipart/form-data');
echo form_open('', $attributes);
?>

<div class="kt-portlet">
    <div class="kt-portlet__body">

        <div class="table-wrapper">
            <table class="table-write">
                <colgroup>
                    <col style="width:10%">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="stock_cd">회사명</label></th>
                    <td>
                        <input type="hidden" name="seq" value="<?=$info->seq;?>">
                        <input type="text" name="name"  class="form-control" value="<?=$info->name;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">대표이사</th>
                    <td>
                        <input type="text" name="ceo"  class="form-control" value="<?=$info->ceo;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">사업자등록번호</th>
                    <td>
                        <input type="text" name="bizno"  class="form-control" value="<?=$info->bizno;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">통신판매업신고번호</th>
                    <td>
                        <input type="text" name="buyno"  class="form-control" value="<?=$info->buyno;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">주소</th>
                    <td>
                        <input type="text" name="address"  class="form-control" value="<?=$info->address;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">고객센터</th>
                    <td>
                        <input type="text" name="cscenter"  class="form-control" value="<?=$info->cscenter;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">FAX</th>
                    <td>
                        <input type="text" name="fax"  class="form-control" value="<?=$info->fax;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">E-mail</th>
                    <td>
                        <input type="text" name="email"  class="form-control" value="<?=$info->email;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">카카오링크</th>
                    <td>
                        <input type="text" name="kakao"  class="form-control" value="<?=$info->kakao;?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">텔레그램링크</th>
                    <td>
                        <input type="text" name="telegram"  class="form-control" value="<?=$info->telegram;?>">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="kt-portlet__foot kt-portlet__foot--center">
        <button type="button" class="btn btn-primary btn-wide btn-save">저장</button>
    </div>
</div>

<?php
echo form_close();
?>

<script type="text/javascript">
    $(document).ready(function() {
        $(this).off('click', '.btn-save').on('click', '.btn-save', function(e) {
            var _is_empty = false;
            $('input[name="name"]').each(function() {
                if ($.trim($(this).val()) == "") {
                    _is_empty = true;
                }
            });

            if (_is_empty) {
                swal.fire('회사명은 필수 입력입니다.');
                return false;
            }

            var _company_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/company_process_ajax',
                    dataType: 'json',
                    data : $('#frmWrite').serialize(),
                    error: function (xhr, textStatus, errorThrown) {
                        swal.fire('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {
                        if(data.result === "SUCCESS") {
                            swal.fire({
                                text: data.msg,
                                timer: 1000,
                                onOpen: function() {
                                    swal.showLoading()
                                }
                            }).then(function(result) {
                                if (result.dismiss === 'timer') {
                                    location.href = '/siteinfo/company';
                                }
                            });
                        }
                        else {
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            }

            var _company_cancel = function()
            {
                //PASS
            }

            swal.fire({
                title: __TITLE__,
                html: "회사 정보를 저장하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _company_ok()
                } else if (result.dismiss === 'cancel') {
                    _company_cancel()
                }
            });
        });
    });
</script>
