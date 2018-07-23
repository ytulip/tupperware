@extends('_layout.master')
@section('title')
    <title>特百惠</title>
@stop
@section('style')
    <style>
        html{height: 100%;}
        html,body{background-color: #f7f7f9;margin: 0;padding: 0;}
        .upload-item{background-color: #ffffff;margin-bottom: 24px;padding: 14px 14px 24px 14px;}

        .album{
            display: block;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .album li{
            list-style: none;
            width: 50%;
            padding-bottom: 50%;
            float: left;
            position: relative;
        }

        .album li:nth-child(odd) a{
            /*padding-left: 10px;*/
            /*padding-bottom: 10px;*/
            /*padding-right: 5px;*/
            padding-right:.5px ;
        }

        .album li:nth-child(even) a{
            /*padding-left: 5px;*/
            /*padding-bottom: 10px;*/
            /*padding-right: 10px;*/
            padding-left: .5px;
        }

        .album li a{
            display: block;
            position: absolute;
            top:0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .img-wrapper{
            background-color: #f1f1f1;
            width: 100%;
            height: 100%;
            position: relative;
        }
    </style>
@stop
@section('container')

    <div style="min-height: 100%;padding: 24px 0;" id="list">
        <div class="ab-t-t-x-y" v-if="!list.length">
            <div>
                <image src="/images/m/icon_pic_nor@3x.png" style="width: 261px;"/>
            </div>
        </div>

        <div class="upload-list" v-for="item in list">
            <div class="upload-item">
                <div class="item-header cus-row" style="padding: 10px 0;">
                    <div class="cus-row-col-6"><span class="fs-24-fc-232A31 fw-m">23</span><span>07月</span><span class="fs-14-fc-93989E">18:32:42</span></div>
                    <div class="cus-row-col-6 t-al-r"><a class="fs-14-fc-93989E">删除</a></div>
                </div>
                <div class="item-body" style="margin-top: 14px;">
                    <ul class="album" v-for="subitem in item">
                        <li><a href="javascript:play()"><div class="img-wrapper"><img v-bind:src="subitem"/></div></a></li>
                        {{--<li><a href="javascript:play()"><div class="img-wrapper"><img src=""/></div></a></li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer style="margin-bottom: 32px;position: fixed;bottom: 0;right: 0;left: 0;z-index:99;" class="t-al-c">
        <form style="display: none;" id="data-form">
            <input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png" multiple="multiple"/>
        </form>
        <div class="btn4" onclick="uploadCover()">上传图片</div>
    </footer>
@stop

@section('script')
    <script>


        var listVue = new Vue(
            {
                el:"#list",
                data:{
                    list:[]
                }
            }
        );

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
                        $('input[name="images[]"]').replaceWith('<input type="file" name="images[]"  style="display: none" accept="image/gif,image/jpeg,image/png" multiple="multiple"/>');
                        if(data.status) {
//                            $('.essay_img').find('img').attr('src',data.data[0]); 'http://static.liaoliaoy.com/' + data.data[0];
                            listVue.list.push([data.data]);
                        } else {
                            alert(data.desc);
                        }
                    }
                });
            }
        });
    </script>
@stop