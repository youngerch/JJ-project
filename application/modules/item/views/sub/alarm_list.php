<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-09
 */

$attributes = array('class' => 'kt-form', 'id' => 'frmSearch', 'method' => 'GET');
echo form_open('', $attributes);
?>
<input type="hidden" id="current_page" name="current_page" value="<?=$currentPage;?>" />
<input type="hidden" id="per_page" value="<?=$perPage;?>" />
<input type="hidden" id="total_count" value="<?=$totalCount;?>" />

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">검색조건입력</h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="search-form">
            <div class="form-group">
                <label class="col-form-label">상품</label>
                <div class="col-form-block form-inline">
                    <select id="item_cd" name="item_cd" class="form-control">
                        <option value="">-- 전체 상품 --</option>
                        <?php
                        foreach ($items as $key => $row) {
                            $str_sales_status = "";
                            switch ($row->sales_status) {
                                case "1" :
                                    $str_sales_status = "판매중";
                                    break;
                                case "5" :
                                    $str_sales_status = "품절";
                                    break;
                                case "9" :
                                    $str_sales_status = "판매중지";
                                    break;
                                default :
                                    $str_sales_status = "-";
                                    break;
                            }
                            ?>
                            <option value="<?=$row->item_cd;?>" <?=($row->item_cd==$item_cd)?"SELECTED":"";?>>[<?=$str_sales_status;?>] <?=$row->item_name;?></option>
                        <?php
                        }
                            ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">기간조회</label>
                <div class="col-form-block form-inline">
                    <select id="date_type" name="date_type" class="form-control">
                        <option value="reg_date" <?=($date_type == "reg_date")?"SELECTED":"";?>>신청일 기준</option>
                        <option value="send_date" <?=($date_type == "send_date")?"SELECTED":"";?>>발송일 기준</option>
                    </select>
                    <div class="input-group date">
                        <input type="text" id="date_start" name="date_start" class="form-control input-datepicker" value="<?=$date_start;?>" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar"></i></span>
                        </div>
                    </div>
                    <div class="tilde">~</div>
                    <div class="input-group date">
                        <input type="text" id="date_end" name="date_end" class="form-control input-datepicker" value="<?=$date_end;?>" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar"></i></span>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-set-date" data-select-date="2021-01-01">전체</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("now"));?>">오늘</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-3 day"));?>">3일</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-7 day"));?>">7일</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-1 month"));?>">1개월</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-2 month"));?>">2개월</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-3 month"));?>">3개월</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">발송상태</label>
                <div class="col-form-block form-inline">
                    <div class="kt-radio-inline pt-2">
                        <label class="kt-radio">
                            <input type="radio" name="is_send" value="" <?=($is_send == "")?"CHECKED":"";?>> 전체<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="is_send" value="N" <?=($is_send == "N")?"CHECKED":"";?>> 발송대기<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="is_send" value="Y" <?=($is_send == "Y")?"CHECKED":"";?>> 발송완료<span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">회원검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select id="sch_key" name="sch_key" class="form-control">
                                <option value="login_id" <?=($sch_key == "login_id")?"checked":"";?>>아이디</option>
                                <option value="hp" <?=($sch_key == "hp")?"checked":"";?>>휴대폰번호</option>
                                <option value="nickname" <?=($sch_key == "nickname")?"checked":"";?>>닉네임</option>
                            </select>
                        </div>
                        <input type="text" id="sch_str" name="sch_str" class="form-control enter-to-click" data-target=".btn-search" value="<?=$sch_str;?>">
                    </div>
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
?>

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">알림신청/발송목록</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <!--button type="button" class="btn btn-sm btn-primary btn-item-create">상품등록</button-->
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:60px"> <!-- no -->
                    <col> <!-- 아이디 -->
                    <col> <!-- 닉네임 -->
                    <col> <!-- 휴대폰번호 -->
                    <col> <!-- 회원가입일시 -->
                    <col> <!-- 최근접속일시 -->
                    <col> <!--알림상품코드-->
                    <col> <!--알림상품명-->
                    <col> <!--알림신청일시-->
                    <col> <!--발송상태-->
                    <col> <!--발송일시-->
                    <col> <!--관리-->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">아이디</th>
                    <th scope="col">닉네임</th>
                    <th scope="col">휴대폰번호</th>
                    <th scope="col">회원가입일시</th>
                    <th scope="col">최근접속일시</th>
                    <th scope="col">알림상품코드</th>
                    <th scope="col">알림상품명</th>
                    <th scope="col">알림신청일시</th>
                    <th scope="col">발송상태</th>
                    <th scope="col">발송일시</th>
                    <th scope="col">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($lists as $key => $row):
                    $str_status = "";
                    if ( $row->is_send == "Y" ) {
                        $str_status = "발송완료";
                    } else {
                        $str_status = "발송대기";
                    } // End if

                    $str_sales_status = "";
                    switch ($row->sales_status) {
                        case "1" :
                            $str_sales_status = "판매중";
                            break;
                        case "5" :
                            $str_sales_status = "품절";
                            break;
                        case "9" :
                            $str_sales_status = "판매중지";
                            break;
                        default :
                            $str_sales_status = "-";
                            break;
                    }
                    ?>
                    <tr>
                        <td><?=$row->no?></td>
                        <td><a href="#" class="cell-title__link btn-member-detail" data-seq="<?=$row->member_seq;?>" data-toggle="modal" data-target="#modal-member-form"><?=$row->login_id;?></a></td>
                        <td><a href="#" class="cell-title__link btn-member-detail" data-seq="<?=$row->member_seq;?>" data-toggle="modal" data-target="#modal-member-form"><?=$row->nickname;?></a></td>
                        <td>(<?=$row->international;?>) <?=str_replace("-withdrawal", "", $row->hp);?></td>
                        <td><?=$row->join_date;?></td>
                        <td><?=$row->login_date;?></td>
                        <td><?=$row->item_cd;?></td>
                        <td class="text-left">
                            <a href="/item/detail/<?=$row->item_seq;?>" class="cell-title__link" target="_blank">
                                [<?=$str_sales_status;?>] <?=$row->item_name;?> (<?=$row->unit_count;?> <?=$row->unit_name;?>)
                            </a>
                        </td>
                        <td><?=$row->reg_date;?></td>
                        <td><?=$str_status;?></td>
                        <td><?=$row->send_date;?></td>
                        <td>
                            <?php
                            if ($row->is_send == "Y") {
                            ?>
                                <button type='button' rel='tooltip' class='btn btn-info btn-sm disabled' data-seq='<?=$row->seq;?>'>발송</button>
                            <?php
                            } else {
                            ?>
                                <button type='button' rel='tooltip' class='btn btn-dark btn-sm btn-send-sms' data-seq='<?=$row->seq;?>'>발송</button>
                            <?php
                            } // End if
                            ?>
                        </td>
                    </tr>
                <?php
                endforeach;

                if(count($lists) === 0):
                    echo "<tr><td colspan='12' style='line-height: 300px;text-align: center;'>검색된 정보가 없습니다.</td></tr>";
                endif;
                ?>
                </tbody>
            </table>
        </div>
        <div class="kt-pagination kt-pagination--brand">
            <ul class="kt-pagination__links" id="pagination"></ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $("#date_start, #date_end").datepicker({
            orientation: "right bottom",
            todayBtn: "linked"
        });

        $('.btn-set-date').on('click', function(e) {
            e.preventDefault();

            $('.btn-set-date').removeClass('btn-primary').addClass('btn-secondary');
            $(this).removeClass('btn-secondary').addClass('btn-primary');

            $("#date_start").val($(this).data('select-date'));
            $("#date_end").val("<?=Date('Y-m-d');?>");
        });

        $(this).off('click', '.btn-search').on('click', '.btn-search', function(e) {
            e.preventDefault();

            $('#current_page').val(1);
            $('#frmSearch').submit();
        });

        $(this).off('click', '.btn-search-init').on('click', '.btn-search-init', function(e) {
            e.preventDefault();

            location.href = '/item/alarm';
        });

        // 상품 상세 / 수정
        $(this).off('click', '.btn-send-sms').on('click', '.btn-send-sms', function(e) {
            e.preventDefault();

            var _seq = $(this).data('seq');

            var _alarm_send_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/item/alarm_send_process_ajax',
                    dataType: 'json',
                    data : { seq : _seq },
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
                                    location.href = '/item/alarm';
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
                html: "알림 정보를 발송하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _alarm_send_ok()
                } else if (result.dismiss === 'cancel') {
                    return false;
                }
            });
        });

        get_pagination();
    });

    var get_pagination = function() {

        var started = false;
        var _total_count    = $('#total_count').val();
        var _current_page   = $('#current_page').val();
        var _per_page       = $('#per_page').val();

        if ( _total_count == 0 ) {
            $("#pagination").html('');
            return;
        }

        $("#pagination").paging(_total_count, {
            format: '[< . (qq -) nnnncnnnn (- pp) >]',
            perpage: _per_page,
            lapping: 0,
            page: _current_page, // we await hashchange() event
            onSelect: function (page) {
                if (started) {
                    $('#current_page').val(page);
                    $('#frmSearch').submit();
                } else {
                    started = true;
                }
                return false;
            },
            onFormat: function (type) {
                switch (type) {
                    case "block": // n and c
                        if (this.value != _current_page)
                            return "<li><a href='javascript:void(0);'>" + this.value + "</a></li>";
                        else {
                            return "<li class='kt-pagination__link--active'><a href='javascript:void(0);'>" + this.value + "</a></li></span>";
                        }
                    case "first": // <<
                        return "<li class='kt-pagination__link--first'><a href='javascript:void(0);'><i class='fa fa-angle-double-left kt-font-brand'></i></a></li>";

                    case "prev": // <
                        return "<li class='kt-pagination__link--prev'><a href='javascript:void(0);'><i class='fa fa-angle-left kt-font-brand'></i></a></li>";

                    case "next": // >
                        return "<li class='kt-pagination__link--next'><a href='javascript:void(0);'><i class='fa fa-angle-right kt-font-brand'></i></a></li>";

                    case "last": // >>
                        return "<li class='kt-pagination__link--last'><a href='javascript:void(0);'><i class='fa fa-angle-double-right kt-font-brand'></i></a></li>";

                    default:
                        return "";
                }
            }
        });
    }

</script>
