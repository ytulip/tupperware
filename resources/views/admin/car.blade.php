@extends('admin.master',['headerTitle'=>'','block'=>'7'])
@section('left_content')
    <div class="mt-32 padding-col">


        <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: 24px;">
            <div class="row" style="font-size: 0;text-align: right;">
                <div style="float:left;margin-left: 15px;line-height: 34px;" class="fs-16-fc-232A31">{{$car->car_brand}}-车型管理</div>
                <div style="position: relative;display: inline-block;">
                    <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;" class="fs-14-fc-93989e fn-fa" value="{{\Illuminate\Support\Facades\Request::input('work_no')}}" name="work_no" placeholder="输入关键字搜索"/>
                    <a style="position: absolute;right: 10px;top:9px;" class="search_btn"><img src="/images/icon_search_nor@3x.png" width="14px"/></a>
                </div>
                {{--                <a class="btn-new search_btn_download" style="margin-left: 16px;">新增质保</a>--}}
            </div>
        </div>


        <div class="tr-border fs-14-fc-4E5761 fn-fa bg-fc" style="margin-top: -1px;">
            <div class="row">
                <div class="col-md-3 col-lg-3">车牌号码</div>
                <div class="col-md-3 col-lg-3">状态</div>
                <div class="col-md-3 col-lg-3">价格等级</div>
                <div class="col-md-3 col-lg-3">操作</div>
            </div>
        </div>

        {{--<div class="block-card">--}}
        @foreach($paginate as $item)
            <div class="tr-border fs-14-fc-4E5761 fn-fa"><div class="row">
                    <div class="col-md-3 col-lg-3">
                        {{$item->car_brand}}
                    </div>
                    <div class="col-md-3 col-lg-3" style="line-height: 24px;">{{$item->status? '启用': '禁用'}}</div>
                    <div class="col-md-3 col-lg-3">{{$item->price_type}}</div>
                    <div class="col-md-3 col-lg-3" style="line-height: 24px;"><a href="javascript: setType({{$item->id}})">编辑等级</a>  <a style="margin-left: 24px;" href="javascript: setStatus({{$item->id}},{{$item->status?1:0}})">{{$item->status? '禁用': '启用'}}</a></div>
                </div></div>
        @endforeach
        {{--</div>--}}
        @if($paginate->perPage() > count($paginate) )
            @for($i =0;$paginate->perPage() > (count($paginate) + $i);$i++)
                <div class="tr-border fs-14-fc-4E5761 fn-fa record-item" style="border-bottom: 0;border-top: 0;"><div class="row">
                        <div class="col-md-3 col-lg-3">&nbsp;</div>
                        <div class="col-md-3 col-lg-3"></div>
                        <div class="col-md-3 col-lg-3"></div>
                        <div class="col-md-3 col-lg-3"></div>
                        <div class="col-md-3 col-lg-3"></div>
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
            location.href = '/admin/index/car?id=' + id;
        }

        function setType(id)
        {
            layer.prompt({title: '设置价格等级', formType: 2}, function(pass, index){
                $.ajax({
                    data:{
                        id: id,
                        price_type: pass
                    },
                    url: '/admin/index/car',
                    type:'post',
                    dataType:'json',
                    success:function(data){
                        location.reload()
                        // $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>');
                        // if(data.status) {
                        //     $('.essay_img').find('img').attr('src',data.data[0]);
                        // } else {
                        //     alert(data.desc);
                        // }
                    }
                });
                // alert(pass)
                // layer.closeAll();
                // layer.prompt({title: '随便写点啥，并确认', formType: 2}, function(text, index){
                //     layer.close(index);
                //     // layer.msg('演示完毕！您的口令：'+ pass +'<br>您最后写下了：'+text);
                // });
            });
            // $.ajax({
            //     data:{
            //         id: id,
            //         type: status?'0':'1'
            //     },
            //     url: '/admin/index/car',
            //     type:'post',
            //     dataType:'json',
            //     success:function(data){
            //         // $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>');
            //         // if(data.status) {
            //         //     $('.essay_img').find('img').attr('src',data.data[0]);
            //         // } else {
            //         //     alert(data.desc);
            //         // }
            //     }
            // });
        }

        function setStatus(id, status)
        {
            $.ajax({
                data:{
                    id: id,
                    status: status?'0':'1'
                },
                url: '/admin/index/car',
                type:'post',
                dataType:'json',
                success:function(data){
                    location.reload()
                    // $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>');
                    // if(data.status) {
                    //     $('.essay_img').find('img').attr('src',data.data[0]);
                    // } else {
                    //     alert(data.desc);
                    // }
                }
            });
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
            location.href = '/admin/index/records?province=' + $('.selectpicker1').val() +  '&work_no=' + $('input[name="work_no"]').val();
        });

        $('.search_btn_download').click(function(){
            // location.href = '/admin/index/records?download=1&province=' + $('.selectpicker1').val() +  '&work_no=' + $('input[name="work_no"]').val();
            location.href = '/admin/index/quality?type=add'
        });
    </script>
@stop