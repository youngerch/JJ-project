<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-23
 */

if (count($lists) > 0) {

    foreach ($lists as $key => $row) {
        $amount = "-";
        if ( intVal($row->amount) > 0 ) {
            $amount = number_format($row->amount);
        } // End if

        //결제 유형 [0]공백 - 기한만료, [1]일반결제, [5]정기결제, [9]정기결제해지
        switch ($row->payment_type) {
            case "1" :
                $payment_type = "일반결제";
                break;
            case "5" :
                $payment_type = "정기결제";
                $payment_type .= "<br>(매월 ".$row->auto_bill_day."일)";
                break;
            case "6" :
                $payment_type = "연결제";
                break;
            case "7" :
                $payment_type = "정기결제수단변경";
                break;
            case "9" :
                $payment_type = "정기결제해지";
                break;
            default :
                $payment_type = "-";
                break;
        } // End switch
        ?>
        <tr>
            <td><?=substr($row->reg_date, 0, 16);?></td>
            <td><?=$payment_type;?></td>
            <td class="text-right"><?=$amount?></td>
            <td><?=$row->reason;?></td>
            <td><?=$row->expire_date;?></td>
        </tr>
    <?php
    } // End foreach
}
else {
    echo "<tr><td colspan='5' style='line-height: 100px;text-align: center;'>조회된 내역이 없습니다.</td></tr>";
} // End if
    ?>
