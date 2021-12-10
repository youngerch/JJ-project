<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-06-26
 */
?>
<div class="content">
    <div class="card">
        <div class="card-body">

            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs" role="tablist">
                        <?php
                        foreach($lang_cds as $key => $_lang):
                        ?>
                        <li class="nav-item"><a class="nav-link <?=$shop->lang_cd === $_lang->cd ? "active" : ""?> btn-tab-lang-selected" data-toggle="tab" href="#" aria-expanded="true" data-lang="<?=$_lang->cd?>"><?=$_lang->cd_name?></a></li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>

            <div class="write-form">

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'editForm');
                echo form_open('', $attributes);
                ?>
                <input type="hidden" name="lang_cd" id="lang_cd" value="<?=$shop->lang_cd?>">
                <input type="hidden" name="seq" id="seq" value="<?=$shop->seq?>">


                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 코드</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <?=$shop->shop_cd?>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 이름</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="shop_name" id="shop_name" required="required" placeholder="쇼핑몰 이름" value="<?=$shop->shop_name?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 설명</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <textarea class="form-control" name="shop_desc" id="shop_desc" rows="3"><?=$shop->shop_desc?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 연락처</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="shop_tel" id="shop_tel" placeholder="쇼핑몰 연락처" value="<?=$shop->shop_tel?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 우편번호</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="shop_zip" id="shop_zip" placeholder="우편번호" value="<?=$shop->shop_zip?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 주소</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="shop_addr" id="shop_addr" placeholder="쇼핑몰 주소" value="<?=$shop->shop_addr?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">쇼핑몰 상세주소</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="shop_addr_detail" id="shop_addr_detail" placeholder="쇼핑몰 주소 상세" value="<?=$shop->shop_addr_detail?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">대표자 이름</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="owner_name" id="owner_name" placeholder="대표자 이름" value="<?=$shop->owner_name?>">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <label class="col-md-3 col-form-label">무통장 사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="dbank_use_is" id="dbank_use_is_1" type="radio" <?=$shop->dbank_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="dbank_use_is" id="dbank_use_is_0" type="radio" <?=$shop->dbank_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">무통장 계좌정보</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <textarea class="form-control" name="bank_account_data" id="bank_account_data" rows="3"><?=$shop->bank_account_data?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">가상계좌 사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="vbank_use_is" id="vbank_use_is_1" type="radio" <?=$shop->vbank_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="vbank_use_is" id="vbank_use_is_0" type="radio" <?=$shop->vbank_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">핸드폰결제 사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="hp_use_is" id="hp_use_is_1" type="radio" <?=$shop->hp_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="hp_use_is" id="hp_use_is_0" type="radio" <?=$shop->hp_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">카드결제 사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="card_use_is" id="card_use_is_1" type="radio" <?=$shop->card_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="card_use_is" id="card_use_is_0" type="radio" <?=$shop->card_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">코인결제 사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="coin_use_is" id="coin_use_is_1" type="radio" <?=$shop->coin_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="coin_use_is" id="coin_use_is_0" type="radio" <?=$shop->coin_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">포인트(적립금) 사용여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="pay_point_use_is" id="pay_point_use_is_1" type="radio" <?=$shop->pay_point_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="pay_point_use_is" id="pay_point_use_is_0" type="radio" <?=$shop->pay_point_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">결제 포인트 최소금액</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="pay_point_min_amt" id="pay_point_min_amt" placeholder="결제 포인트 최소 금액" value="<?=$shop->pay_point_min_amt?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">결제 포인트 최대금액</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="pay_point_max_amt" id="pay_point_max_amt" placeholder="결제 포인트 최대 금액" value="<?=$shop->pay_point_max_amt?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">결제 포인트 단위</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="pay_point_unit" id="pay_point_unit" placeholder="결제 포인트 단위" value="<?=$shop->pay_point_unit?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">쿠폰 사용 여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="coupon_use_is" id="coupon_use_is_1" type="radio" <?=$shop->coupon_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="coupon_use_is" id="coupon_use_is_0" type="radio" <?=$shop->coupon_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">현금 영수증 사용 여부</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="receipt_use_is" id="receipt_use_is_1" type="radio" <?=$shop->receipt_use_is === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 예
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="receipt_use_is" id="receipt_use_is_0" type="radio" <?=$shop->receipt_use_is === "0" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 아니오
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">배송 업체</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <select name="trans_company_cd" id="trans_company_cd" class="form-control">
                                <option value="">선택해 주세요.</option>
                                <?php
                                foreach($trans_cds as $key => $row):
                                    $selected = $row->cd === $shop->trans_company_cd ? "selected" : "";
                                    echo "<option value='{$row->cd}' ".$selected.">{$row->cd_name}</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">배송비 타입</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="trans_type" id="trans_type_1" type="radio" <?=$shop->trans_type === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 무료배송
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="trans_type" id="trans_type_2" type="radio" <?=$shop->trans_type === "2" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 금액별 차등
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">배송비</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="trans_cost" id="trans_cost" placeholder="상한가:배송비,상한가:배송비" value="<?=$shop->trans_cost?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">배송정보</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <textarea id="trans_data" name="trans_data" class="form-control col-md-10 col-xs-12" placeholder="글 내용"><?=$shop->trans_data?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">교환/환불 정보</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <textarea id="exchange_return_data" name="exchange_return_data" class="form-control col-md-10 col-xs-12" placeholder="글 내용"><?=$shop->exchange_return_data?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">후기 승인 타입</label>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="review_confirm_type" id="review_confirm_type_1" type="radio" <?=$shop->review_confirm_type === "1" ? "checked" : "";?> value="1"><span class="form-check-sign"></span> 즉시 승인
                            </label>
                        </div>
                        <div class="form-check-radio form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="review_confirm_type" id="review_confirm_type_2" type="radio" <?=$shop->review_confirm_type === "2" ? "checked" : "";?> value="0"><span class="form-check-sign"></span> 관리자 승인
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">사업자등록번호</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="saupja_reg_num" id="saupja_reg_num" placeholder="사업자등록번호" value="<?=$shop->saupja_reg_num?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">통신판매업신고번호</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="tongsin_num" id="tongsin_num" placeholder="통신판매업신고번호" value="<?=$shop->tongsin_num?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">부가통신사업자번호</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="buga_saupja_num" id="buga_saupja_num" placeholder="부가통신사업자번호" value="<?=$shop->buga_saupja_num?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label">정보관리책임자 이름</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="security_name" id="security_name" placeholder="정보관리책임자 이름" value="<?=$shop->security_name?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">정보관리책임자 이메일</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" name="security_email" id="security_email" placeholder="정보관리지책임자 이메일" value="<?=$shop->security_email?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">다국어 지원</label>

                    <div class="col-md-5 col-sm-12">
                        <?php
                        foreach($lang_cds as $key => $lang):

                            if($lang->cd === __DEFAULT_LANG_CD__):
                                $checked = "checked";
                            else:
                                $checked = in_array($lang->cd, $shop->lang_cds) ? "checked" : "";

                            endif;
                            ?>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" value="<?=$lang->cd?>" name="lang_cds[]" id="lang_cds_<?=$lang->cd?>" <?=$checked?>><span class="form-check-sign"></span> <?=$lang->cd_name?>
                                </label>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>

                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">등록정보</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            등록일 : <?=$shop->reg_date?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3 col-form-label">변경정보</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            변경일 : <?=$shop->mod_date?>
                        </div>
                    </div>
                </div>

                <div class="button-group d-flex justify-content-between">
                    <div>
                        <button class="btn btn-success btn-submit">저장</button>
                        <button class="btn btn-cancel">취소</button>
                        <button class="btn btn-danger btn-init" data-lang="<?=$shop->lang_cd?>">초기화</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets')?>/ckeditor/ckeditor.js"></script>

<script>

    $(".btn-tab-lang-selected").on("click", function(e){
        e.preventDefault();
        var target_lang_cd = $(this).data("lang");
        location.href = "/config/shop/edit/" + target_lang_cd;
    });


    CKEDITOR.replace( 'trans_data', {
        height : 400,
        filebrowserUploadUrl: '/config/shop/ckeditor_upload'
    });

    CKEDITOR.replace( 'exchange_return_data', {
        height : 400,
        filebrowserUploadUrl: '/config/shop/ckeditor_upload'
    });

    $(".btn-submit").on("click", function(e){
        e.preventDefault();

        if(!$("#shop_name").val()){
            ablex.alert.error("쇼핑몰 이름을 입력해 주세요.");
            return false;
        }

        var ok = function () {


            var trans_data = CKEDITOR.instances.trans_data;
            $("#trans_data").val(trans_data.getData());

            var exchange_return_data = CKEDITOR.instances.exchange_return_data;
            $("#exchange_return_data").val(exchange_return_data.getData());


            $.ajax({
                type: 'POST',
                url: '/config/shop/edit_process_ajax',
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
            ablex.notify.message("변경 작업을 취소했습니다.");
        };

        ablex.confirm("쇼핑몰 설정을 변경하시겠습니까?", __TITLE__, ok, cancel);

    });

    $(".btn-cancel").on("click", function(e){
        e.preventDefault();

        var lang_cd = $("#lang_cd").val();

        location.href = "/config/code/edit/" + lang_cd;
    });

    $(".btn-init").on("click", function(e){
        e.preventDefault();

        var lang_cd = $(this).data("lang");

        var ok = function () {



            console.log(lang_cd);

            $.ajax({
                type: 'POST',
                url: '/config/shop/init_ajax',
                dataType: 'json',
                data : {
                    'lang_cd' : lang_cd
                },
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
            ablex.notify.message("초기화 작업을 취소했습니다.");
        };

        ablex.confirm("쇼핑몰 설정을 기본 언어 값으로 초기화 하시겠습니까?", __TITLE__, ok, cancel);
    });

</script>


