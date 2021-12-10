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
                <label class="col-form-label">기간조회</label>
                <div class="col-form-block form-inline">
                    <div class="input-group date">
                        <input type="text" id="date_start" name="date_start" class="form-control input-datepicker" value="<?=$date_start;?>" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar"></i></span>
                        </div>
                    </div>
                    <div class="tilde">~</div>
                    <div class="input-group date">
                        <input type="text" id="date_end" name="date_end" class="form-control input-datepicker" value="<?=$date_end;?>" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar"></i></span>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-set-date" data-select-date="all">전체</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("now"));?>">오늘</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-3 day"));?>">3일</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-7 day"));?>">7일</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-1 month"));?>">1개월</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-2 month"));?>">2개월</button>
                        <button type="button" class="btn btn-secondary btn-set-date" data-select-date="<?=date('Y-m-d', strtotime("-3 month"));?>">3개월</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-form-label">조건검색</label>
                <div class="col-form-block form-inline">
                    <div class="input-group-prepend">
                        <select id="sch_key" name="sch_key" class="form-control">
                            <option value="title" <?=($sch_key == "title")?"SELECTED":"";?>>제목</option>
                            <option value="code" <?=($sch_key == "code")?"SELECTED":"";?>>코드</option>
                        </select>
                    </div>
                    <input type="text" id="sch_str" name="sch_str" class="form-control" value="<?=$sch_str;?>">
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
            <h3 class="kt-portlet__head-title">총 <?=number_format($totalCount);?> 건</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <button class="btn btn-sm btn-primary btn-write">문서 등록</button>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col style="width:70px">    <!-- no -->
                    <col style="width:130px">    <!-- 코드 -->
                    <col>    <!-- 제목 -->
                    <col style="width:200px">    <!-- 등록일시 -->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col" class="disabled-sorting">NO</th>
                    <th scope="col" class="disabled-sorting">코드</th>
                    <th scope="col" class="disabled-sorting">제목</th>
                    <th scope="col" class="disabled-sorting">등록일시</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($lists as $key => $row) {
                    ?>
                    <tr>
                        <td><?=$row->no;?></td>
                        <td><?=$row->code;?></td>
                        <td class="text-left"><a href="#" class="cell-title__link btn-document-detail" data-seq="<?=$row->seq;?>"><?=$row->title;?></a></td>
                        <td><?=$row->reg_date;?></td>
                    </tr>
                    <?php
                } // End foreach

                if(count($lists) === 0) {
                    echo "<tr><td colspan='4' style='line-height: 200px;text-align: center;'>검색된 정보가 없습니다.</td></tr>";
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

<div class="modal fade" id="modal_search_member" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal_search_member">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $("#date_start, #date_end").datepicker({
            orientation: "right bottom",
            todayBtn: "linked"
        });

        $('.btn-set-date').on('click', function(e) {
            e.preventDefault();

            $('.btn-set-date').removeClass('btn-primary').addClass('btn-secondary');
            $(this).removeClass('btn-secondary').addClass('btn-primary');

            $("#date_start").val($(this).data('select-date'));
            $("#date_end").val("<?=Date('Y-m-d');?>");
        });

        $(".btn-search").on("click", function(e){
            e.preventDefault();

            $("#frmSearch").submit();
        });

        $(".btn-search-init").on("click", function(e) {
            e.preventDefault();
            location.href = '/operation/notice';
        });


        $(this).off('click', '.btn-write').on('click', '.btn-write', function(e) {
            e.preventDefault();

            location.href = '/siteinfo/document_create';
        });

        $(this).off('click', '.btn-document-detail').on('click', '.btn-document-detail', function(e) {
            e.preventDefault();

            var _seq = $(this).data('seq');
            location.href = '/siteinfo/document_edit/'+_seq;
        });
    });
</script>
