@extends('admin.master',['headerTitle'=>'','block'=>'1'])
@section('left_content')
    <div class="row header-title" style="margin-top: -1px;height: 106px;padding-top: 16px;padding-bottom: 25px;">
        <div class="fs-14-fc-4E5661">资料收集/详情</div>
        <div class="fs-16-fc-232A31" style="margin-top: 24px;"><span>用户ID:{{$user->work_no}}</span><span style="margin-left: 35px;">所属身份:{{$user->province}}</span><span style="margin-left: 35px;">上传时间:{{date('Y/m/d',strtotime($record->created_at))}}</span></div>
    </div>

    <div style="">
        <div style="width: 749px;margin:0 auto;margin-top: 28px;">
            <img width="100%" src="{{$record->img1}}"/>
        </div>

        <div style="width: 749px;margin:0 auto;margin-top: 28px;">
            <img width="100%" src="{{$record->img2}}"/>
        </div>
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