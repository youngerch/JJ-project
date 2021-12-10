<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-29
 */

$attributes = array('class' => 'kt-form', 'id' => 'frmLyrCodeUpdate', 'method' => 'POST');
echo form_open('', $attributes);
?>
<input type="hidden" id="lyr_cate_seq" name="lyr_cate_seq" value="<?=$category->seq;?>" />

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">카테고리 관리 <!--small class="kt-font-danger">(한국어만 입력 시 다른 언어도 동일 정보로 저장)</small--></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table-write">
        <colgroup>
            <col style="width:30%">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="stock_cd">카테고리 코드</label></th>
            <td>
                <input type="text" class="form-control" value="<?=$category->cate_code;?>" readonly />
            </td>
        </tr>
        <tr>
            <th scope="row">카테고리명 (한국어)</th>
            <td>
                <input type="text" id="lyr_cate_name_kor" name="lyr_cate_name_kor" class="form-control" value="<?=$category->cate_name_kor;?>" />
            </td>
        </tr>
        <!--tr>
            <th scope="row">카테고리명 (중국어)</th>
            <td>
                <input type="text" id="lyr_cate_name_chn" name="lyr_cate_name_chn" class="form-control" value="<?=$category->cate_name_chn;?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">카테고리명 (일본어)</th>
            <td>
                <input type="text" id="lyr_cate_name_jpn" name="lyr_cate_name_jpn" class="form-control" value="<?=$category->cate_name_jpn;?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">카테고리명 (영어)</th>
            <td>
                <input type="text" id="lyr_cate_name_eng" name="lyr_cate_name_eng" class="form-control" value="<?=$category->cate_name_eng;?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">카테고리명 (베트남어)</th>
            <td>
                <input type="text" id="lyr_cate_name_vnm" name="lyr_cate_name_vnm" class="form-control" value="<?=$category->cate_name_vnm;?>" />
            </td>
        </tr-->
        <tr>
            <th scope="row">관리 옵션1</th>
            <td>
                <input type="text" id="lyr_cate_option1" name="lyr_cate_option1" class="form-control" value="<?=$category->cate_option1;?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">관리 옵션2</th>
            <td>
                <input type="text" id="lyr_cate_option2" name="lyr_cate_option2" class="form-control" value="<?=$category->cate_option2;?>" />
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <?php
    if ($category->is_del == "Y") {
    ?>
        <button type="button" class="btn btn-dark btn-repair" data-seq="<?=$category->seq;?>">복구</button>
    <?php
    }
    else {
        ?>
        <button type="button" class="btn btn-danger btn-delete" data-seq="<?=$category->seq;?>">삭제</button>
    <?php
    } // End if
        ?>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
    <button type="button" class="btn btn-primary btn-save">저장</button>
</div>
<?php
echo form_close();
?>

<script type="text/javascript">
    $(document).ready(function() {

        $(this).off('blur', '#lyr_cate_code').on('blur', '#lyr_cate_code', function (e) {
            e.preventDefault();

            let _parent = $('#lyr_parent_code').val();
            let _val = $(this).val().split(' ').join('');
            if ( _val != "" ) {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/category_code_duplication_check_ajax',
                    dataType: 'json',
                    data : {
                        'parent_code'   : _parent,
                        'cate_code'     : _val
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        swal.fire('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {
                        if(data.result === "SUCCESS"){
                            //PASS
                        }else{
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            }
        });

        $(this).off('click', '.btn-save').on('click', '.btn-save', function (e) {
            e.preventDefault();

            if ($('#lyr_cate_name_kor').val() == "") {
                swal.fire('카테고리명(한국어)를 입력해 주세요.');
                return false;
            }

            var _category_update_ok = function () {

                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/category_update_process_ajax',
                    dataType: 'json',
                    data : $('#frmLyrCodeUpdate').serialize(),
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
                                    location.href = window.location.href;
                                }
                            });
                        }
                        else {
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            };

            var _category_update_cancel = function () {
                //PASS
            };

            swal.fire({
                title: __TITLE__,
                html: "카테고리를 수정 하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    _category_update_ok()
                } else if (result.dismiss === 'cancel') {
                    _category_update_cancel()
                }
            });
        });

        $(this).off('click', '.btn-delete').on('click', '.btn-delete', function(e) {
            e.preventDefault();

            var _seq = $(this).data('seq');

            var _category_delete_ok = function() {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/category_delete_process_ajax',
                    dataType: 'json',
                    data : {
                        lyr_cate_seq : _seq
                    },
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
                                    location.href = window.location.href;
                                }
                            });
                        }
                        else {
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            };

            var _category_delete_cancel = function() {
                //PASS
            };

            swal.fire({
                title: __TITLE__,
                html: "카테고리를 삭제 하시겠습니까?<br />삭제 시 연관 오류 발생을 체크해주세요.",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    _category_delete_ok()
                } else if (result.dismiss === 'cancel') {
                    _category_delete_cancel()
                }
            });
        });

        $(this).off('click', '.btn-repair').on('click', '.btn-repair', function(e) {
            e.preventDefault();

            var _seq = $(this).data('seq');

            var _category_repair_ok = function() {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/category_repair_process_ajax',
                    dataType: 'json',
                    data : {
                        lyr_cate_seq : _seq
                    },
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
                                    location.href = window.location.href;
                                }
                            });
                        }
                        else {
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            };

            var _category_repair_cancel = function() {
                //PASS
            };

            swal.fire({
                title: __TITLE__,
                html: "카테고리를 복구 하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    _category_repair_ok()
                } else if (result.dismiss === 'cancel') {
                    _category_repair_cancel()
                }
            });
        });
    });
</script>