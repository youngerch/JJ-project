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
                        <li class="nav-item"><a class="nav-link <?=$lang_cd === "11001" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="true" data-lang="11001">한국어</a></li>
                        <li class="nav-item"><a class="nav-link <?=$lang_cd === "11002" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="false" data-lang="11002">영어</a></li>
                        <li class="nav-item"><a class="nav-link <?=$lang_cd === "11003" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="false" data-lang="11003">일본어</a></li>
                        <li class="nav-item"><a class="nav-link <?=$lang_cd === "11004" ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="false" data-lang="11004">중국어</a></li>
                    </ul>
                </div>
            </div>

            <div class="write-form">

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'createForm');
                echo form_open('', $attributes);
                ?>
                <input type="hidden" name="lang_cd" id="lang_cd" value="<?=$lang_cd?>">

                <div class="row">
                    <label class="col-md-3 col-form-label">분류코드</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <select class="form-control" name="class_cd" id="class_cd">
                                <option value="00">분류코드 신규생성</option>
                                <?php
                                foreach($codes as $key => $row):
                                    $selected = $row->class_cd === $class_cd ? "selected" : "";
                                    echo "<option value='".$row->class_cd."' ".$selected.">".$row->cd_name."</option>";
                                endforeach;
                                ?>
                            </select>
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">코드명</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="name" required="required" placeholder="코드명">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">코드설명</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="desc" id="desc" placeholder="코드설명">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">여분1</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="dummy_col_1" id="dummy_col_1" placeholder="여분1">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">여분2</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="dummy_col_2" id="dummy_col_2" placeholder="여분2">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 부가 설명 영역</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">정렬순서</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="number" class="form-control" name="orders" id="orders" placeholder="정렬순서">
                            <span class="form-text text-danger"><i class="nc-icon nc-alert-circle-i"></i> 미 입력시 자동으로 설정됩니다.</span>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <label class="col-md-3 col-form-label">사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="use_is" id="use_is_1" type="radio" checked="checked" value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="use_is" id="use_is_0" type="radio" disabled="" value="0"><span class="form-check-sign"></span> 아니오
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
        var lang_cd = $(this).data("lang");
        var class_cd = $("#class_cd").val();

        location.href = "/config/code/create/" + class_cd + "/" + lang_cd;

    });

    $(".btn-submit").on("click", function(e){
        e.preventDefault();

        if(!$("#class_cd").val()){
            ablex.alert.error("분류코드를 선택해 주세요.");
            return false;
        }

        if(!$("#name").val()){
            ablex.alert.error("코드명을 입력해 주세요.");
            return false;
        }

        var ok = function () {


            $.ajax({
                type: 'POST',
                url: '/config/code/create_process_ajax',
                dataType: 'json',
                data : $("#createForm").serialize(),
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
            ablex.notify.message("코드 등록을 취소했습니다.");
        };

        ablex.confirm("코드를 등록하시겠습니까?", __TITLE__, ok, cancel);

    });

    $(".btn-cancel").on("click", function(e){
        e.preventDefault();
        var class_cd = $("#class_cd").val();
        location.href = "/config/code/lists/" + class_cd;
    });
</script>
