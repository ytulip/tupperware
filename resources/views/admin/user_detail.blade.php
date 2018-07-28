@extends('admin.master',['headerTitle'=>''])
@section('left_content')
    <div class="row header-title" style="margin-top: -1px;height: 106px;padding-top: 16px;padding-bottom: 25px;">
        <div class="fs-14-fc-4E5661">用户管理/详情</div>
        <div class="fs-16-fc-232A31" style="margin-top: 24px;"><span>用户ID:{{$user->work_no}}</span><span style="margin-left: 35px;">所属身份:{{$user->province}}</span><span style="margin-left: 35px;">上传次数:{{count($list)}}</span></div>
    </div>

    <div style="">
        @foreach($list as $item)
        <div class="tr-border-24">
            <div class="cus-row-col-6"><span class="fs-24-fc-232A31 fw-m">{{date('d',strtotime($item->created_at))}}月</span><span style="font-size: 14px;line-height: 16px;">{{date('m',strtotime($item->created_at))}}月</span><span class="fs-14-fc-93989E">{{date('H:i:s',strtotime($item->created_at))}}</span><span class="fs-14-fc-93989E" style="margin-left: 16px;">{{date('Y',strtotime($item->created_at))}}年</span></div>
            <ul class="album" style="margin-top: 24px;">
                @if($item->img1)
                <li onclick='viewPhoto(["{{env('IMAGE_PREFIX') . $item->img1}}"])'><a href="javascript:void(0)"><div class="img-wrapper img-liquid" style="background-image:url('{{$item->img1}}')"></div></a></li>
                @endif
                @if($item->img2)
                <li onclick='viewPhoto(["{{env('IMAGE_PREFIX') . $item->img2}}"])'><a href="javascript:void(0)"><div class="img-wrapper img-liquid" style="background-image:url('{{$item->img2}}')"></div></a></li>
                    @endif
            </ul>
        </div>
            @endforeach
    </div>
@stop

@section('script')
    <script>
        function goDetail(id)
        {
            location.href = '/admin/index/user-detail?id=' + id;
        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        function viewPhoto(urls)
        {
            console.log(urls);
            layer.photos({
                photos: {
                    "data": [   //相册包含的图片，数组格式
                        {
                            "src": urls[0], //原图地址
                        }
                    ]
                } //格式见API文档手册页
                ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机
            });
        }
    </script>
@stop