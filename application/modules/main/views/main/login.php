<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container">
                    <div class="kt-login__logo"><img alt="Logo" src="/assets/media/logos/logo_dark.png" style="max-width:430px;"></div>
                    <div class="kt-login__signin">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">LOGIN</h3>
                        </div>
                        <form id="frmLogin" name="frmLogin" class="kt-form" method="POST">
                            <div class="form-group">
                                <label for="admin_email">아이디</label>
                                <input type="text" id="admin_login_id" name="admin_login_id" class="form-control enter-to-click" data-target=".btn-login" placeholder="로그인 아이디 입력" autocomplete="off" value="<?=$admin_login_id;?>">
                            </div>
                            <div class="form-group">
                                <label for="admin_password">비밀번호</label>
                                <input type="password" id="admin_login_pwd" name="admin_login_pwd" class="form-control enter-to-click" data-target=".btn-login" placeholder="비밀번호 입력">
                            </div>
                            <div class="row kt-login__extra">
                                <div class="col">
                                    <label class="kt-checkbox">
                                        <input type="checkbox" id="remember_id" name="remember_id" value="Y" <?=($idCookieSave == "Y")?"CHECKED":"";?>> 아이디 저장
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="kt-login__actions">
                                <a href="#" id="kt_login_signin_submit" class="btn btn-primary kt-login__btn-primary btn-login">로그인</a>
                            </div>
                            <div class="kt-login__account">
                                <a href="/main/auth/find_password" id="kt_login_forgot" class="kt-login__account-link">비밀번호 찾기</a>
                                <a href="/main/auth/register" class="kt-login__account-link">사용자 등록 신청</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Page -->

<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<button type="button" class="btn btn-modal" data-toggle="modal" data-target="#modal_form" style="display:none;"></button>

<script type="text/javascript">
    $(document).ready(function() {

        $(this).off('click', '.btn-login').on('click', '.btn-login', function(e) {
            e.preventDefault();

            if($.trim($("#admin_login_id").val()) == "" ) {
                swal.fire('아이디를 입력해 주세요.');
                return false;
            }

            if($.trim($("#admin_login_pwd").val()) == "" ) {
                swal.fire('비밀번호를 입력해 주세요.');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/main/auth/login_process_ajax',
                dataType: 'json',
                data : $("#frmLogin").serialize(),
                error: function (xhr, textStatus, errorThrown) {
                    swal.fire("error:" + xhr.status);
                    return false;
                },
                success: function (data) {
                    if (data.result === "SUCCESS") {
                        console.log(data);
                        swal.fire({
                            text: data.msg,
                            showCancelButton: false,
                            confirmButtonText: '확인',
                        }).then(function(result) {
                            $('#modal_form .modal-content').load(data.return_url, function() {
                                $('.btn-modal').click();
                                $('#modal_otp_code').focus();
                            });
                        });
                    } else {
                        swal.fire(data.msg);
                        return false;
                    }
                },
                complete: function () {
                }
            }); //ajax end

        });
    });
</script>
