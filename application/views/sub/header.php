<!DOCTYPE html>
<html lang="ko">
<!-- begin::Head -->
<head>
    <base href="/">
    <meta charset="utf-8">
    <title><?=__SITE_TITLE__;?></title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->
    <link rel="shortcut icon" href="<?php echo base_url('assets')?>/media/logos/favicon.ico">
    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="<?php echo base_url('assets')?>/css/admin.css" rel="stylesheet" type="text/css">
    <!--end::Global Theme Styles -->
    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="<?php echo base_url('assets')?>/plugins/global/plugins.bundle.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets')?>/js/scripts.bundle.js" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->
    <script src="<?php echo base_url('assets')?>/js/jquery.form.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets')?>/js/jquery.paging.min.js" type="text/javascript"></script>

    <script src="<?php echo base_url('assets')?>/js/bookcafe.js" type="text/javascript"></script>

    <script type="text/javascript">
        const __TITLE__ = "<?=__SITE_TITLE__;?>";
    </script>
</head>
<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-aside--enabled kt-aside--fixed">

<!-- begin:: Page -->

<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
    <div class="kt-header-mobile__logo">
        <a href="/">
            <img alt="Logo" src="<?php echo base_url('assets')?>/media/logos/logo_dark.png">
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
    </div>
</div>

<!-- end:: Header Mobile -->
<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

        <!-- begin:: Aside -->
        <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
        <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

            <!-- begin:: Aside -->
            <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
                <div class="kt-aside__brand-logo">
                    <a href="/">
                        <img alt="Logo" src="<?php echo base_url('assets')?>/media/logos/logo.png" style="filter: invert(10);">
                    </a>
                </div>
                <div class="kt-aside__brand-tools">
                    <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler"><span></span></button>
                </div>
            </div>
            <!-- end:: Aside -->

            <!-- begin:: Aside Menu -->
            <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
                    <ul class="kt-menu__nav ">
                        <li class="kt-menu__item kt-menu__item<?=($this->_module=="main")?"--active":"";?>" aria-haspopup="true">
                            <a href="/" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">홈</span></a>
                        </li>
                        <?php if(in_array("member", $this->_accessible_arr)) {?>
                        <li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="member")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">회원관리</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">회원관리</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="active")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/member/lists/1" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">회원목록</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="leave")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/member/lists/9" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">탈퇴회원목록</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <?php }?>
                        <?php if(in_array("cscenter", $this->_accessible_arr)) {?>
                        <li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="cscenter")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">고객센터</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">고객센터</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="inquiry")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/cscenter/inquiry" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">1:1문의</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="faq")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/cscenter/faq" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">FAQ관리</span></a></li>
                                    <!--li class="kt-menu__item <?=($this->_menu_item=="sms")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/cscenter/sms" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">SMS관리</span></a></li-->
                                </ul>
                            </div>
                        </li>
                        <?php }?>
                        <?php if(in_array("item", $this->_accessible_arr)) {?>
                        <li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="item")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">상품관리</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">상품관리</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="item")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/item/lists" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">상품목록</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="alarm")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/item/alarm" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">알림관리</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <?php }?>
                        <?php if(in_array("operation", $this->_accessible_arr)) {?>
                        <li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="operation")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">운영관리</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">운영관리</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="notice")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/operation/notice" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">공지관리</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="banner")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/operation/banner" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">배너관리</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <!--li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="statistics")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">통계</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">통계</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="member")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/statistics/member" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">회원통계</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="payment")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/statistics/payment" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">매출통계</span></a></li>
                                </ul>
                            </div>
                        </li-->
                        <?php }?>
                        <?php if(in_array("admin", $this->_accessible_arr)) {?>
                        <li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="admin")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">관리자관리</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">관리자관리</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="lists")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/admin/lists" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">관리자목록</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="permission")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/admin/permission" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">권한관리</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <?php }?>
                        <?php if(in_array("siteinfo", $this->_accessible_arr)) {?>
                        <li class="kt-menu__item kt-menu__item--submenu <?=($this->_module=="siteinfo")?"kt-menu__item--open kt-menu__item--here":"";?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">사이트설정</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">사이트설정</span></span></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="inspection")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/siteinfo/inspection" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">점검관리</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="docs")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/siteinfo/docs" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">문서관리</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="category")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/siteinfo/category" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">카테고리관리</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="allowips")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/siteinfo/allowips" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">접속아이피관리</span></a></li>
                                    <li class="kt-menu__item <?=($this->_menu_item=="company")?"kt-menu__item--active":"";?>" aria-haspopup="true"><a href="/siteinfo/company" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">회사정보</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
            <!-- end:: Aside Menu -->
        </div>
        <!-- end:: Aside -->

        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Header -->
            <div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">
                <!-- begin:: page location -->
                <div class="kt-subheader">
                    <div class="kt-subheader__main">
                        <h3 class='kt-subheader__title'>홈</h3><span class='kt-subheader__separator kt-hidden'></span><div class='kt-subheader__breadcrumbs'><a href='/' class='kt-subheader__breadcrumbs-home'><i class='flaticon2-shelter'></i></a></div>
                    </div>
                </div>
                <!-- end:: page location -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper">
                            <div class="kt-header__topbar-user">
                                <span class="kt-header__topbar-username kt-hidden-mobile"><b class="mr-1"><?=$this->_admin->name?></b>님,</span>
                                <span class="kt-header__topbar-welcome kt-hidden-mobile">반갑습니다.</span>
                            </div>
                        </div>
                    </div>
                    <div class="kt-header__topbar-item">
                        <div class="kt-header__topbar-wrapper">
                            <a href="/main/auth/logout" class="kt-header__topbar-icon"><i class="fa fa-power-off"></i></a>
                        </div>
                    </div>
                </div>
                <!-- end:: Header Topbar -->
            </div>
            <!-- end:: Header -->

            <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

                <!-- begin:: Content -->
                <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">