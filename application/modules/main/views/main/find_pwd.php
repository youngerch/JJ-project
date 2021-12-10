<div class="wrapper">
    <div class="login-wrapper">
        <div class="login-group">
            <div class="login-logo">
                <img src="<?php echo base_url('assets')?>/images/common/logo.png" class="login-logo__img" alt="BOOK-CAFE SHOP">
                <h1 class="login-title">비밀번호 찾기</h1>
            </div>
            <div class="login-form">

                <?php
                $attributes = array('class' => 'form', 'id' => 'loginForm');
                echo form_open('', $attributes);
                ?>

                <div class="form-block">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="user_name" class="form-label-inline">이름</label>
                        </div>
                        <input type="text" id="name" name="name" class="form-input">
                    </div>
                </div>
                <div class="form-block">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="user_id" class="form-label-inline">아이디</label>
                        </div>
                        <input type="text" id="email" name="email" placeholder="이메일 주소" class="form-input">
                    </div>
                </div>
                <?php
                echo form_close();
                ?>

                <div class="button-group">
                    <button type="button" class="button button-cancel btn-cancel">취소</button>
                    <button type="button" class="button button-dark button-find btn-find">확인</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-cancel').on('click', function(e) {
            e.preventDefault();
            location.href = '/main/auth/';
        })

        $(".btn-find").on("click", function(e){
            e.preventDefault();

            if(!$("#name").val()){
                ablex.alert.error("이름을 입력해 주세요.");
                $("#name").focus();
                return false;
            }

            if(!$("#email").val()){
                ablex.alert.error("관리자 이메일을 입력해 주세요.");
                $("#email").focus();
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/main/auth/findpwd_process_ajax',
                dataType: 'json',
                data : $("#loginForm").serialize(),
                error: function (xhr, textStatus, errorThrown) {
                    ablex.alert.error("error:" + xhr.status);
                    return false;
                },
                success: function (data) {
                    if (data.result === "SUCCESS") {
                        ablex.alert.success(data.msg, __TITLE__, "/");
                    } else {
                        ablex.alert.error(data.msg, __TITLE__);
                        return false;
                    }
                },
                complete: function () {
                }
            }); //ajax end

        });
    });
</script>