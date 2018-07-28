@extends('admin.master',['headerTitle'=>'','block'=>'1'])
@section('left_content')
    <div class="mt-32 padding-col">


        <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: 24px;">
            <div class="row" style="font-size: 0;text-align: right;">
                <div style="float:left;margin-left: 15px;line-height: 34px;" class="fs-16-fc-232A31">收集列表</div>
                <div style="position: relative;display: inline-block;">
                    <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;" class="fs-14-fc-93989e fn-fa" placeholder="输入ID搜索"/>
                    <a style="position: absolute;right: 10px;top:9px;"><img src="/images/icon_search_nor@3x.png" width="14px"/></a>
                </div>

                <a class="btn-new" style="margin-left: 16px;">导出数据</a>
            </div>
        </div>


        <div class="tr-border fs-14-fc-4E5761 fn-fa bg-fc" style="margin-top: -1px;">
            <div class="row">
                <div class="col-md-2 col-lg-2">上传时间</div>
                <div class="col-md-2 col-lg-2">用户ID</div>
                <div class="col-md-2 col-lg-2">所属省份</div>
            </div>
        </div>

        {{--<div class="block-card">--}}
        @foreach($paginate as $item)
            <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: -1px;"><div class="row" onclick="goDetail({{$item->id}})">
                    <div class="col-md-2 col-lg-2">{{date('Y/m/d H:i:s',strtotime($item->upload_at))}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->work_no}}</div>
                    <div class="col-md-2 col-lg-2">{{$item->province}}</div>
                </div></div>
        @endforeach
        {{--</div>--}}

        {{--<div class="block-card">--}}
        <div class="tr-border" style="overflow: hidden;margin-top: -1px;">
            <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>
        </div>
        {{--</div>--}}

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
    </script>
@stop