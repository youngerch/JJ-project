<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-29
 */

?>
<div class="row">
    <section class="col-lg-4 col-sm-1">
        <!-- begin:: card -->
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">대분류</h3>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success mr-2 btn-category-create" data-type="A" data-toggle="modal" data-target="#modal-small-form">신규생성</button>
                    <button type="button" class="btn btn-sm btn-primary btn-sort-save" data-type="A">정렬저장</button>
                </div>
            </div>
            <div class="card-body">
                <div class="dual-listbox">
                    <div class="dual-listbox__container">
                        <div>
                            <ul class="dual-listbox__available" id="cate_a">
                                <?php
                                foreach ($categorys as $key => $row) {
                                    if ($row->is_del =="Y") {
                                        ?>
                                        <li class="dual-listbox__item" data-seq="<?=$row->seq;?>" data-type="<?=$row->cate_type;?>"><s>[<?=$row->cate_code;?>] <?=$row->cate_name_kor;?></s></li>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <li class="dual-listbox__item" data-seq="<?=$row->seq;?>" data-type="<?=$row->cate_type;?>">[<?=$row->cate_code;?>] <?=$row->cate_name_kor;?></li>
                                        <?php
                                    } // End if
                                } // End foreach;
                                ?>
                            </ul>
                        </div>
                        <div class="dual-listbox__buttons">
                            <button type="button" class="btn btn-secondary btn-icon btn-sort-up" data-type="A"><i class="fa flaticon2-up"></i></button>
                            <button type="button" class="btn btn-secondary btn-icon mt-2 btn-sort-down" data-type="A"><i class="fa flaticon2-down"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: card -->
    </section>
    <section class="col-lg-4 col-sm-1">
        <!-- begin:: card -->
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">중분류</h3>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success mr-2 btn-category-create" data-type="B" data-toggle="modal" data-target="#modal-small-form">신규생성</button>
                    <button type="button" class="btn btn-sm btn-primary btn-sort-save" data-type="B">정렬저장</button>
                </div>
            </div>
            <div class="card-body">
                <div class="dual-listbox">
                    <div class="dual-listbox__container">
                        <div>
                            <ul class="dual-listbox__available" id="cate_b"></ul>
                        </div>
                        <div class="dual-listbox__buttons">
                            <button type="button" class="btn btn-secondary btn-icon btn-sort-up" data-type="B"><i class="fa flaticon2-up"></i></button>
                            <button type="button" class="btn btn-secondary btn-icon mt-2 btn-sort-down" data-type="B"><i class="fa flaticon2-down"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: card -->
    </section>
    <section class="col-lg-4 col-sm-1">
        <!-- begin:: card -->
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">소분류</h3>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success mr-2 btn-category-create" data-type="C" data-toggle="modal" data-target="#modal-small-form">신규생성</button>
                    <button type="button" class="btn btn-sm btn-primary btn-sort-save" data-type="C">정렬저장</button>
                </div>
            </div>
            <div class="card-body">
                <div class="dual-listbox">
                    <div class="dual-listbox__container">
                        <div>
                            <ul class="dual-listbox__available" id="cate_c"></ul>
                        </div>
                        <div class="dual-listbox__buttons">
                            <button type="button" class="btn btn-secondary btn-icon btn-sort-up" data-type="C"><i class="fa flaticon2-up"></i></button>
                            <button type="button" class="btn btn-secondary btn-icon mt-2 btn-sort-down" data-type="C"><i class="fa flaticon2-down"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: card -->
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        //분류 선택
        $(this).off('click', '.dual-listbox__item').on('click', '.dual-listbox__item', function(e) {
            e.preventDefault();

            let _this       = $(this);
            let _parent     = $(this).closest('ul');
            let _type       = $(this).data('type');
            let _seq        = $(this).data('seq');
            let _target     = "";
            let _target_type= "";

            _parent.find('li').removeClass('dual-listbox__item--selected');
            _this.addClass('dual-listbox__item--selected');

            if (_type == "A") {
                $('#cate_b li').remove();
                $('#cate_c li').remove();

                _target = $('#cate_b');
                _target_type = "B"
            } else if (_type == "B") {
                $('#cate_c li').remove();
                _target = $('#cate_c');
                _target_type = "C"
            }

            if (_target != "") {
                get_category_list_ajax(_target_type, _seq, _target);
            }
        });

        //분류 수정
        $(this).off('dblclick', '.dual-listbox__item').on('dblclick', '.dual-listbox__item', function(e) {
            e.preventDefault();

            let _seq = $(this).data('seq');
            $('#modal-small-form .modal-content').load('/siteinfo/category_update_form/'+_seq, function() {
                $('#modal-small-form').modal('toggle');
            });
        });

        //정렬 순서 업
        $(this).off('click', '.btn-sort-up').on('click', '.btn-sort-up', function(e) {
            e.preventDefault();

            let _type = $(this).data('type');
            let _parent = $('#cate_' + _type.toLowerCase());
            let _target = _parent.find('li.dual-listbox__item--selected');

            if (_target.prev().length > 0) {    //위에 내용이 존재할 경우
                let _before = _target.prev();
                $(_target).insertBefore(_before);
            }
        });

        //정렬 순서 다운
        $(this).off('click', '.btn-sort-down').on('click', '.btn-sort-down', function(e) {
            e.preventDefault();

            let _type = $(this).data('type');
            let _parent = $('#cate_' + _type.toLowerCase());
            let _target = _parent.find('li.dual-listbox__item--selected');

            if (_target.next().length > 0) {    //아래에 내용이 존재할 경우
                let _after = _target.next();
                $(_target).insertAfter(_after);
            }
        });

        //분류 신규생성
        $(this).off('click', '.btn-category-create').on('click', '.btn-category-create', function(e) {
            e.preventDefault();

            var _type = $(this).data('type');
            var _seq = "";
            if ( _type == "A" ) {
                _seq = 0;
            } else if ( _type == "B" ) {
                _seq = parseInt($('#cate_a li.dual-listbox__item--selected').data('seq'));    //대분류 순번
            } else {
                _seq = parseInt($('#cate_b li.dual-listbox__item--selected').data('seq'));    //중분류 순번
            }

            if (isNaN(_seq)) {
                $('#modal-small-form .modal-content').html('');
                swal.fire({
                    text: "상위 분류를 선택 후 이용해 주세요.",
                    showCancelButton: false,
                    confirmButtonText: '확인',
                }).then(function(result){
                    if (result.value) {
                        $('#modal-small-form').modal('toggle');
                    }
                });
                return false;
            }
            else {
                $('#modal-small-form .modal-content').load('/siteinfo/category_create_form/'+_type+'/'+_seq, function() {});
            }
        });

        //정렬 순서 저장
        $(this).off('click', '.btn-sort-save').on('click', '.btn-sort-save', function(e) {
            e.preventDefault();

            var _categorys	= "";
            var _type	= $(this).data('type');
            var _target	= "cate_" + _type.toLowerCase();

            $('#'+_target+' li').each(function() {
                _categorys += ( _categorys == "" ) ? "" : "|";
                _categorys += $(this).data('seq');
            });

            set_category_order(_type, _categorys);
        });
    });

    var get_category_list_ajax = function(type, seq, target) {
        $.ajax({
            type: 'POST',
            url: '/siteinfo/category_list_ajax',
            dataType: 'json',
            data : {
                'cate_type' : type,
                'cate_seq'  : seq
            },
            error: function (xhr, textStatus, errorThrown) {
                swal.fire('error :' + xhr.status);
                return false;
            },
            success: function (data) {
                if (data.result === "SUCCESS") {
                    target.html(data.html);
                }
                else {
                    swal.fire(data.msg);
                    return false;
                }
            }
        }); //ajax end
    }

    var set_category_order = function(type, categorys) {
        $.ajax({
            type: 'POST',
            url: '/siteinfo/category_order_change_ajax',
            dataType: 'json',
            data : {
                'cate_type' : type,
                'categorys' : categorys
            },
            error: function (xhr, textStatus, errorThrown) {
                swal.fire('error :' + xhr.status);
                return false;
            },
            success: function (data) {
                swal.fire(data.msg);
                return false;
            }
        }); //ajax end
    }
</script>