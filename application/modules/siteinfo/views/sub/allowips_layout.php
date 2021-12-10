<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-07-06
 */

$attributes = array('class' => 'kt-form', 'id' => 'frmSearch', 'method' => 'GET');
echo form_open('', $attributes);
?>

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">검색조건입력</h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="search-form">
            <div class="form-group">
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group-prepend">
                        <select id="sch_key" name="sch_key" class="form-control">
                            <option value="ai_ip" <?=($sch_key == "ai_ip")?"SELECTED":"";?>>아이피</option>
                            <option value="ai_name" <?=($sch_key == "ai_name")?"SELECTED":"";?>>명칭</option>
                        </select>
                    </div>
                    <input type="text" id="sch_str" name="sch_str" class="form-control" value="<?=$sch_str;?>">
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot kt-portlet__foot--center">
        <button type="button" class="btn btn-primary btn-wide btn-search"><i class="la la-search"></i>검색</button>
        <button type="reset" class="btn btn-info btn-wide btn-search-init"><i class="la la-refresh"></i> 초기화</button>
    </div>
</div>

<?php
echo form_close();

$attributes = array('class' => 'kt-form', 'id' => 'frmWrite', 'method' => 'POST');
echo form_open('', $attributes);
?>
<input type="hidden" id="seq" name="seq" value="0" />

<div class="kt-portlet">
    <div class="kt-portlet__body">
        <div class="search-form">
            <div class="form-group">
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group-prepend">
                        <input type="text" id="ai_ip" name="ai_ip" class="form-control numeric" value="" maxlength="15" placeholder="아이피를 입력해 주세요.">
                    </div>
                    <input type="text" id="ai_name" name="ai_name" class="form-control col-md-6" value="" maxlength="50" placeholder="명칭을 입력해 주세요.">
                    <button type="button" class="btn btn-success btn-wide btn-save mr-2"><i class="la la-pencil-square"></i> 저장</button>
                    <button type="button" class="btn btn-info btn-wide btn-init-form"><i class="la la-refresh"></i> 초기화</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo form_close();
?>

<div class="kt-portlet">
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:70px">    <!-- no -->
                    <col style="width:200px">    <!-- 아이피 -->
                    <col>    <!-- 명칭 -->
                    <col style="width:200px">    <!-- 등록일시 -->
                    <col style="width:200px">    <!-- 등록일시 -->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col" class="disabled-sorting">NO</th>
                    <th scope="col" class="disabled-sorting">아이피</th>
                    <th scope="col" class="disabled-sorting">명칭</th>
                    <th scope="col" class="disabled-sorting">등록일시</th>
                    <th scope="col" class="disabled-sorting">승인</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($lists as $key => $row) {
                    ?>
                    <tr>
                        <td><?=$row->no;?></td>
                        <td><?=$row->ai_ip;?></td>
                        <td class="text-left"><a href="#" class="cell-title__link btn-ips-detail" data-seq="<?=$row->seq;?>" data-ip="<?=$row->ai_ip;?>" data-name="<?=$row->ai_name;?>"><?=$row->ai_name;?></a></td>
                        <td><?=$row->reg_date;?></td>
                        <td>
                            <span class="kt-switch kt-switch--dark">
                                <label>
                                    <input type="checkbox" name="is_use" value="Y" data-seq="<?=$row->seq;?>" <?=($row->is_use=="Y")?"CHECKED":"";?>>
                                    <span></span>
                                </label>
                            </span>
                        </td>
                    </tr>
                    <?php
                } // End foreach

                if(count($lists) === 0) {
                    echo "<tr><td colspan='4' style='line-height: 200px;text-align: center;'>검색된 정보가 없습니다.</td></tr>";
                } // End if
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(this).off('click', '.btn-search').on('click', '.btn-search', function(e) {
            e.preventDefault();

            $("#frmSearch").submit();
        });

        $(this).off('click', '.btn-search-init').on('click', '.btn-search-init', function(e) {
            e.preventDefault();
            location.href = '/siteinfo/allowips';
        });

        $(this).off('click', '.btn-save').on('click', '.btn-save', function(e) {
            e.preventDefault();

            var _seq = $('#seq').val();
            var _ip = $.trim($('#ai_ip').val().split(" ").join(""));
            var _name = $.trim($('#ai_name').val().split(" ").join(""));

            if (isNaN(_seq)) {
                swal.fire('처리에 문제가 발생하였습니다.<br>새로고침 후 이용해주세요.');
                return false;
            }

            if (_ip == "" ) {
                swal.fire('아이피를 입력해 주세요.');
                return false;
            }

            if (!chkIP(_ip)) {
                swal.fire('아이피 형식이 맞지 않습니다.');
                return false;
            }

            if (_name == "" ) {
                swal.fire('명칭을 입력해 주세요.');
                return false;
            }

            if (parseInt(_seq) == 0) {
                _msg = "접속 아이피를 등록하시겠습니까?";
            }
            else {
                _msg = "접속 아이피 정보를 수정하시겠습니까?";
            }

            var _allowips_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/siteinfo/allowips_process_ajax',
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
                                    location.href = '/siteinfo/allowips';
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

            var _allowips_cancel = function()
            {
                //PASS
            }

            swal.fire({
                title: __TITLE__,
                html: _msg,
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _allowips_ok()
                } else if (result.dismiss === 'cancel') {
                    _allowips_cancel()
                }
            });
        });

        $(this).off('click', '.btn-init-form').on('click', '.btn-init-form', function(e) {
            e.preventDefault();

            $('#seq').val(0);
            $('#ai_ip').val('');
            $('#ai_name').val('');
        });

        $(this).off('click', '.btn-ips-detail').on('click', '.btn-ips-detail', function(e) {
            e.preventDefault();

            var _seq    = $(this).data('seq');
            var _ip     = $(this).data('ip');
            var _name   = $(this).data('name');

            $('#seq').val(_seq);
            $('#ai_ip').val(_ip);
            $('#ai_name').val(_name);
        });

        $(this).off('click', 'input[type=checkbox]').on('click', 'input[type=checkbox]', function() {
            var _seq = $(this).data('seq');
            var _use = ($(this).prop('checked')) ? "Y" : "N";

            console.log('_use : ', _use);
        });
    });
</script>

