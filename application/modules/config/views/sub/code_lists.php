<?php
/**
 * Created by kamiz@ablex.co.kr on 2020-06-23
 */
?>
<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="search-form">
                <form action="">
                    <div class="table-wrapper">
                        <table class="table table-form">
                            <colgroup>
                                <col style="width: 16.666667%">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <th scope="row"><label for="">분류코드</label></th>
                                <td>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <select name="class_cd" id="class_cd" class="form-control">
                                                <option value="">전체</option>
                                                <?php
                                                foreach($codes as $key => $row):
                                                    $selected = $row->class_cd === $class_cd ? "selected" : "";
                                                    echo "<option value='".$row->class_cd."' ".$selected.">".$row->cd_name." (".$row->class_cd.")</option>";
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-9">
                                            <button class="btn btn-primary btn-search"><i class="nc-icon nc-zoom-split"></i> 검색</button>
                                            <button class="btn btn-light"><i class="nc-icon nc-refresh-69"></i> 초기화</button>
                                            <button class="btn btn-excel-download"><i class="nc-icon nc-single-copy-04"></i> 엑셀 저장</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-wrapper">
                <table id="datatable" class="table table-list table-striped text-center">
                    <colgroup>
                        <col style="width:70px">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col" class="disabled-sorting">No.</th>
                        <th scope="col" class="disabled-sorting">코드</th>
                        <th scope="col" class="disabled-sorting">분류코드</th>
                        <th scope="col" class="disabled-sorting">상세코드</th>
                        <th scope="col" class="disabled-sorting">코드명</th>
                        <th scope="col" class="disabled-sorting">설명</th>
                        <th scope="col" class="disabled-sorting">여분1</th>
                        <th scope="col" class="disabled-sorting">여분2</th>
                        <th scope="col" class="disabled-sorting">사용여부</th>
                        <th scope="col" class="disabled-sorting">관리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($lists as $key => $row):
                        $bg_color = ($row->detail_cd === "000" ? "info" : "");
                        ?>
                        <tr class="<?=$bg_color?>">
                            <td class=""><?=$row->no?></td>
                            <td class=""><?=$row->cd?></td>
                            <td class=""><?=$row->class_cd?></td>
                            <td class=""><?=$row->detail_cd?></td>
                            <td class=""><?=$row->cd_name?></td>
                            <td class=""><?=$row->desc?></td>
                            <td class=""><?=$row->dummy_col_1?></td>
                            <td class=""><?=$row->dummy_col_2?></td>
                            <td class="">
                                <input type="checkbox" data-toggle="switch" <?=($row->use_is === "1" ? "checked" : "") ?> data-on-color="default" data-off-color="default" data-on-label="ON" data-off-label="OFF" class="bootstrap-switch btn-use-toggle" data-code="<?=$row->cd?>" data-use="<?=$row->use_is?>">
                            </td>
                            <td class="">
                                <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-edit" data-code="<?=$row->cd?>">수정</button>
                                <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-del" data-code="<?=$row->cd?>">삭제</button>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>

                    </tbody>
                </table>
            </div>
            <nav class="pagination-wrapper">
                <a href="#" class="btn btn-primary btn-round float-right btn-create">등록</a>
            </nav>
        </div>
    </div>
</div>
<script>

    $('.navbar-brand').html('설정 <sub class="text-muted"><i class="nc-icon nc-minimal-right"></i> </sub><sub class="text-title">코드관리</sub>');

    $("#class_cd").on("change", function(e){
        e.preventDefault();
        var class_cd = $(this).val();

        location.href = "/config/code/lists/" + class_cd;
    });

    $(".btn-create").on("click", function(e){
        e.preventDefault();
        location.href = "/config/code/create/" + $("#class_cd").val();
    });


    $(".btn-del").on("click", function(e){
        e.preventDefault();

        var code = $(this).data("code");

        var ok = function () {


            $.ajax({
                type: 'GET',
                url: '/config/code/delete_ajax/' + code,
                dataType: 'json',
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
            ablex.notify.message("코드 삭제를 취소했습니다.");
        };

        ablex.confirm(code + " 코드를 삭제하시겠습니까?", __TITLE__, ok, cancel);

    });

    //수정
    $(".btn-edit").on("click", function(){
        location.href = "/config/code/edit/" + $(this).data("code");
    });

    //사용여부 변경
    $(".btn-use-toggle").on("switchChange.bootstrapSwitch", function(e, state){
        e.preventDefault();

        var $this = $(this);
        var code = $this.data("code");
        var use = ($this.data("use") == "1" ? "0" : "1");

        var ok = function () {

            $.ajax({
                type: 'GET',
                url: '/config/code/use_ajax/' + code + "/" + use,
                dataType: 'json',
                error: function (xhr, textStatus, errorThrown) {
                    alert('error :' + xhr.status);
                    return false;
                },
                success: function (data) {

                    if(data.result === "SUCCESS"){

                        ablex.alert.success(data.msg);
                        $this.data("use", use);

                    }else{

                        ablex.alert.error(data.msg);
                        $this.bootstrapSwitch('toggleState');
                        return false;
                    }

                },
                complete: function () {
                }
            }); //ajax end
        };

        var cancel = function () {
            ablex.notify.message("정보 변경을 취소했습니다.");
            $this.bootstrapSwitch('toggleState');
        };

        if(state === true) {
            ablex.confirm(code + " 코드의 사용여부를 사용으로 변경하시겠습니까?", __TITLE__, ok, cancel);
        } else{
            ablex.confirm(code + " 코드의 사용여부를 미사용으로 변경하시겠습니까?", __TITLE__, ok, cancel);
        }

    });

</script>
