<?php
foreach ($lists as $key => $row) {

    $str_sales_status = "";
    switch ($row->sales_status) {
        case "1" :
            $str_sales_status = "배송가능";
            break;
        case "3" :
            $str_sales_status = "배송불가";
            break;
        case "5" :
            $str_sales_status = "품절";
            break;
        case "9" :
            $str_sales_status = "판매중지";
            break;
    } // End switch
    ?>
    <tr>
        <td class="text-left"><?=$row->item_name;?> (<?=$row->unit_count;?> <?=$row->unit_name;?>)</td>
        <td class="text-center">
            <input type="text" class="form-control numeric tbl-in-input" style="width:50px;" />
            <input type="hidden" value="<?=$row->sale_price;?>" />
        </td>
        <td class="text-right">￦ <?=number_format($row->sale_price);?></td>
        <td class="text-right">￦ <span class="calc-amount">0</span></td>
        <td><?=$str_sales_status;?></td>
        <td><?=($row->is_display=="1")?"노출":"비노출";?></td>
        <td>
            <button type="button" class="btn btn-info btn-sm btn-lyr-exchange" data-itemcd="<?=$row->item_cd;?>" data-itemname="<?=$row->item_name;?> (<?=$row->unit_count;?> <?=$row->unit_name;?>)">적용</button>
        </td>
    </tr>
<?php
} // End foreach

if (count($lists) === 0) {
    echo "<tr><td colspan='7' style='line-height: 200px;text-align: center;'>검색된 정보가 없습니다.</td></tr>";
} // End if
?>