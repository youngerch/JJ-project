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
            <h3 class="kt-portlet__head-title">상품 설정</h3>
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
                            <col style="width:10%">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row"><label for="stock_cd">대표코드</label></th>
                            <td colspan="3">
                                <input type="text" id="stock_cd" name="stock_cd"  class="form-control" maxlength="13">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">상품코드</th>
                            <td colspan="3">
                                <div class="form-control-plaintext"><?=$item_cd;?></div>
                                <input type="hidden" id="item_cd" name="item_cd" value="<?=$item_cd;?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">상품 구성</th>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="text" id="unit_count" name="unit_count" class="form-control numeric" placeholder="개수 설정 (숫자만)">
                                    </div>
                                    <select id="unit_name" name="unit_name" class="form-control col-4">
                                        <option value="">구성 단위</option>
                                        <option value="개">개</option>
                                        <option value="박스">박스</option>
                                        <option value="세트">세트</option>
                                    </select>
                                </div>
                            </td>
                            <th scope="row">교환리스트</th>
                            <td>
                                <span class="kt-switch kt-switch--dark">
                                    <label>
                                        <input type="checkbox" id="is_exchange" name="is_exchange" value="Y" CHECKED>
                                        <span></span>
                                    </label>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">노출</th>
                            <td>
                                <span class="kt-switch kt-switch--dark">
                                    <label>
                                        <input type="checkbox" id="is_display" name="is_display" value="Y" CHECKED>
                                        <span></span>
                                    </label>
                                </span>
                            </td>
                            <th scope="row">메인노출</th>
                            <td>
                                <span class="kt-switch kt-switch--dark">
                                    <label>
                                        <input type="checkbox" id="is_main" name="is_main" value="Y">
                                        <span></span>
                                    </label>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sales_status">상태</label></th>
                            <td colspan="3">
                                <div class="col-form-block form-inline">
                                    <div class="kt-radio-inline pt-2">
                                        <label class="kt-radio">
                                            <input type="radio" name="sales_status" value="1" CHECKED > 판매중<span></span>
                                        </label>
                                        <label class="kt-radio">
                                            <input type="radio" name="sales_status" value="5" > 품절<span></span>
                                        </label>
                                        <label class="kt-radio">
                                            <input type="radio" name="sales_status" value="9" > 판매중지<span></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="stock_cd">상품명</label></th>
                            <td colspan="3">
                                <input type="text" id="item_name" name="item_name"  class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">판매가</th>
                            <td>
                                <input type="text" id="sale_price" name="sale_price"  class="form-control numeric">
                            </td>
                            <th scope="row">기본출고수량</th>
                            <td>
                                <input type="text" id="release_count" name="release_count"  class="form-control numeric">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">재고수량</th>
                            <td>
                                <input type="text" id="warehousing_count" name="warehousing_count"  class="form-control numeric">
                            </td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <th scope="row">재고알림수량</th>
                            <td>
                                <input type="text" id="alert_count" name="alert_count"  class="form-control numeric">
                            </td>
                            <th scope="row">재고경고수량</th>
                            <td>
                                <input type="text" id="warning_count" name="warning_count"  class="form-control numeric">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">정기결제인원</th>
                            <td>
                                <input type="text" id="subscription_limit" name="subscription_limit"  class="form-control numeric">
                            </td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <th scope="row">알림기준인원</th>
                            <td>
                                <input type="text" id="alarm_sms" name="alarm_sms"  class="form-control numeric">
                            </td>
                            <th scope="row">알림발송인원</th>
                            <td>
                                <input type="text" id="alarm_send" name="alarm_send"  class="form-control numeric">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">상품설명</th>
                            <td colspan="3">
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">상품정보고시</th>
                            <td colspan="3">
                                <textarea name="information" id="information" class="form-control"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                메인 이미지
                            </th>
                            <td colspan="3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="upfile" name="upfile">
                                    <label class="custom-file-label" for="upfile">첨부할 파일은 선택해주세요.</label>
                                </div>
                                <small class="kt-font-danger">(이미지 사이즈는 640*640 권장)</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xl-4"></div>
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

<script src="<?=base_url('assets');?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let _item_cd = $("#item_cd").val();

        CKEDITOR.replace( 'description', {
            height: 400,
            filebrowserUploadUrl: '/common/ckeditor_upload/item/' + _item_cd
        });

        CKEDITOR.replace( 'information', {
            height: 400,
            filebrowserUploadUrl: '/common/ckeditor_upload/item/' + _item_cd
        });

        // 상품 정보 저장 버튼 클릭 시
        $(this).off('click', '.btn-save').on('click', '.btn-save', function(e) {
            e.preventDefault();

            let description = CKEDITOR.instances.description;
            $("#description").val(description.getData());

            let information = CKEDITOR.instances.information;
            $("#information").val(information.getData());

            if ($.trim($('#stock_cd').val()) == "") {
                swal.fire('대표코드를 입력해 주세요.');
                return false;
            }

            if (isNaN($('#unit_count').val())) {
                swal.fire('상품 구성 개수를 입력해주세요.');
                return false;
            }

            if ($('#unit_name option:selected').val() == "") {
                swal.fire('상품 구성 단위를 선택해주세요.');
                return false;
            }

            if ($.trim($('#item_name').val()) == "") {
                swal.fire('상품명을 입력해주세요.');
                return false;
            }

            if (isNaN($('#sale_price').val())) {
                swal.fire('판매가를 입력해주세요.');
                return false;
            }

            if (isNaN($('#release_count').val())) {
                swal.fire('기본출고수량을 입력해주세요.');
                return false;
            }

            if (isNaN($('#warehousing_count').val())) {
                swal.fire('재고수량을 입력해주세요.');
                return false;
            }

            if (isNaN($('#alert_count').val())) {
                swal.fire('재고알림수량을 입력해주세요.');
                return false;
            }

            if (isNaN($('#warning_count').val())) {
                swal.fire('재고경고수량을 입력해주세요.');
                return false;
            }

            if ($('#upfile').val() == "") {
                swal.fire('메인 이미지를 선택해주세요.');
                return false;
            }

            var _item_create_ok = function()
            {
                $('#frmCreate').ajaxFormUnbind();
                $('#frmCreate').ajaxForm({
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    url: '/item/create_process_ajax',
                    dataType: 'json',
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
                                    location.href = '/item/lists';
                                }
                            });
                        }
                        else {
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); // End ajaxform

                $('#frmCreate').submit();
            }

            var _item_create_cancel = function()
            {
                //PASS
            }

            swal.fire({
                title: __TITLE__,
                html: "입력하신 내용으로 상품을 등록 하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _item_create_ok()
                } else if (result.dismiss === 'cancel') {
                    _item_create_cancel()
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
                    location.href = '/item/lists';
                } else if (result.dismiss === 'cancel') {
                    //PASS
                }
            });
        });

    });


</script>
