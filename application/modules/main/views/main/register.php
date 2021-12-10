
<div class="wrapper">
    <div class="register-wrapper">
        <div class="register-group">
            <div class="login-logo">
                <img src="/assets/media/logos/logo.png" class="login-logo__img" alt="삼보사랑 ADMIN">
                <h1 class="login-title">사용자 등록 신청</h1>
            </div>
            <div class="register-form">

                <?php
                $attributes = array('class' => 'form', 'id' => 'registerForm');
                echo form_open('', $attributes);
                ?>
                <input type="hidden" name="is_hp_certify" id="is_hp_certify" value="N">
                <input type="hidden" name="hp_certify_seq" id="hp_certify_seq" value="" >

                <div class="form-block">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="email" class="form-label-inline">이메일 주소</label>
                        </div>
                        <input type="text" id="email" name="email" class="form-input">
                    </div>
                </div>
                <div class="form-block">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="name" class="form-label-inline">이름</label>
                        </div>
                        <input type="text" id="name" name="name" class="form-input">
                    </div>
                </div>
                <div class="form-block">
                    <label for="user_id" class="form-label-title">휴대폰 번호</label>
                    <select id="international" name="international" class="form-select mb-2">
                        <option value="82" selected>+82 (대한민국)</option>
                    </select>
                    <div class="input-group form-verify">
                        <input type="text" id="hp" name="hp" class="phone-verify-input numeric" placeholder="숫자만 입력 (-제외)">
                        <div class="input-group-append"><button type="button" id="btnSmsSubmit" class="button button-secondary button-submit-sms">발송</button></div>
                    </div>
                    <div class="input-group form-timer" style="display:none;">
                        <input type="text" id="hp_certify_no" class="form-input timer-verify-input numeric" readonly placeholder="3분 동안 유효">
                        <span class="form-verify-timer">남은 시간 : <span class="font-danger">02:59</span></span>
                        <div class="input-group-append">
                            <button type="button" id="btnSmsVerify" disabled class="button button-confirm-sms">인증</button>
                        </div>
                    </div>
                </div>
                <p class="form-alert">휴대폰 번호를 입력하세요(숫자만 입력)</p>
                <div class="form-block">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="user_password" class="form-label-inline">비밀번호</label>
                        </div>
                        <input type="password" id="user_password" name="user_password" class="form-input">
                    </div>
                </div>
                <p class="form-alert">비밀번호는 6자 이상 15자 이하, 숫자, 알파벳 혼용으로 입력해주세요.</p>

                <div class="form-block">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="user_password_re" class="form-label-inline">비밀번호 확인</label>
                        </div>
                        <input type="password" id="user_password_re" name="user_password_re" class="form-input">
                    </div>
                </div>
                <p class="form-alert">비밀번호를 한번 더 입력해주세요.</p>
                <?php
                echo form_close();
                ?>
                <div class="button-group">
                    <button type="button" class="button button-cancel btn-cancel">취소</button>
                    <button type="button" class="button button-dark button-submit btn-submit">확인</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets')?>/js/jquery.countdown.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#hp').on('change', function() {
            //변경 시 SMS 인증 무효화
            $('#is_hp_certify').val("N");
            $('#hp_certify_seq').val("");

            //인증번호 입력폼 숨김.
            $("#hp_certify_no").val("").prop("readonly", true);
            $('.form-timer').hide();
            $('.form-verify-timer').hide();
            $('#btnSmsVerify').text('인증').prop('disabled', true);
        });

        $('#btnSmsSubmit').on('click', function(e) {
            e.preventDefault();

            $("#is_hp_certify").val("N");
            $("#hp_certify_seq").val("");

            var _international = $('#international').val();
            var _mobile = $('#hp').val();
            if ( _mobile ) {

                $.ajax({
                    type: 'POST',
                    url: '/main/auth/hp_certification_ajax',
                    dataType: 'json',
                    data : {
                        'international' : _international,
                        'hp' : _mobile
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        ablex.alert.error('error :' + xhr.status,__TITLE__);
                        return false;
                    },
                    success: function (data) {

                        if(data.result === "SUCCESS") {
                            ablex.alert.success(data.msg,__TITLE__);

                            $("#hp_certify_seq").val(data.certify_seq);
                            $("#hp_certify_no").prop("readonly", false);

                            $('.form-timer').show();
                            $('.form-verify-timer').show();

                            $('#btnSmsVerify').text('인증').prop('disabled', false);

                            count_down(data.time);

                        } else if(data.result === "ERROR_ALREADY_MEMBER") {
                            ablex.alert.error(data.msg,__TITLE__);
                        } else {
                            ablex.alert.error(data.msg,__TITLE__);
                            return false;
                        }

                    },
                    complete: function () {
                    }
                }); //ajax end
            } else {
                ablex.alert.error("휴대폰번호를 입력해 주세요.");
                return false;
            }
        });

        $('#btnSmsVerify').on('click', function(e) {
            e.preventDefault();

            if(!$("#hp_certify_no").val()){
                ablex.alert.success("인증번호가 일치하지 않습니다.",__TITLE__);
                $("#hp_certify_no").focus();
                return false;
            }

            if(!$("#hp_certify_seq").val()){
                ablex.alert.success("인증문자 발송내역이 없습니다.",__TITLE__);
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/main/auth/hp_certification_response_ajax',
                dataType: 'json',
                data : {
                    'hp_certify_no' : $("#hp_certify_no").val(),
                    'hp_certify_seq': $("#hp_certify_seq").val()
                },
                error: function (xhr, textStatus, errorThrown) {
                    ablex.alert.error('error :' + xhr.status,__TITLE__);
                    return false;
                },
                success: function (data) {

                    if(data.result === "SUCCESS"){
                        ablex.alert.success(data.msg, __TITLE__);
                        $("#is_hp_certify").val("Y");
                        $("#hp_certify_no").addClass("readonly").prop("readonly", true);

                        $(".form-verify-timer .font-danger").countdown('stop');
                        $('.form-verify-timer').hide();

                        $('#btnSmsVerify').text('인증완료').prop('disabled', true);
                    }else{
                        ablex.alert.error(data.msg, __TITLE__);
                        return false;
                    }
                },
                complete: function () {
                }
            }); //ajax end
        });

        $('.btn-cancel').on('click', function(e) {
            e.preventDefault();
            location.href = '/main/auth/';
        })

        $(".btn-submit").on("click", function(e){
            e.preventDefault();

            if(!$("#email").val()){
                ablex.alert.error("이메일 주소를 입력해 주세요.");
                return false;
            }

            if(!$("#name").val()){
                ablex.alert.error("이름을 입력해 주세요.");
                return false;
            }

            if(!$("#hp").val()){
                ablex.alert.error("휴대폰번호를 입력해 주세요.");
                return false;
            }

            if($("#is_hp_certify").val() == "N" || $("#hp_certify_seq").val() == "") {
                ablex.alert.error("휴대폰번호 인증을 진행해 주세요.");
                return false;
            }

            if(!$("#user_password").val()){
                ablex.alert.error("비밀번호를 입력해 주세요.");
                return false;
            }

            if(!chkPassword($('#user_password').val())) {
                return false;
            }

            if(!$("#user_password_re").val()){
                ablex.alert.error("비밀번호를 다시 한번 더 입력해 주세요.");
                return false;
            }

            if($("#user_password").val() !== $("#user_password_re").val()){
                ablex.alert.error("비밀번호가 일치하지 않습니다.");
                return false;
            }

            var ok = function(){
                $.ajax({
                    type: 'POST',
                    url: '/main/auth/join_process_ajax',
                    dataType: 'json',
                    data : $("#registerForm").serialize(),
                    error: function (xhr, textStatus, errorThrown) {
                        alert('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {

                        if(data.result === "SUCCESS"){
                            ablex.alert.success(data.msg, __TITLE__, "/");
                        }else{
                            ablex.alert.error(data.msg);
                            return false;
                        }
                    },
                    complete: function () {
                    }
                }); //ajax end

            };

            var cancel = function () {
                e.preventDefault();
                ablex.notify.message("사용자등록을 취소했습니다.");
            };

            ablex.confirm("사용자 등록하시겠습니까?", __TITLE__, ok, cancel);
        });
    });

    var count_down = function(time)
    {
        $(".form-verify-timer .font-danger")
            .countdown(time, function (event) {
                $(".form-verify-timer .font-danger").text(event.strftime('%M:%S'));
            })
            .on('finish.countdown', function(event) {
                    ablex.alert.error("유효기간이 만료되었습니다. 다시 인증을 요청해 주세요.",__TITLE__);
                    $("#hp_certify_seq").val("");
                    $("#hp_certify_no").val("").prop("readonly", true);

                    $('.form-timer').hide();
                    $('.form-verify-timer').hide();

                    $('#btnSmsVerify').text('인증').prop('disabled', true);
                }
            );
    };

    var chkPassword = function(str)
    {
        var pw = str;
        var num = pw.search(/[0-9]/g);
        var eng = pw.search(/[a-z]/ig);
        var spe = pw.search(/[`~!@#$%^&*|₩₩₩'₩";:₩/?]/g);

        if(pw.length < 6 || pw.length > 15){
            ablex.alert.error("비밀번호는 6 ~ 15자리 이내로 입력해주세요.");
            return false;
        }

        if(pw.search(/₩s/) != -1){
            ablex.alert.error("비밀번호는 공백없이 입력해주세요.");
            return false;
        }

        if(spe > 0){
            ablex.alert.error("영문, 숫자만 입력해주세요.");
            return false;
        }

        if(num < 0 || eng < 0 ){
            ablex.alert.error("영문, 숫자를 혼합하여 입력해주세요.");
            return false;
        }
        return true;
    };
</script>