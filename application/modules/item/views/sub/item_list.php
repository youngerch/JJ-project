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
                <label class="col-form-label">노출</label>
                <div class="col-form-block form-inline">
                    <div class="kt-radio-inline pt-2">
                        <label class="kt-radio">
                            <input type="radio" name="is_display" value="" <?=($is_display == "")?"CHECKED":"";?>> 전체<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="is_display" value="Y" <?=($is_display == "Y")?"CHECKED":"";?>> 노출<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="is_display" value="N" <?=($is_display == "N")?"CHECKED":"";?>> 비노출<span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">상태</label>
                <div class="col-form-block form-inline">
                    <div class="kt-radio-inline pt-2">
                        <label class="kt-radio">
                            <input type="radio" name="sales_status" value="" <?=($sales_status == "")?"CHECKED":"";?>> 전체<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="sales_status" value="1" <?=($sales_status == "1")?"CHECKED":"";?>> 판매중<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="sales_status" value="5" <?=($sales_status == "5")?"CHECKED":"";?>> 품절<span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="sales_status" value="9" <?=($sales_status == "9")?"CHECKED":"";?>> 판매중지<span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select id="sch_key" name="sch_key" class="form-control">
                                <option value="C.item_name" <?=($sch_key == "A.item_name")?"checked":"";?>>상품명</option>
                                <option value="A.item_cd" <?=($sch_key == "A.item_cd")?"checked":"";?>>상품코드</option>
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
        <!--button type="button" class="btn btn-dark btn-wide btn-search-init"><i class="la la-cloud-download"></i> 엑셀저장</button-->
    </div>
</div>

<?php
echo form_close();
?>

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">상품목록</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <button type="button" class="btn btn-sm btn-primary btn-item-create">상품등록</button>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:60px">
                    <col style="width:60px"> <!--상태-->
                    <col style="width:60px"> <!--노출여부-->
                    <col style="width:90px"> <!--메인노출-->
                    <col style="width:130px"> <!--대표코드-->
                    <col style="width:130px"> <!--상품코드-->
                    <col> <!--상품명-->
                    <col style="width:130px"> <!--판매가-->
                    <col style="width:90px"> <!--출고수-->
                    <col style="width:100px"> <!--누적입고-->
                    <col style="width:100px"> <!--누적출고-->
                    <col style="width:100px"> <!--재고-->
                    <col style="width:100px"> <!--재고알림-->
                    <col style="width:100px"> <!--재고경고-->
                    <col style="width:100px"> <!--정기결제제한-->
                    <col style="width:100px"> <!--알림기준인원-->
                    <col style="width:100px"> <!--알림발송인원-->
                    <col style="width:120px"> <!--등록일시-->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">상태</th>
                    <th scope="col">노출</th>
                    <th scope="col">메인노출</th>
                    <th scope="col">대표코드</th>
                    <th scope="col">상품코드</th>
                    <th scope="col">상품명</th>
                    <th scope="col">판매가</th>
                    <th scope="col">출고수</th>
                    <th scope="col">누적입고</th>
                    <th scope="col">누적출고</th>
                    <th scope="col">재고</th>
                    <th scope="col">재고알림</th>
                    <th scope="col">재고경고</th>
                    <th scope="col">결제제한</th>
                    <th scope="col">알림기준</th>
                    <th scope="col">알림발송</th>
                    <th scope="col">등록일시</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($lists as $key => $row):
                    $stock_count = intVal($row->total_warehousing_count) - intVal($row->total_release_count);
                    $str_sales_status = "-";
                    switch ( $row->sales_status ) {
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
                        <td><?=$str_sales_status;?></td>
                        <td><?=($row->is_display=="Y")?"노출":"노출안함";?></td>
                        <td><?=($row->is_main=="Y")?"노출":"노출안함";?></td>
                        <td><?=$row->stock_cd;?></td>
                        <td>
                            <a href="/item/detail/<?=$row->seq;?>" class="cell-title__link">
                                <?=$row->item_cd;?>
                            </a>
                        </td>
                        <td class='text-left'>
                            <a href="/item/detail/<?=$row->seq;?>" class="cell-title__link"><?=$row->item_name;?> [ <?=$row->unit_count;?> <?=$row->unit_name;?> ]</a>
                        </td>
                        <td class='text-right'><?=number_format($row->sale_price);?> 원</td>
                        <td class='text-right'><?=number_format($row->release_count);?> <?=$row->unit_name;?></td>
                        <td class='text-right'><?=number_format($row->total_warehousing_count);?> <?=$row->unit_name;?></td>
                        <td class='text-right'><?=number_format($row->total_release_count);?> <?=$row->unit_name;?></td>
                        <td class='text-right'><?=number_format($stock_count);?> <?=$row->unit_name;?></td>
                        <td class='text-right'><?=number_format($row->alert_count);?> <?=$row->unit_name;?></td>
                        <td class='text-right'><?=number_format($row->warning_count);?> <?=$row->unit_name;?></td>
                        <td class='text-right'><?=number_format($row->subscription_limit);?> 명</td>
                        <td class='text-right'><?=number_format($row->alarm_sms);?> 명</td>
                        <td class='text-right'><?=number_format($row->alarm_send);?> 명</td>
                        <td><?=str_replace(' ', '<br/>', $row->reg_date);?></td>
                    </tr>
                <?php
                endforeach;

                if(count($lists) === 0):
                    echo "<tr><td colspan='20' style='line-height: 300px;text-align: center;'>검색된 상품 정보가 없습니다.</td></tr>";
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

        // 조건검색 input Enter 키 누름
        $(this).off('keyup', '#sch_str').on('keyup', '#sch_str', function(e) {
            e.preventDefault();
            if ( e.keyCode == 13 ) {
                $('#frmSearch').submit();
            }
        });

        // 검색
        $(this).off('click', '.btn-search').on('click', '.btn-search', function(e) {
            e.preventDefault();

            $('#frmSearch').submit();
        });

        // 검색 초기화
        $(this).off('click', '.btn-search-init').on('click', '.btn-search-init', function(e) {
            e.preventDefault();

            location.href = '/item/lists';
        });

        // 상품 등록
        $(this).off('click', '.btn-item-create').on('click', '.btn-item-create', function(e) {
            e.preventDefault();

            location.href = '/item/create';
        });

        // 상품 상세 / 수정
        $(this).off('click', '.btn-item-edit').on('click', '.btn-item-edit', function(e) {
            e.preventDefault();

            let _item_seq = $(this).data("seq");
            location.href = '' + _item_seq;
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
