<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-23
 */
?>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">정기결제내역</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="table-wrapper table-scroll">
        <table class="table-list" id="tblMemberSubscription">
            <colgroup>
                <col>    <!-- 일시 -->
                <col>    <!-- 결제유형 -->
                <col>    <!-- 결제금액 -->
                <col>    <!-- 내용 -->
                <col>    <!-- 자격유효일 -->
            </colgroup>
            <thead>
            <tr>
                <th scope="col">일시</th>
                <th scope="col">결제유형</th>
                <th scope="col">결제금액</th>
                <th scope="col">내용</th>
                <th scope="col">자격유효일</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="kt-pagination kt-pagination--brand">
        <ul class="kt-pagination__links" id="layer-pagination"></ul>
    </div>
</div>

<input type="hidden" id="lyr_history_p" value="1" />

<script type="text/javascript">
    $(document).ready(function() {
        subscription_history_list();
    });

    var subscription_history_list = function() {

        var thisElement = $('#tblMemberSubscription tbody');
        var _page = $('#lyr_history_p').val();

        $.ajax({
            type: "POST",
            url: '/member/member_subscription_list_ajax',
            data: {
                seq     : <?=$member_seq?>,
                page	: _page
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $(thisElement).html(data.html);

                get_history_pagination(_page, data.perpage, data.totalcnt);
            }
        });
    }

    var get_history_pagination = function(page, perpage, total) {

        var started = false;
        if ( total == 0 ) {
            $("#layer-pagination").html('');
            return;
        }

        $("#layer-pagination").paging(total, {
            format: '[< . (qq -) nnnncnnnn (- pp) >]',
            perpage: perpage,
            lapping: 0,
            page: page, // we await hashchange() event
            onSelect: function (page) {
                if (started) {
                    $('#lyr_history_p').val(page);
                    subscription_history_list()
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

</script>