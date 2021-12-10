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
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select id="sch_key" name="sch_key" class="form-control">
                                <option value="email" <?=($sch_key == "email")?"SELECTED":"";?>>이메일</option>
                                <option value="nickname" <?=($sch_key == "nickname")?"SELECTED":"";?>>관리자명</option>
                            </select>
                        </div>
                        <input type="text" id="sch_str" name="sch_str" class="form-control" value="<?=$sch_str;?>">
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
            <h3 class="kt-portlet__head-title">관리자목록</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <button type="button" class="btn btn-sm btn-primary btn-create">관리자등록</button>
        </div>
    </div>
<!--    <div class="kt-portlet__head">
        <div class="kt-portlet__head-toolbar">
            <button type="button" class="btn btn-sm btn-primary btn-delete">삭제</button>
        </div>
        <div class="kt-portlet__head-toolbar">
            <button type="button" class="btn btn-sm btn-primary btn-">이용정지</button>
        </div>
        <div class="kt-portlet__head-toolbar">
            <button type="button" class="btn btn-sm btn-primary btn-">이용정지해제</button>
        </div>

        <div class="kt-portlet__head-toolbar">
            <select id="perpage" name="perpage" class="form-control">
                <option value="20">20개보기</option>
            </select>
        </div>
    </div>-->
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:7%">
                    <col style="">
                    <col style="">
                    <col style="">
                    <col style="">
                    <col style="">
                    <col style="">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">이메일</th>
                    <th scope="col">관리자명</th>
                    <th scope="col">관리자등급</th>
                    <th scope="col">관리자권한</th>
                    <th scope="col">휴대폰</th>
                    <th scope="col">가입일</th>
                    <th scope="col">OTP 관리</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($lists as $key => $row) {
                    ?>
                    <tr>
                        <td><?=$row->no?></td>
                        <td>
                            <?php
                            if ($row->seq == $this->_admin->seq) {
                                ?>
                                <a href="#" class="cell-title__link btn-edit" data-seq="<?=$row->seq;?>"><?=$row->email?></a>
                                <?php
                            }
                            else {
                                echo $row->email;
                            } // End if
                            ?>
                        </td>
                        <td><?=$row->name?></td>
                        <td><?php if($row->admin_level === '9') {
                                echo("슈퍼관리자");
                            } else if($row->admin_level === '5') {
                                echo("관리자");
                            } else {
                                echo("운영자");
                            }
                            ?></td>
                        <td><?=($row->permission_seq) ? $row->permission : ""?></td>
                        <td><?=$this->secure->decrypt($row->hp_s)?></td>
                        <td><?=$row->reg_date?></td>
                        <td>
                            <?php
                            if( intVal($row->otp_status) == 1 || intVal($row->otp_status) == 3 ) { ?>
                                <button type="button"  class="btn btn-info btn-sm btn-otp" data-seq="<?=$row->seq?>" data-status="2">승인</button>
                                <?php
                            }

                            if( intVal($row->otp_status) == 1 || intVal($row->otp_status) == 2 ) { ?>
                                <button type="button"  class="btn btn-danger btn-sm btn-otp" data-seq="<?=$row->seq?>" data-status="3">거부</button>
                                <?php
                            }

                            if( intVal($row->otp_status) == 1 || intVal($row->otp_status) == 2 || intVal($row->otp_status) == 3 ) { ?>
                                <button type="button"  class="btn btn-dark btn-sm btn-otp" data-seq="<?=$row->seq?>" data-status="4">초기화</button>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                } // End foreach

                if (count($lists) === 0) {
                    echo "<tr><td colspan='9' style='line-height: 300px;text-align: center;'>관리자 정보가 없습니다.</td></tr>";
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

        $(".btn-create").on("click", function (e) {
            e.preventDefault();
            location.href = "/admin/create";
        });

        $(".btn-del").on("click", function (e) {
            e.preventDefault();

            var seq = $(this).data("seq");

            var ok = function () {


                $.ajax({
                    type: 'GET',
                    url: '/admin/delete_ajax/' + seq,
                    dataType: 'json',
                    error: function (xhr, textStatus, errorThrown) {
                        alert('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {

                        if (data.result === "SUCCESS") {

                            ablex.alert.success(data.msg, __TITLE__, window.location.href);

                        } else {

                            ablex.alert.error(data.msg);

                            return false;
                        }

                    },
                    complete: function () {
                    }
                }); //ajax end
            };

            var cancel = function () {
                ablex.notify.message("관리자 삭제를 취소했습니다.");
            };

            ablex.confirm("해당 관리자를 삭제하시겠습니까?", __TITLE__, ok, cancel);

        });

        //수정
        $(".btn-edit").on("click", function (e) {
            e.preventDefault();
            location.href = "/admin/edit/" + $(this).data("seq");
        });

        $(".btn-otp").on("click", function (e) {
            e.preventDefault();

            var _seq = $(this).data("seq");
            var _otp_status = $(this).data('status');

            var _otp_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/otp_status_process_ajax',
                    dataType: 'json',
                    data : {
                        seq : _seq,
                        status : _otp_status
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
                                    location.reload();
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
                html: "OTP 상태를 변경하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _otp_ok()
                } else if (result.dismiss === 'cancel') {
                    return false;
                }
            });

        });

        //사용여부 변경
        $(".btn-use-toggle").on("switchChange.bootstrapSwitch", function (e, state) {
            e.preventDefault();

            var $this = $(this);
            var seq = $this.data("seq");
            var use = ($this.data("use") == "1" ? "0" : "1");

            var ok = function () {

                $.ajax({
                    type: 'GET',
                    url: '/admin/use_ajax/' + seq + "/" + use,
                    dataType: 'json',
                    error: function (xhr, textStatus, errorThrown) {
                        alert('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {

                        if (data.result === "SUCCESS") {

                            ablex.alert.success(data.msg);
                            $this.data("use", use);

                        } else {

                            ablex.alert.error(data.msg);
                            $this.bootstrapSwitch('toggleState');
                            return false;
                        }

                    },
                    complete: function () {
                    }
                }); //ajax end
            };

            var cancel = function () {
                ablex.notify.message("정보 변경을 취소했습니다.");
                $this.bootstrapSwitch('toggleState');
            };

            if (state === true) {
                ablex.confirm("해당 관리자 계정의 사용여부를 사용으로 변경하시겠습니까?", __TITLE__, ok, cancel);
            } else {
                ablex.confirm("해당 관리자 계정의 사용여부를 미사용으로 변경하시겠습니까?", __TITLE__, ok, cancel);
            }

        });

        $(".btn-search").on("click", function (e) {
            e.preventDefault();
            if (!$("#sch_str").val()) {
                swal.fire('검색어를 입력해 주세요.');
                return false;
            }

            $("#frmSearch").submit();
        });

        $(".btn-search-init").on("click", function (e) {
            e.preventDefault();
            location.href = "/admin/lists";
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
