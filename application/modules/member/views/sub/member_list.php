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
        </div>`
    </div>
    <div class="kt-portlet__body">
        <div class="search-form">
            <div class="form-group">
                <label class="col-form-label">기간조회</label>
                <div class="col-form-block form-inline">
                    <select id="date_type" name="date_type" class="form-control">
                        <option value="A.join_date" <?=($date_type == "A.join_date")?"SELECTED":"";?>>가입일 기준</option>
                        <option value="A.login_date" <?=($date_type == "A.login_date")?"SELECTED":"";?>>접속일 기준</option>
                        <?php
                        switch ( $status ):
                            case 1 : break;
                            case 5 : echo "<option value='A.mod_date' class='disabled-sorting'>휴면일시</option>"; break;
                            case 9 : echo "<option value='A.leave_date' class='disabled-sorting'>탈퇴일시</option>"; break;
                        endswitch;
                        ?>
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
                        <button type="button" class="btn btn-primary btn-set-date" data-select-date="2021-09-01">전체</button>
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
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select id="sch_key" name="sch_key" class="form-control">
                                <option value="login_id_s" <?=($sch_key == "login_id_s")?"SELECTED":"";?>>아이디</option>
                                <option value="hp_s" <?=($sch_key == "hp_s")?"SELECTED":"";?>>휴대폰번호</option>
                                <option value="nickname" <?=($sch_key == "nickname")?"SELECTED":"";?>>닉네임</option>
                                <option value="invite_code" <?=($sch_key == "invite_code")?"SELECTED":"";?>>초대코드</option>
                            </select>
                        </div>
                        <input type="text" id="sch_str" name="sch_str" class="form-control enter-to-click" data-target=".btn-search" value="<?=$sch_str;?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">유형별</label>
                <div class="col-form-block form-inline">
                    <select id="sch_auto_bill" name="sch_auto_bill" class="form-control">
                        <option value=""  <?=($sch_auto_bill == "")?"SELECTED":"";?>>-- 정기결제 여부 --</option>
                        <option value="Y" <?=($sch_auto_bill == "Y")?"SELECTED":"";?>>정기결제 신청</option>
                        <option value="N" <?=($sch_auto_bill == "N")?"SELECTED":"";?>>정기결제 미신청</option>
                        <option value="D" <?=($sch_auto_bill == "D")?"SELECTED":"";?>>정기결제 해지</option>
                    </select>
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

<!-- begin:: kt-portlet -->
<div class="kt-portlet">
    <div class="kt-portlet__body">
        <div class="kt-section kt-section--last">
            <h3 class="kt-section__info kt-font-primary"><b><i class="fa flaticon2-setup"></i> [ 조회기간 : <?=$date_start;?> ~ <?=$date_end;?> ] </b></h3>
            <div class="kt-section__content">
                <ul class="dot-list">
                    <li>총 <?=number_format($totalCount)?> 명</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- end:: kt-portlet -->

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title"><?=($status=="1")?"회원목록":"탈퇴회원목록";?></h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <select id="perpage" name="perpage" class="form-control">
                <option value="20">20개보기</option>
            </select>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:70px">    <!-- no -->
                    <col>    <!-- 초대코드 -->
                    <col>    <!-- 아이디 -->
                    <col>    <!-- 닉네임 -->
                    <col>    <!-- 휴대폰번호 -->
                    <col>    <!-- 정기결제 -->
                    <col>    <!-- 가입일시 -->
                    <col>    <!-- 최근활동일시 -->
                    <col>    <!-- 회원정보 -->
                    <col>    <!-- 로그인 -->
                    <col>    <!-- 임시 비밀번호 발송 / 휴면일시 / 탈퇴일시 -->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">초대코드</th>
                    <th scope="col">아이디</th>
                    <th scope="col">닉네임</th>
                    <th scope="col">휴대폰번호</th>
                    <th scope="col">정기결제</th>
                    <th scope="col">가입일시</th>
                    <th scope="col">최근활동일시</th>
                    <th scope="col">회원정보</th>
                    <th scope="col">로그인</th>
                    <?php
                    switch ( $status ):
                        case 1 : echo "<th scope='col'>임시 비밀번호 발송</th>"; break;
                        case 9 :  echo "<th scope='col'>탈퇴일시</th>"; break;
                    endswitch;
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($lists as $key => $row) {
                    $auto_bill = "미신청";
                    if (isset($row->subscription->seq)) {
                        if (trim($row->subscription->bill_key) != "") {
                            $auto_bill = "신청";
                        } else {
                            $auto_bill = "해지";
                        } // End if
                    } // End if
                    ?>
                    <tr>
                        <td><?=$row->no;?></td>
                        <td><?=$row->invite_code;?></td>
                        <td><a href="#" class="cell-title__link btn-member-detail" data-seq="<?=$row->seq;?>" data-toggle="modal" data-target="#modal-member-form"><?=$row->login_id;?></a></td>
                        <td><a href="#" class="cell-title__link btn-member-detail" data-seq="<?=$row->seq;?>" data-toggle="modal" data-target="#modal-member-form"><?=$row->nickname;?></a></td>
                        <td>(<?=$row->international;?>) <?=str_replace("-withdrawal", "", $this->secure->decrypt($row->hp_s));?></td>
                        <td><?=$auto_bill;?></td>
                        <td><?=$row->join_date?></td>
                        <td><?=$row->login_date?></td>
                        <td><button type='button' rel='tooltip' class='btn btn-dark btn-sm btn-member-detail' data-toggle="modal" data-target="#modal-member-form" data-seq='<?=$row->seq;?>'>회원정보</button></td>
                        <td><button type='button' rel='tooltip' class='btn btn-danger btn-sm btn-login' data-seq='<?=$row->seq;?>'>로그인</button></td>
                        <td>
                            <?php
                            switch ( $status ):
                                case 1 : echo "<button type='button' rel='tooltip' class='btn btn-info btn-sm btn-password' data-seq='".$row->seq."'>임시 비밀번호 발송</button>"; break;
                                case 5 : echo $row->mod_date; break;
                                case 9 : echo $row->leave_date; break;
                            endswitch;
                            ?>
                        </td>
                    </tr>
                <?php
                } // End foreach

                if (count($lists) === 0) {
                    echo "<tr><td colspan='16' style='line-height: 200px;text-align: center;'>조회된 회원 정보가 없습니다.</td></tr>";
                } // End if
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

            $('#frmSearch').submit();
        });

        $(this).off('click', '.btn-search-init').on('click', '.btn-search-init', function(e) {
            e.preventDefault();

            location.href = '/member/lists/1';
        });

        $(this).off('click', '.btn-login').on('click', '.btn-login', function(e) {
            e.preventDefault();

            var _seq = $(this).data('seq');
            var winBookcafe = window.open();
            if ( winBookcafe ) {
                winBookcafe.location.href = '<?=__WWW_DOMAIN__;?>/auth/admin_login_process/'+_seq+'/<?=$this->_admin->seq;?>';
            }
        })

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
