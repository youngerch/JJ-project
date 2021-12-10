<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">회원</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            [ 조회기간 : <?=Date('Y-m-d');?> ~ <?=Date('Y-m-d');?> ]
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-wrapper table-scroll">
            <table class="table-list">
                <colgroup>
                    <col>    <!-- 총가입자 -->
                    <col>    <!-- 금일가입자 -->
                    <col>    <!-- 금일탈퇴자 -->
                    <col>    <!-- 로그인회원 -->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">총가입자</th>
                    <th scope="col">금일가입자</th>
                    <th scope="col">금일탈퇴자</th>
                    <th scope="col">로그인회원</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="/member" class="cell-title__link"><?=$memberTotal;?>명</a></td>
                        <td><a href="/member" class="cell-title__link"><?=$joinCnt;?>명</a></td>
                        <td><a href="/member" class="cell-title__link"><?=$leaveCnt;?>명</a></td>
                        <td><a href="/member" class="cell-title__link"><?=$loginCnt;?>명</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>