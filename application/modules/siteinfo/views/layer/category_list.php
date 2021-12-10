<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-30
 */

foreach ( $lists as $key => $row ) {
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
} // End foreach
    ?>