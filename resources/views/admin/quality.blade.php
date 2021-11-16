@extends('admin.master',['headerTitle'=>'','block'=>'6'])
@section('left_content')
    <div class="row header-title" style="margin-top: -1px;height: 56px;padding-top: 16px;padding-bottom: 25px;">
        <div class="fs-14-fc-4E5661">案例详情/编辑</div>
    </div>

    <div style="">
        <div style="width: 640px;margin: 0 auto;margin-top: 24px;">
            <form role="form" id="data-form">
                <div class="form-group">
                    <label for="name">车牌号码</label>
                    <input type="text" value="{{$record->brand_card}}" name="brand_card" class="form-control" id="name" placeholder="请输入">
                </div>

                <div class="form-group">
                    <label for="name">被保车型</label>
                    <input type="text" value="{{$record->car_type}}" name="car_type" class="form-control" id="name" placeholder="请输入">
                </div>


                <div class="form-group">
                    <label for="name">交车日期</label>
                    <input type="text" value="{{$record->valid_date}}" name="valid_date" class="form-control" id="name" placeholder="请输入">
                </div>


                <div class="form-group">
                    <label for="name">施工店面</label>
                    <input type="text" value="{{$record->store}}" name="store" class="form-control" id="name" placeholder="请输入">
                </div>


                <div class="form-group">
                    <label for="name">施工部位</label>
                    <input type="text" value="{{$record->part}}" name="part" class="form-control" id="name" placeholder="请输入">
                </div>

                <div class="form-group">
                    <label for="name">产品</label>
                    <select class="form-control" name="product">
                        <option value="">
                            请选择
                        </option>
                        @foreach($classify as $key=>$item)
                            <option year="{{$item['year']}}" value="{{$item['item_name']}}" {{($record->product == $item['item_name'] )?'selected':''}}>
                                {{$item['item_name']}}-(质保{{$item['year']}}年)
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label for="name">车膜颜色</label>
                    <input type="text" value="{{$record->color}}" name="color" class="form-control" id="name" placeholder="请输入">
                </div>


                <div class="form-group">
                    <label for="name">膜批号</label>
                    <input type="text" value="{{$record->seri_no}}" name="seri_no" class="form-control" id="name" placeholder="请输入">
                </div>

                <div class="form-group">
                    <label for="name">施工质保年限</label>
                    <input type="text" value="{{$record->quality_year}}" name="quality_year" class="form-control" id="name" placeholder="请输入">
                </div>

                <div class="form-group">
                    <label for="inputfile">施工图</label>
                    <script id="container" name="content" type="text/plain">
这里写你的初始化内容
</script>
                </div>
            </form>

            <a style="display:inline-block;padding: 8px 18px;border-radius: 4px; background-color: #1889f9;color: #ffffff;" id="do_publish">提交</a>

        </div>

        <form style="display: none;" id="data-form">
            <input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>

        </form>
    </div>
@stop

@section('script')
    <!-- 配置文件 -->
    <script type="text/javascript" src="/admin/js/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/admin/js/ueditor/ueditor.all.js"></script>
    <script>

        var pageConfig = {
            content: '{!! isset($record->content)?$record->content:'' !!}'
        }


        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        var ue = UE.getEditor('container');
        ue.ready(function() {
            //设置编辑器的内容
            ue.setContent(pageConfig.content);
        });

        new SubmitButton({
            selectorStr:"#do_publish",
            prepositionJudge:function(){

                if ( !$('input[name="brand_card"]').val() )
                {
                    mAlert('车牌号码不能为空');
                    return;
                }

                if ( !$('input[name="car_type"]').val() )
                {
                    mAlert('被保车型不能为空');
                    return;
                }


                if ( !$('input[name="valid_date"]').val() )
                {
                    mAlert('交车日期不能为空');
                    return;
                }

                if ( !$('input[name="store"]').val() )
                {
                    mAlert('施工店面不能为空');
                    return;
                }

                if ( !$('input[name="part"]').val() )
                {
                    mAlert('施工部位不能为空');
                    return;
                }

                if ( !$('input[name="color"]').val() )
                {
                    mAlert('车膜颜色不能为空');
                    return;
                }

                if ( !$('input[name="seri_no"]').val() )
                {
                    mAlert('批号不能为空');
                    return;
                }

                if ( !$('input[name="quality_year"]').val() )
                {
                    mAlert('施工质保年限不能为空');
                    return;
                }

                var content = ue.getContent();
                if( content.length == 0 ) {
                    mAlert('施工图不能为空');
                    return;
                }

                return true;
            },
            data:function(){
                //获得data-form的值
                return {
                    brand_card:$('input[name="brand_card"]').val(),
                    car_type:$('input[name="car_type"]').val(),
                    valid_date:$('input[name="valid_date"]').val(),
                    store:$('input[name="store"]').val(),
                    part:$('input[name="part"]').val(),
                    color:$('input[name="color"]').val(),
                    seri_no:$('input[name="seri_no"]').val(),
                    quality_year:$('input[name="quality_year"]').val(),
                    product: $('select[name="product"]').val(),
                    content:ue.getContent()
                };
            },
            callback:function(el,val){
                location.href = '/admin/index/quality?id='  + val.data
            }
        });

        function uploadCover(){
            $('input[name="images[]"]').click();
        }
        $('select[name="product"]').change(function(){
            // console.log('产品值变化')
            // console.log($(this))
            // console.log($(this).val())

            $('input[name="quality_year"]').val($(this).find("option:selected").attr('year'))
        })


        $('body').on('change','input[name="images[]"]',function(){
            if(this.value){
                var formData = new FormData($("#data-form")[0]);
                $.ajax({
                    url:'/index/album-image',
                    data:formData,
                    type:'post',
                    contentType: false,
                    processData: false,
                    dataType:'json',
                    success:function(data){
                        $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png"/>');
                        if(data.status) {
                            $('.essay_img').find('img').attr('src',data.data[0]);
                        } else {
                            alert(data.desc);
                        }
                    }
                });
            }
        });
    </script>
@stop
