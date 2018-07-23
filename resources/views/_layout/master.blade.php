<!DOCTYPE HTML>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" href="/css/style.css?v={{env('VERSION')}}"/>
@section('title')
    <title></title>
@show
@section('style')
@show
<body>
@section('container')
@show
</body>
<script src="/js/jquery.min.js?v={{env('VERSION')}}"></script>
<script src="/js/jquery.serializejson.min.js?v={{env('VERSION')}}"></script>
<script src="/js/plugin/layer_mobile/layer.js?v={{env('VERSION')}}"></script>
<script src="/js/common.js?v={{env('VERSION')}}"></script>
<script src="/js/vue.js?v={{env('VERSION')}}"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    function goHref(href)
    {
        location.href = href;
    }
</script>
@section('script')

@show
</html>