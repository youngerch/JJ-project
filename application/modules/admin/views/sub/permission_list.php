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
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select id="sch_key" name="sch_key" class="form-control">
                                <option value="name" <?=($sch_key == "name")?"SELECTED":"";?>>등급명</option>
<!--                                <option value="accessible_menu" --><?//=($sch_key == "accessible_menu")?"SELECTED":"";?><!-->접근가능 메뉴</option>-->
                            </select>
                        </div>
                        <input type="text" id="sch_str" name="sch_str" class="form-control" value="<?=$sch_str;?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot kt-portlet__foot--center">
        <button type="button" class="btn btn-primary btn-wide btn-search"><i class="la la-search"></i>검색</button>
        <button type="reset" class="btn btn-info btn-wide btn-search-init"><i class="la la-refresh"></i> 초기화</button>
    </div>
</div>

<?php
echo form_close();
?>

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">권한관리</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <button type="button" class="btn btn-sm btn-primary btn-create">권한등록</button>
        </div>
<!--        <div class="kt-portlet__head-toolbar">-->
<!--            <select id="perpage" name="perpage" class="form-control">-->
<!--                <option value="20">20개보기</option>-->
<!--            </select>-->
<!--        </div>-->
    </div>
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:7%">
                    <col style="">
                    <col style="">
                    <col style="">
                    <col style="">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">등급명</th>
<!--                    <th scope="col">레벨</th>-->
                    <th scope="col">접근가능 메뉴</th>
                    <th scope="col">등록일</th>
                    <th scope="col">사용여부</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $ary_permission = array (
                    "member"    => "회원관리",
                    "cscenter"  => "고객센터",
                    "item"      => "상품관리",
                    "operation" => "운영관리",
                    "admin"     => "운영자관리",
                    "siteinfo"  => "사이트설정",
                );
                foreach ($lists as $key => $row) {
                    $str_permission = "";
                    $tmp = explode("|", $row->accessible_menu);
                    foreach ( $tmp as $k => $v ) {
                        $str_permission .= ($str_permission == "") ? $ary_permission[$v] : ", " . $ary_permission[$v];
                    }
                    ?>
                    <tr>
                        <td><?=$row->no?></td>
                        <td><a href="/admin/permission_edit/<?=$row->seq;?>" class="cell-title__link"><?=$row->name?></a></td>
                        <td><?=$str_permission;?></td>
                        <td><?=$row->reg_date?></td>
                        <td>
                            <span class="kt-switch">
                                <label>
                                    <input type="checkbox" name="" <?=($row->is_use === "Y" ? "CHECKED" : "") ?> class=" btn-use-toggle" data-seq="<?=$row->seq?>" data-use="<?=$row->is_use?>">
                                    <span></span>
                                </label>
                            </span>
                        </td>
                    </tr>
                <?php
                } // End foreach

                if (count($lists) === 0) {
                    echo "<tr><td colspan='5' style='line-height: 300px;text-align: center;'>관리자 권한 정보가 없습니다.</td></tr>";
                } // End if
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

        $(".btn-create").on("click", function (e) {
            e.preventDefault();
            location.href = "/admin/permission_create";
        });

        //수정
        $(".btn-edit").on("click", function (e) {
            e.preventDefault();
            location.href = "/admin/permission_edit/" + $(this).data("seq");
        });

        $(".btn-search").on("click", function (e) {
            e.preventDefault();
            if (!$("#sch_str").val()) {
                swal.fire('검색어를 입력해 주세요.');
                return false;
            }

            $("#frmSearch").submit();
        });

        $(".btn-search-init").on("click", function (e) {
            e.preventDefault();
            location.href = "/admin/permission";
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
