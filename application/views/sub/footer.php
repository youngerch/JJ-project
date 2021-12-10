                </div>
                <!-- end:: Content -->
            </div>
        </div>
    </div>
</div>
<!-- end:: Page -->

<!-- 회원 정보 레이어 팝업 -->
<div class="modal fade" id="modal-member-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<!-- 공용 레이어 팝업 -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<!-- 공용 레이어 팝업 스몰사이즈 -->
<div class="modal fade" id="modal-small-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<!-- 운송번호 개별 입력 팝업 -->
<div class="modal fade" id="modal_search_member" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal_search_member">
        </div>
    </div>
</div>

<!-- 엑셀다운로드 Target -->
<iframe id="ifrmExcel" name="ifrmExcel" width="100%" height="0" frameborder="0"></iframe>

<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop" title="Scroll To Top"><i class="fa fa-arrow-up"></i></div>
<!-- end::Scrolltop -->

<script type="text/javascript">
$(document).ready(function() {
    //회원 정보 상세보기
    $(this).off('click', '.btn-member-detail').on('click', '.btn-member-detail', function(e) {
        e.preventDefault();

        var _member_seq = $(this).data('seq');
        var _modal_url = "/member/detail/" + _member_seq

        $('#modal-member-form .modal-content').load(_modal_url, function() {});
    });

    //회원 임시 비밀번호 발송
    $(this).off('click', '.btn-change-password').on('click', '.btn-change-password', function(e) {
        e.preventDefault();

        var _member_seq = $(this).data('seq');

        $.ajax({
            type: 'POST',
            url: '/member/change_password_ajax',
            dataType: 'json',
            data : { 'member_seq' : _member_seq },
            error: function (xhr, textStatus, errorThrown) {
                swal.fire('error :' + xhr.status);
                return false;
            },
            success: function (data) {
                swal.fire(data.msg);
                return false;
            }
        }); //ajax end
    });
});
</script>

</body>
<!-- end::Body -->

</html>