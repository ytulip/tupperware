@extends('admin.master',['headerTitle'=>'','block'=>'6'])
@section('left_content')
    <div class="mt-32 padding-col">


        <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: 24px;">
            <div class="row" style="font-size: 0;text-align: right;">
                <div style="float:left;margin-left: 15px;line-height: 34px;" class="fs-16-fc-232A31">质保管理</div>
                <div style="position: relative;display: inline-block;">
                    <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;" class="fs-14-fc-93989e fn-fa" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}" name="keyword" placeholder="输入关键字搜索"/>
                    <a style="position: absolute;right: 10px;top:9px;" class="search_btn"><img src="/images/icon_search_nor@3x.png" width="14px"/></a>
                </div>
                <a class="btn-new search_btn_download" style="margin-left: 16px;">新增质保</a>
            </div>
        </div>


        <div class="tr-border fs-14-fc-4E5761 fn-fa bg-fc" style="margin-top: -1px;">
            <div class="row">
                <div class="col-md-4 col-lg-4">车牌号码</div>
                <div class="col-md-4 col-lg-4">质保年限</div>
                <div class="col-md-4 col-lg-4">操作</div>
            </div>
        </div>

        {{--<div class="block-card">--}}
        @foreach($paginate as $item)
            <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: -1px;"><div class="row">
                    <div class="col-md-4 col-lg-4">
                        {{$item->brand_card}}
                    </div>
                    <div class="col-md-4 col-lg-4" style="line-height: 24px;">{{$item->quality_year}}年</div>
                    <div class="col-md-4 col-lg-4" style="line-height: 24px;"><a onclick="goDetail({{$item->id}})">编辑</a> <a style="margin-left: 24px;" onclick="deleteQuality({{$item->id}})">删除</a></div>
                </div></div>
        @endforeach
        {{--</div>--}}
        @if($paginate->perPage() > count($paginate) )
            @for($i =0;$paginate->perPage() > (count($paginate) + $i);$i++)
                <div class="tr-border fs-14-fc-4E5761 fn-fa record-item" style="border-bottom: 0;border-top: 0;"><div class="row">
                        <div class="col-md-2 col-lg-2">&nbsp;</div>
                        <div class="col-md-2 col-lg-2"></div>
                        <div class="col-md-2 col-lg-2"></div>
                        <div class="col-md-4 col-lg-4"></div>
                        <div class="col-md-2 col-lg-2"></div>
                    </div></div>
            @endfor
            <div style="border-top: 1px solid #EAEEF7"></div>
        @endif

        {{--<div class="block-card">--}}
        @if($paginate->lastPage() > 1)
            <div class="tr-border" style="overflow: hidden;margin-top: -1px;">
                <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>
            </div>
        @endif
        {{--</div>--}}

    </div>
@stop

@section('script')
    <script>
        function goDetail(id)
        {
            location.href = '/admin/index/quality?id=' + id;
        }

        function deleteQuality(id)
        {
            //confirm， 是否确定删除


            if(confirm('是否确定要删除')){
                $.ajax({
                    url:'/admin/index/delete-quality',
                    data:{id: id},
                    type:'post',
                    // contentType: false,
                    // processData: false,
                    dataType:'json',
                    success:function(data){
                        location.reload()
                    }
                });
            }

        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        $('.selectpicker').on('changed.bs.select',function(e){
            // console.log(e);
            // console.log($('.selectpicker1').val());
            location.href = '/admin/index/records?province=' + $('.selectpicker1').val() + '&work_no=' + $('input[name="work_no"]').val();
        });

        $('.search_btn').click(function(){
            location.href = '/admin/index/qualitys?keyword='  + $('input[name="keyword"]').val();
        });

        $('.search_btn_download').click(function(){
            // location.href = '/admin/index/records?download=1&province=' + $('.selectpicker1').val() +  '&work_no=' + $('input[name="work_no"]').val();
            location.href = '/admin/index/quality?type=add'
        });
    </script>
@stop
