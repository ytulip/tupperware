<!DOCTYPE HTML>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->
    <link href="/admin/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="/admin/css/style.css?v={{env('VERSION')}}" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="/admin/css/morris.css" type="text/css"/>
    <link rel="stylesheet" href="/admin/css/bootstrap-select.css" type='text/css' />
    <!-- Graph CSS -->
    <link href="/admin/css/font-awesome.css" rel="stylesheet">
    <link href="/admin/css/admin.css?v={{env('VERSION')}}" rel="stylesheet">
    <link href="/js/plugin/dateinput/bootstrap-datetimepicker.css" rel="stylesheet"/>
    <!-- jQuery -->
    <script src="/admin/js/jquery-2.1.4.min.js"></script>
    <!-- //jQuery -->
    <!-- lined-icons -->
    <link rel="stylesheet" href="/admin/css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <style>

        #menu-academico-sub{display: none;}
        .menu-fa{display: none;}
        .sidebar-collapsed .menu-fa{display: inline-block;}
        .sidebar-collapsed #menu-academico-sub{display: inline-block;}
        .sidebar-collapsed .sub-menu{display: none;}

        .sub-menu{padding-left: 40px;}

        #admin_message_wrap #admin_message_panel{display: none;}
        #admin_message_wrap:hover #admin_message_panel{display: block;}


        #admin_user_panel {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 110px;
            text-align: center;
            background-color: #ffffff;
        }

        #admin_user_panel li {
            display: block;
            border-bottom: 1px solid #e9e9ea;
        }

        #admin_user_wrap:hover #admin_user_panel{display: block;}

        .sidebar-menu{background-color: #ffffff;}
        #menu li a{background-color: #ffffff;font-family: PingFangSC-Regular;font-size: 14px;color: #93989E;border-left: 0;}
        #menu li a:hover{color: #93989E;}
        #menu li a:hover{background-color: #ffffff;}

        .menu-a-active{color:#E01885 !important;position: relative;}
        .menu-a-active:hover{color:#E01885 !important;}
        .menu-a-active:before{
            content: "";
            position: absolute;
            border-left: 3px solid #E01885;
            height: 20px;
            left: 1px;
            display: block;

        }
    </style>
    @section('style')
        @show
</head>
<body>
<div class="page-container">
    <!--/content-inner-->
    <div class="left-content">
        <div class="mother-grid-inner">
        <div class="row header-title">
            <div class="col-md-6 col-lg-6">{!! $headerTitle !!}</div>
            <div class="col-md-3 col-lg-3">&nbsp;</div>
            <div class="col-md-3 col-lg-3" style="text-align: right;">
                <div style="line-height: 52px;display: inline-block;position: relative;" id="admin_user_wrap">
<img src="/images/statusbar_user_nor@3x.png" style="width: 30px;"/>
                    <span class="fs-14-fc-232A31" style="margin-left: 8px;">{{\App\Util\AdminAuth::user()->email}}</span><span class="caret" style="margin-left: 8px"></span>
                    <ul style="position: absolute;z-index: 99;right:0;border:1px solid #EAEEF7;" id="admin_user_panel">
                        <li><a href="/admin/index/user-info" class="fs-14-fc-4E5761">个人信息</a></li>
                        <li><a href="/passport/admin-login-out" class="fs-14-fc-4E5761">退出登录</a></li>
                    </ul>
                </div>
            </div>
        </div>
        @section('left_content')

            @show
        </div>
        <div style="margin-bottom: 45px;"></div>
    </div>
    <!--//content-inner-->
    <!--/sidebar-menu-->
    <div class="sidebar-menu" style="box-shadow: none;border-right:1px solid #EAEEF7; ">
        <header class="logo1" style="background-color: #ffffff;">
            <img src="/images/logo-clean.png" width="149px;" onclick="goHref('/admin/index/home')"/>
            {{--<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>--}}
        </header>
        <div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
        <div class="menu">
            @if(!isset($hideList))
            <ul id="menu" >
                 <li id="menu-academico"><a href="/admin/index/home" class="@if(!isset($block) || !$block) menu-a-active @endif"><i class="fa fa-envelope nav_icon menu-fa"></i><span>库存管理</span><div class="clearfix"></div></a></li>
                <li id="menu-academico"><a href="/admin/index/records" class="@if( isset($block) && ($block == 1)) menu-a-active @endif"><i class="fa fa-envelope nav_icon menu-fa"></i><span>首页banner</span><div class="clearfix"></div></a></li>
                <li id="menu-academico"><a href="/admin/index/cases" class="@if( isset($block) && ($block == 1)) menu-a-active @endif"><i class="fa fa-envelope nav_icon menu-fa"></i><span>精选案例</span><div class="clearfix"></div></a></li>
                <li id="menu-academico"><a href="/admin/index/records" class="@if( isset($block) && ($block == 1)) menu-a-active @endif"><i class="fa fa-envelope nav_icon menu-fa"></i><span>资讯管理</span><div class="clearfix"></div></a></li>
                <li id="menu-academico"><a href="/admin/index/records" class="@if( isset($block) && ($block == 1)) menu-a-active @endif"><i class="fa fa-envelope nav_icon menu-fa"></i><span>产品系列</span><div class="clearfix"></div></a></li>
                <li id="menu-academico"><a href="/admin/index/records" class="@if( isset($block) && ($block == 1)) menu-a-active @endif"><i class="fa fa-envelope nav_icon menu-fa"></i><span>质保管理</span><div class="clearfix"></div></a></li>
            </ul>
                @endif
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    var toggle = true;

    $(".sidebar-icon").click(function() {
        if (toggle)
        {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position":"absolute"});
        }
        else
        {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function() {
                $("#menu span").css({"position":"relative"});
            }, 400);
        }

        toggle = !toggle;
    });
</script>
<!--js -->
<script src="/admin/js/jquery.nicescroll.js"></script>
<script src="/admin/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="/admin/js/bootstrap.min.js"></script>
<!-- /Bootstrap Core JavaScript -->
<!-- morris JavaScript -->
<script src="/admin/js/jquery.base64.js"></script>
<script src="/admin/js/raphael-min.js"></script>
<script src="/admin/js/morris.js"></script>
<script src="/admin/js/echarts.simple.min.js?v={{env('VERSION')}}"></script>
<script src="/admin/js/layer/layer/layer.js"></script>
<script src="/admin/js/common.js"></script>
<script src="/admin/js/bootstrap-select.js"></script>
<script src="/js/vue.js"></script>
<script>
    jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
</script>
<script src="/js/plugin/dateinput/bootstrap-datetimepicker.js"></script>
<script src="/js/plugin/dateinput/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    function goHref(href)
    {
        location.href = href;
    }
</script>
@section('script')
    @show
</body>
</html>