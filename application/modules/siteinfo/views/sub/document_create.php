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
            <h3 class="kt-portlet__head-title">문서 관리</h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="row">
            <div class="table-wrapper">
                <input type="hidden" name="board_type" value="1" /> <!-- 공지사항 -->
                <table class="table-write">
                    <colgroup>
                        <col style="width:10%">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">구분코드</th>
                        <td>
                            <input type="text" id="code" name="code"  class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">제목</th>
                        <td>
                            <input type="text" id="title" name="title"  class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">내용</th>
                        <td>
                            <textarea name="content" id="content" class="form-control" style="height:20rem;"></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
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

<script src="<?php echo base_url('assets')?>/js/jquery.form.min.js"></script>
<script src="<?php echo base_url('assets')?>/js/plugins/jasny-bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('.btn-save').on('click', function(e) {
            e.preventDefault();

            if ( $('#code').val().split(" ").join("") == "" ) {
                swal.fire('코드를 입력해 주세요.');
                return false;
            }

            if ( $('#title').val().split(" ").join("") == "" ) {
                swal.fire('제목을 입력해 주세요.');
                return false;
            }

            var _document_create_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/document_create_process_ajax',
                    dataType: 'json',
                    data : $('#frmCreate').serialize(),
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
                                    location.href = '/siteinfo/docs';
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

            var _document_create_cancel = function()
            {
                //PASS
            }

            swal.fire({
                title: __TITLE__,
                html: "문건을 등록하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _document_create_ok()
                } else if (result.dismiss === 'cancel') {
                    _document_create_cancel()
                }
            });
        });

        // 취소 버튼 클릭 시
        $(this).off('click', '.btn-cancel').on('click', '.btn-cancel', function(e) {
            e.preventDefault();

            swal.fire({
                title: __TITLE__,
                html: "현재 작성 중인 내용이 있습니다.<br />취소하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    location.href = '/siteinfo/docs';
                } else if (result.dismiss === 'cancel') {
                    //PASS
                }
            });
        });

    });
</script>