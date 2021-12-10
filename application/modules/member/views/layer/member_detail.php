<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-22
 */
?>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">회원정보</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row px-2">
        <div class="kt-section">
            <h4 class="kt-section__title"> 기본 정보</h4>
            <table class="table-list">
                <colgroup>
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                    <col style="width:12.5%;" />
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">아이디</th>
                    <th scope="col">닉네임</th>
                    <th scope="col">휴대폰번호</th>
                    <th scope="col">이메일</th>
                    <th scope="col">초대코드</th>
                    <th scope="col">가입일시</th>
                    <th scope="col">최근접속일시</th>
                    <th scope="col">비밀번호관리</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?=($info->status=="9")?"(탈퇴회원) ":"";?>
                        <?=$info->login_id;?>
                    </td>
                    <td><?=$info->nickname;?></td>
                    <td><?=format_phone($info->hp)?></td>
                    <td><?=$info->email;?></td>
                    <td><?=$info->invite_code;?></td>
                    <td><?=str_replace(" ", "<br>", $info->join_date);?></td>
                    <td><?=str_replace(" ", "<br>", $info->login_date);?></td>
                    <td>
                        <?php
                        if ( $info->status != "9" ) {
                            ?>
                            <button type="button" class="btn btn-sm btn-danger btn-change-password" data-seq="<?=$info->seq?>">임시 비밀번호 발송</button>
                        <?php
                        } // End if
                            ?>
                    </td>
                </tr>
                </tbody>
                <thead>
                <tr>
                    <th scope="col">어드바이저</th>
                    <th scope="col">PG사</th>
                    <th scope="col">정기결제수단</th>
                    <th colspan="2" scope="col">결제정보</th>
                    <th scope="col">결제일시</th>
                    <th scope="col">익월예정일</th>
                    <th scope="col">결제상품코드</th>
                    <th scope="col">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?=$info->advisor;?></td>
                    <td>다날</td>
                    <td>신용카드</td>
                    <td colspan="2">
                        <?php
                        if ( isset($info->subscription->seq) ) {
                            echo $info->subscription->card_name;
                            echo " / ";
                            echo $info->subscription->card_no;
                        }
                        else {
                            echo "-";
                        } // End if
                        ?>
                    </td>
                    <td><?=(isset($info->subscription->seq))?$info->subscription->mod_date:"-";?></td>
                    <td><?=(isset($info->subscription->seq))?$info->subscription->next_date:"-";?></td>
                    <td><?=(isset($info->subscription->seq))?$info->subscription->item_cd:"-";?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    if (isset($info->delivery->seq)) {
        ?>
        <div class="kt-separator kt-separator--border-dashed kt-separator--space-sm"></div>
        <div class="row px-2">
            <div class="kt-section">
                <h4 class="kt-section__title"> 배송지 정보</h4>
                <table class="table-list">
                    <colgroup>
                        <col style="width:20%;"/>
                        <col style="width:20%;"/>
                        <col style="width:20%;"/>
                        <col style="width:20%;"/>
                        <col style="width:20%;"/>
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">수신인</th>
                        <th scope="col">연락처</th>
                        <th scope="col">우편번호</th>
                        <th scope="col">주소</th>
                        <th scope="col">배송메모</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$info->delivery->receiver;?></td>
                        <td><?=format_phone($info->delivery->phone);?></td>
                        <td><?=$info->delivery->zipcode;?></td>
                        <td><?=$info->delivery->address;?> <?=$info->delivery->address_detail;?></td>
                        <td><?=$info->delivery->memo;?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } // End if
    ?>
    <div class="kt-separator kt-separator--border-dashed kt-separator--space-sm"></div>
    <div class="row px-2">
        <div class="kt-section">
            <h4 class="kt-section__title"> 관리자 고객 메모</h4>
            <table class="table-list" id="tblMemo">
                <colgroup>
                    <col style="width:12%;"/>
                    <col style="width:auto;"/>
                    <col style="width:10%;"/>
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">등록일시</th>
                    <th scope="col">메모내용</th>
                    <th scope="col">작성자</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <?php
        $attributes = array('class' => 'kt-form', 'id' => 'frmMemberMemo', 'method' => 'POST', 'enctype' => 'multipart/form-data');
        echo form_open('', $attributes);
            ?>
            <input type="hidden" id="lyr_member_seq" name="lyr_member_seq" value="<?=$info->seq;?>" />
        <div class="kt-section">
            <table class="table-write">
                <colgroup>
                    <col>
                    <col style="width:10%">
                </colgroup>
                <tbody>
                <tr>
                    <td><textarea class="form-control" id="lyr_member_memo" name="lyr_member_memo" style="height:5rem;" placeholder="고객 메모 입력"></textarea></td>
                    <td><button class="btn btn-info btn-memo-write">메모등록</button></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        echo form_close();
            ?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
</div>

<input type="hidden" id="lyr_p" value="1" />

<script type="text/javascript">
    $(document).ready(function() {

        //등급변경
        $(this).off('click', '.btn-change-grade').on('click', '.btn-change-grade', function(e) {
            e.preventDefault();

            var _member_seq         = $(this).data('seq');
            var _before_grade       = $('#lyr_member_before_grade').val();
            var _before_grade_str   = get_grade_string(_before_grade);
            var _after_grade        = $('#lyr_member_grade').val();
            var _after_grade_str    = get_grade_string(_after_grade);
            var _msg = "";

            if ( _before_grade != _after_grade ) {
                _msg = "[" + _before_grade_str + "] 에서 [" + _after_grade_str + "](으)로 변경하시겠습니까?";

                var _grade_ok = function() {
                    $.ajax({
                        type: 'POST',
                        url: '/member/member_grade_change_process_ajax',
                        dataType: 'json',
                        data : {
                            'member_seq'    : _member_seq,
                            'change_grade'  : _after_grade
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            swal.fire('error :' + xhr.status);
                            return false;
                        },
                        success: function (data) {
                            if(data.result === "SUCCESS"){
                                swal.fire(data.msg);

                            }else{
                                swal.fire(data.msg);
                                return false;
                            }
                        },
                        complete: function () {
                        }
                    }); //ajax end
                }

                var _grade_cancel = function() {
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
                        _grade_ok()
                    } else if (result.dismiss === 'cancel') {
                        _grade_cancel()
                    }
                });

            }
        });

        //정기결제 내역
        $(this).off('click', '.btn-layer-subscription-history').on('click', '.btn-layer-subscription-history', function(e) {
            e.preventDefault();

            var seq = $(this).data('seq');
            $('#modal-form .modal-content').load('/member/member_subscription/' + seq, function() {});
        });

        //메모 등록 클릭 시
        $(this).off('click', '.btn-memo-write').on('click', '.btn-memo-write', function(e) {
            e.preventDefault();

            if ($('#lyr_member_memo').val().split(" ").join("") == "") {
                swal.fire("내용을 입력해 주세요.");
                return false;
            } // End if

            var _memo_create_ok = function () {
                $.ajax({
                    type: 'POST',
                    url: '/member/memo_create_process_ajax',
                    dataType: 'json',
                    data : $("#frmMemberMemo").serialize(),
                    error: function (xhr, textStatus, errorThrown) {
                        swal.fire('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {
                        if(data.result === "SUCCESS") {
                            $('#lyr_member_memo').val('');
                            get_member_memo_list();
                        } else {
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            };

            var _memo_create_cancel = function () {
                //PASS
            };

            swal.fire({
                title: __TITLE__,
                html: "등록된 메모는 수정 및 삭제가 불가능합니다.<br />등록하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _memo_create_ok()
                } else if (result.dismiss === 'cancel') {
                    _memo_create_cancel()
                }
            });
        })

        get_member_memo_list();
    });

    var get_member_memo_list = function() {
        //console.log('get_member_list');
        $.ajax({
            type: 'POST',
            url: '/member/member_memo_ajax',
            dataType: 'html',
            data : { 'member_seq' : <?=$info->seq;?> },
            error: function (xhr, textStatus, errorThrown) {
                swal.fire('error :' + xhr.status);
                return false;
            },
            success: function (data) {
                $('#tblMemo tbody').html(data);
            }
        }); //ajax end
    }

    var get_grade_string = function(grade)
    {
        var _str = "";
        switch ( grade ) {
            case "0" : _str = "일반회원"; break;
            case "1" : _str = "멤버십회원"; break;
            case "4" : _str = "연결제회원"; break;
            case "5" : _str = "정기결제회원"; break;
            case "6" : _str = "준회원"; break;
            case "9" : _str = "만료회원"; break;
            default : _str = "일반회원"; break;
        }
        return _str;
    }
</script>