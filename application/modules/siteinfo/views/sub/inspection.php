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
            <h3 class="kt-portlet__head-title">점검 관리</h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="row">
            <div class="table-wrapper">
                <table class="table-write">
                    <colgroup>
                        <col style="width:10%">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">상태</th>
                        <td>
                    <span class="kt-switch kt-switch--dark">
                        <label>
                            <input type="checkbox" id="is_active" name="is_active" value="Y" <?=($info->is_active=="Y")?"CHECKED":"";?>>
                            <span></span>
                        </label>
                    </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">점검 시작일시</th>
                        <td>
                            <div class="form-check-radio form-check-inline">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" id="s_date" name="s_date" readonly style="cursor: pointer;" value="<?=substr($info->date_start, 0, 10);?>">
                                    <div class="input-group-append">
                                <span class="input-group-text" style="padding:8px;">
                                    <i class="la la-calendar"></i>
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check-radio form-check-inline">
                                <select id="s_hour" name="s_hour" class="form-control kt-selectpicker">
                                    <?php
                                    $s_hour = substr($info->date_start, 11, 2);
                                    for($i=0;$i<24;$i++):?>
                                        <option value="<?=sprintf("%02d",$i)?>" <?=(intVal($s_hour)==$i)?"SELECTED":"";?> ><?=sprintf("%02d",$i)?>시</option>
                                    <?php
                                    endfor;?>
                                </select>
                            </div>
                            <div class="form-check-radio form-check-inline">
                                <select id="s_minute" name="s_minute" class="form-control kt-selectpicker">
                                    <?php
                                    $s_minute = substr($info->date_start, 14, 2);
                                    for($i=0;$i<60;$i++):?>
                                        <option value="<?=sprintf("%02d",$i)?>" <?=(intVal($s_minute)==$i)?"SELECTED":"";?> ><?=sprintf("%02d",$i)?>분</option>
                                    <?php
                                    endfor;?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">점검 종료일시</th>
                        <td>
                            <div class="form-check-radio form-check-inline">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" id="e_date" name="e_date" readonly style="cursor: pointer;" value="<?=substr($info->date_end, 0, 10);?>">
                                    <div class="input-group-append">
                                <span class="input-group-text" style="padding:8px;">
                                    <i class="la la-calendar"></i>
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check-radio form-check-inline">
                                <select id="e_hour" name="e_hour" class="form-control kt-selectpicker">
                                    <?php
                                    $e_hour = substr($info->date_end, 11, 2);
                                    for($i=0;$i<24;$i++):?>
                                        <option value="<?=sprintf("%02d",$i)?>" <?=(intVal($e_hour)==$i)?"SELECTED":"";?> ><?=sprintf("%02d",$i)?>시</option>
                                    <?php
                                    endfor;?>
                                </select>
                            </div>
                            <div class="form-check-radio form-check-inline">
                                <select id="e_minute" name="e_minute" class="form-control kt-selectpicker">
                                    <?php
                                    $e_minute = substr($info->date_end, 14, 2);
                                    for($i=0;$i<60;$i++):?>
                                        <option value="<?=sprintf("%02d",$i)?>" <?=(intVal($e_minute)==$i)?"SELECTED":"";?> ><?=sprintf("%02d",$i)?>분</option>
                                    <?php
                                    endfor;?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">제목</th>
                        <td>
                            <input type="text" id="title" name="title"  class="form-control" value="<?=$info->title?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">점검 내용</th>
                        <td>
                            <textarea name="content" id="content" class="form-control" style="min-height:300px;"><?=$info->content?></textarea>
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

        $('.datepicker').datepicker({
            orientation: "right bottom",
            todayBtn: "linked"
        });

        $('.btn-save').on('click', function(e) {
            e.preventDefault();

            var _startdatetime = $('#s_date').val() + " " + $('#s_hour option:selected').val() + ":" + $('#s_minute option:selected').val();
            var _enddatetime = $('#e_date').val() + " " + $('#e_hour option:selected').val() + ":" + $('#e_minute option:selected').val();

            if ( _startdatetime > _enddatetime ) {
                swal.fire('노출 시작일시보다 이전 날짜(시간)으로 등록하실 수가 없습니다.');
                return false;
            }

            if ( $('#title').val().split(" ").join("") == "" ) {
                swal.fire('제목을 입력해 주세요.');
                return false;
            }

            if ( $('#content').val().split(" ").join("") == "" ) {
                swal.fire('점검 내용을 입력해 주세요.');
                return false;
            }

            var _inspection_update_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/inspection_process_ajax',
                    dataType: 'json',
                    data : $("#frmCreate").serialize(),
                    cache : false,
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
                                    location.href = '/siteinfo/inspection';
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
            swal.fire({
                title: __TITLE__,
                html: "점검관리 정보를 등록하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _inspection_update_ok()
                } else if (result.dismiss === 'cancel') {
                    return false;
                }
            });
        });

    });
</script>