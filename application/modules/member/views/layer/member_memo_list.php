<?php
/**
 * Created by Kim Chang Soo <cs.kim@ablex.co.kr>
 * Created on 2021-06-23
 */

foreach ($lists as $key => $row) {
?>
    <tr>
        <td><?=$row->reg_date;?></td>
        <td class="text-left"><?=nl2br($row->memo);?></td>
        <td><?=$row->admin_name;?></td>
    </tr>
<?php
} // End if

if (count($lists) == 0) {
    ?>
    <tr>
        <td colspan="3">등록된 메모가 없습니다.</td>
    </tr>
<?php
} // End if
    ?>
