@extends('admin.master',['headerTitle'=>'','block'=>'1'])
@section('left_content')
    <div class="row header-title" style="margin-top: -1px;height: 56px;padding-top: 16px;padding-bottom: 25px;">
        <div class="fs-14-fc-4E5661">案例详情/编辑</div>
    </div>

    <div style="">
       <div style="width: 640px;margin: 0 auto;margin-top: 24px;">
           <form role="form">
               <div class="form-group">
                   <label for="name">标题</label>
                   <input type="text" value="{{$record->title}}" name="title" class="form-control" id="name" placeholder="请输入标题">
               </div>
               <div class="form-group">
                   <label for="inputfile">封面图片</label>
                   <div style="width: 128px;height: 128px" onclick="uploadCover()" class="essay_img">
                       <img src="{{$record->cover_img?$record->cover_img:'/admin/images/add_img.png'}}" style="width: 100%;height: 100%;object-fit: contain"/>
                   </div>
               </div>
               <div class="form-group">
                   <label for="inputfile">文章内容</label>
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

        var ue = UE.getEditor('container');
        ue.ready(function() {
            //设置编辑器的内容
            ue.setContent(pageConfig.content);
        });

        new SubmitButton({
            selectorStr:"#do_publish",
            prepositionJudge:function(){

                if ( !$('input[name="title"]').val() )
                {
                    mAlert('标题不能为空');
                    return;
                }

                if( !$('.essay_img').find('img').attr('src') )
                {
                    mAlert('封面图片不能为空');
                    return;
                }

                var content = ue.getContent();
                if( content.length == 0 ) {
                    mAlert('内容不能为空');
                    return;
                }

                return true;
            },
            data:function(){
                return {title:$('input[name="title"]').val(),content:ue.getContent(),cover_image:$('.essay_img').find('img').attr('src')};
            },
            callback:function(el,val){
                location.href = 'http://tp.cc/admin/index/case?id='  + val.data
            }
        });

        function uploadCover(){
            $('input[name="images[]"]').click();
        }


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