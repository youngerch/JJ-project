<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-06-24
 */
?>
<div class="content">
    <div class="card">
        <div class="card-body">

            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link <?=$code->lang_cd === "11001" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="true" data-lang="11001">한국어</a></li>
                        <li class="nav-item"><a class="nav-link <?=$code->lang_cd === "11002" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="false" data-lang="11002">영어</a></li>
                        <li class="nav-item"><a class="nav-link <?=$code->lang_cd === "11003" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="false" data-lang="11003">일본어</a></li>
                        <li class="nav-item"><a class="nav-link <?=$code->lang_cd === "11004" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="false" data-lang="11004">중국어</a></li>
                    </ul>
                </div>
            </div>

            <div class="write-form">

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'editForm');
                echo form_open('', $attributes);
                ?>
                <input type="hidden" name="lang_cd" id="lang_cd" value="<?=$code->lang_cd?>">
                <input type="hidden" name="cd" id="cd" value="<?=$code->cd?>">
                <input type="hidden" name="class_cd" id="class_cd" value="<?=$code->class_cd?>">
                <input type="hidden" name="detail_cd" id="detail_cd" value="<?=$code->detail_cd?>">


                <div class="row">
                    <label class="col-md-3 col-form-label">코드</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <?=$code->cd?>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <label class="col-md-3 col-form-label">분류코드</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <?=$code->class_cd?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">상세코드</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <?=$code->detail_cd?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">코드명</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="name" required="required" placeholder="코드명" value="<?=$code->cd_name?>">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">코드설명</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="desc" id="desc" placeholder="코드설명" value="<?=$code->desc?>">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">여분1</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="dummy_col_1" id="dummy_col_1" placeholder="여분1" value="<?=$code->dummy_col_1?>">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">여분2</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="dummy_col_2" id="dummy_col_2" placeholder="여분2" value="<?=$code->dummy_col_2?>">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">정렬순서</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="number" class="form-control" name="orders" id="orders" placeholder="정렬순서" value="<?=$code->orders?>">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 미 입력시 자동으로 설정됩니다.</span>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <label class="col-md-3 col-form-label">사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="use_is" id="use_is_1" type="radio" <?=$code->use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="use_is" id="use_is_0" type="radio" <?=$code->use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="button-group d-flex justify-content-between">
                    <div>
                        <button class="btn btn-success btn-submit">저장</button>
                        <button class="btn btn-cancel">취소</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

    $(".btn-tab-lang-selected").on("click", function(e){
        e.preventDefault();
        var target_lang_cd = $(this).data("lang");
        var cd      = $("#cd").val();

        location.href = "/config/code/edit/" + cd + "/" + target_lang_cd;

    });

    $(".btn-submit").on("click", function(e){
        e.preventDefault();

        if(!$("#name").val()){
            ablex.alert.error("코드명을 입력해 주세요.");
            return false;
        }

        var ok = function () {


            $.ajax({
                type: 'POST',
                url: '/config/code/edit_process_ajax',
                dataType: 'json',
                data : $("#editForm").serialize(),
                error: function (xhr, textStatus, errorThrown) {
                    alert('error :' + xhr.status);
                    return false;
                },
                success: function (data) {

                    if(data.result === "SUCCESS"){

                        ablex.alert.success(data.msg, __TITLE__, window.location.href);

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
            ablex.notify.message("코드 수정을 취소했습니다.");
        };

        ablex.confirm("코드를 수정하시겠습니까?", __TITLE__, ok, cancel);

    });

    $(".btn-cancel").on("click", function(e){
        e.preventDefault();

        var class_cd = $("#class_cd").val();

        location.href = "/config/code/lists/" + class_cd;
    });
</script>

