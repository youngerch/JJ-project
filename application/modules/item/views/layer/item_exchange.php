<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-23
 */

?>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">상품변경하기</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <?php
    $attributes = array('class' => 'kt-form', 'id' => 'frmLyrSearch', 'method' => 'POST', 'enctype' => 'multipart/form-data');
    echo form_open('', $attributes);
        ?>
    <input type="hidden" id="lyr_page" name="lyr_page" value="1" />

    <div class="search-form">
        <div class="form-group">
            <label class="col-form-label">조건검색</label>
            <div class="col-form-block form-inline">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <input type="text" id="lyr_sch_str" name="lyr_sch_str" class="form-control">
                    </div>
                    <button type="button" class="btn btn-primary btn-wide btn-lyr-search"><i class="la la-search"></i>검색</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo form_close();
        ?>
    <div class="table-wrapper table-scroll">
        <?php
        $attributes = array('class' => 'kt-form', 'id' => 'frmLyrExchange', 'method' => 'POST', 'enctype' => 'multipart/form-data');
        echo form_open('', $attributes);
            ?>
        <input type="hidden" id="lyr_order_item_seq" name="lyr_order_item_seq" value="<?=$order_item->seq;?>" />
        <input type="hidden" id="lyr_order_item_amount" name="lyr_order_item_amount" value="<?=$order_item->sale_price;?>" />
        <input type="hidden" id="lyr_order_item_exchange_cd" name="lyr_order_item_exchange_cd" />
        <input type="hidden" id="lyr_order_item_exchange_count" name="lyr_order_item_exchange_count" />

        <table class="table-list" id="tblExchangeItem">
            <colgroup>
                <col /> <!-- 상품명 -->
                <col style="width:60px;" /> <!-- 수량 -->
                <col style="width:200px;" /> <!-- 판매가격 -->
                <col style="width:200px;" /> <!-- 총 판매가격 -->
                <col /> <!-- 품절여부 -->
                <col /> <!-- 게시여부 -->
                <col /> <!-- 관리 -->
            </colgroup>
            <thead>
            <tr>
                <th scope="col">상품명</th>
                <th scope="col">수량</th>
                <th scope="col">판매가격</th>
                <th scope="col">총 판매가격</th>
                <th scope="col">상태</th>
                <th scope="col">게시</th>
                <th scope="col">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="kt-pagination kt-pagination--brand">
        <ul class="kt-pagination__links" id="lyr_pagination"></ul>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(this).off('click', '.btn-lyr-search').on('click', '.btn-lyr-search', function(e) {
            e.preventDefault();

            $('#lyr_page').val(1);
            get_exchange_list();
        });

        $(this).off('blur', '.tbl-in-input').on('blur', '.tbl-in-input', function() {
            var _count = parseInt($(this).val());
            var _parent = $(this).closest('tr');
            var _amount = parseInt(_parent.find('input[type=hidden]').val());

            $('.tbl-in-input').val('');
            $('.calc-amount').html('0');

            if ( isNaN(_count) ) {
                return false;
            }
            var _calc_amount = _count * _amount;
            $(this).val(_count);
            _parent.find('.calc-amount').html(comma(_calc_amount));
        });

        $(this).off('click', '.btn-lyr-exchange').on('click', '.btn-lyr-exchange', function(e) {
            e.preventDefault();

            var _parent     = $(this).closest('tr');
            var _itemcd     = $(this).data('itemcd');
            var _itemname   = $(this).data('itemname');
            var _count      = parseInt(_parent.find('.tbl-in-input').val());
            var _amount     = parseInt(_parent.find('input[type=hidden]').val());
            var _order_amount   = $('#lyr_order_item_amount').val();
            var _calc_amount    = _count * _amount;

            console.log('_count', _count);
            console.log('_amount', _amount);
            console.log('_order_amount', _order_amount);
            console.log('_calc_amount', _calc_amount);

            if ( isNaN(_count) ) { swal.fire('수량을 입력해주세요.'); return false; }
            if ( _order_amount != _calc_amount ) { swal.fire('주문상품의 총 결제액과 변경상품의 총 판매가격이 일치하지 않습니다.'); return false; }

            $('#lyr_order_item_exchange_cd').val(_itemcd);
            $('#lyr_order_item_exchange_count').val(_count);

            var _exchange_ok = function() {
                $.ajax({
                    type: 'POST',
                    url: '/order/order_item_change_process_ajax',
                    dataType: 'json',
                    data : $("#frmLyrExchange").serialize(),
                    error: function (xhr, textStatus, errorThrown) {
                        alert('error :' + xhr.status);
                        return false;
                    },
                    success: function (data) {
                        if (data.result === "SUCCESS") {
                            swal.fire({
                                text: data.msg,
                                timer: 1000,
                                onOpen: function() {
                                    swal.showLoading()
                                }
                            }).then(function(result) {
                                if (result.dismiss === 'timer') {
                                    location.href = window.location;
                                }
                            });
                        }else{
                            swal.fire(data.msg);
                            return false;
                        }
                    }
                }); //ajax end
            }

            var _exchange_cancel = function() {
                //PASS
            }

            swal.fire({
                title: __TITLE__,
                html: _itemname + " 상품으로 교환처리 하시겠습니까?",
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                    _exchange_ok()
                } else if (result.dismiss === 'cancel') {
                    _exchange_cancel()
                }
            });
        });

        get_exchange_list();
    });

    var get_exchange_list = function() {

        $('#tblExchangeItem tbody tr').remove();

        var _page = $('#lyr_page').val();

        $.ajax({
            type: 'POST',
            url: '/item/item_exchange_list_ajax',
            dataType: 'json',
            data : $("#frmLyrSearch").serialize(),
            error: function (xhr, textStatus, errorThrown) {
                swal.fire('error :' + xhr.status,__TITLE__);
                return false;
            },
            success: function (data) {
                $('#tblExchangeItem tbody').html(data.html);

                get_exchange_pagination(_page, data.perpage, data.totalcnt);
            },
            complete: function () {
            }
        }); //ajax end
    }

    var get_exchange_pagination = function(page, perpage, total) {

        var started = false;
        if ( total == 0 ) {
            $("#lyr_pagination").html('');
            return;
        }

        $("#lyr_pagination").paging(total, {
            format: '[< . (qq -) nnnncnnnn (- pp) >]',
            perpage: perpage,
            lapping: 0,
            page: page, // we await hashchange() event
            onSelect: function (page) {
                if (started) {
                    $('#lyr_page').val(page);
                    get_exchange_list()
                } else {
                    started = true;
                }
                return false;
            },
            onFormat: function (type) {
                switch (type) {
                    case "block": // n and c
                        if (this.value != page)
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

    //콤마찍기
    var comma = function(str) {
        str = String(str);
        tmp = str.split(".");

        ret = "";
        ret = tmp[0].replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');

        if ( tmp.length == 2 )
            ret += "." + tmp[1];

        return ret;
    }
</script>