@extends('_layout.master')
@section('title')
<title>特百惠</title>
@stop
@section('style')
<style>
    html{height: 100%;font-family: PingFangSC-Regular;}
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

    .img-liquid{
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }


    input {
        caret-color: #E01885;
        outline: none;
        -webkit-appearance: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }
    input:focus::-webkit-input-placeholder{
        color: transparent;
    }

    ::-webkit-input-placeholder { /* WebKit browsers */
        color:    #93989E;
    }
    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color:    #93989E;
    }
    ::-moz-placeholder { /* Mozilla Firefox 19+ */
        color:    #93989E;
    }
    :-ms-input-placeholder { /* Internet Explorer 10+ */
        color:    #93989E;
    }
</style>
    <style>
        <style>
        html,body{
            background-color: #f9f9fb;
        }
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
        .item-opr span{line-height: 40px;display: inline-block;}
        .show-img{width: 100%;border-radius: 12px;}
        .pro-essay-barr{border-bottom: 1px solid #9c9c9c;margin: 20px 0;}


        .active-tab{font-weight: bold;position: relative;}


        .active-tab:after{
            border-bottom:solid 4px #98CC3D;
            position: absolute;
            right: 0;
            left: 0;
            content:'';
            display: block;
            top:22px;
        }



        .active-iframe{
            display: block !important;
        }

        .btn3{background-image: linear-gradient(-137deg, #B9E77D 0%, #78CD09 50%);  box-shadow: 0 8px 16px 0 rgba(139,217,75,0.46);border-radius: 44px;line-height: 44px;font-size: 16px;color:#ffffff;font-weight: 800;text-align: center;}

        .btn3:hover{color:#ffffff;}

        .swiper-container{width: 100%;}
        .swiper-slide img{width: 100%;}

        .red-v-l
        {
            height: 16px;
            border-left: 4px solid #C50081;
        }



        .fs-16-fc-212229-m{
            font-family: PingFangSC-Medium;
            font-size: 16px;
            color: #212229;
            line-height: 16px;
        }


        .fs-18-fc-212229-m{
            font-family: PingFangSC-Medium;
            font-size: 18px;
            color: #212229;
            line-height: 18px;
        }

        .op3{opacity: 0.3;}



        .next-icon{
            display: inline-block;
            width: 8px;
            height: 13px;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAA/CAYAAABjJtHDAAAAAXNSR0IArs4c6QAAAYdJREFUaAXN2t1tgzAQB/C6L4yRbpIMwAMjdLSOwAZJNknGQEKiXFUsCDbxx/3vzi9gY+GfzrqHw7iPSOu67msYhp+mab77vn9EpkGHP0Nv/4fdpmk6z8Ab9UPz0GPudYEV7LQ8c8495whepCO4wYVgmkCPO4JpAf9wKTANYDAhFkjoOifJSSpJsrZ1jZVIEo+jhXO2l+ajgRucNeAOZwkYxFkBRnEWgIc4beBbnCYwCacFTMZpALNw0sBsnCSwCCcFLMZJAKtwaGA1DglkwaGAbDgEkBXHDWTHcQIhOC4gDMcBhOJqgXBcDVAER8C2bS/jOF7pPqXNZeddBFdaD8NxpTD63AbF1cBo62G4WhgMxwGD4Lhg7DhOGCuOG8aGQ8BYcChYNQ4Jq8KhYcU4CVgRTgqWjZOEZeGkYck4DVgSTgv2FqcJO8Rpw6I4C7Agzgpsh7ME2+CswTzOIoxw2cfo6ANgQi3Nl4Yp0ZOEEdDjqHMElIbtcDGgBiyIewVqwaK4FVD1L7BfS2TUzqpVOAIAAAAASUVORK5CYII=');
            background-size: 8px 13px;
        }


        .prev-icon
        {
            display: inline-block;
            width: 8px;
            height: 13px;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAA/CAYAAABjJtHDAAAAAXNSR0IArs4c6QAAAYdJREFUaAXN2t1tgzAQB/C6L4yRbpIMwAMjdLSOwAZJNknGQEKiXFUsCDbxx/3vzi9gY+GfzrqHw7iPSOu67msYhp+mab77vn9EpkGHP0Nv/4fdpmk6z8Ab9UPz0GPudYEV7LQ8c8495whepCO4wYVgmkCPO4JpAf9wKTANYDAhFkjoOifJSSpJsrZ1jZVIEo+jhXO2l+ajgRucNeAOZwkYxFkBRnEWgIc4beBbnCYwCacFTMZpALNw0sBsnCSwCCcFLMZJAKtwaGA1DglkwaGAbDgEkBXHDWTHcQIhOC4gDMcBhOJqgXBcDVAER8C2bS/jOF7pPqXNZeddBFdaD8NxpTD63AbF1cBo62G4WhgMxwGD4Lhg7DhOGCuOG8aGQ8BYcChYNQ4Jq8KhYcU4CVgRTgqWjZOEZeGkYck4DVgSTgv2FqcJO8Rpw6I4C7Agzgpsh7ME2+CswTzOIoxw2cfo6ANgQi3Nl4Yp0ZOEEdDjqHMElIbtcDGgBiyIewVqwaK4FVD1L7BfS2TUzqpVOAIAAAAASUVORK5CYII=');
            background-size: 8px 13px;
            transform: rotate(180deg);
        }


        .barr-line{
            background: #FFFFFF;
            border: 1px solid #E1E1E1;
        }

        .active-type{
            border: 1px solid #C50081 !important;
            background-color: #ffffff !important;
        }

        .chosen{
            background: #C50081;
            border-radius: 14px;
            height: 28px;
            width: 28px;
            display: inline-block;
            color: #ffffff !important;
            line-height: 28px !important;
            opacity: 1;
        }

        .cus-row-col-1-7 span{line-height: 22px;}


        .fs-16-fc-080808-r {
            font-family: PingFangSC-Regular;
            font-size: 16px;
            color: #080808;
            letter-spacing: -0.39px;
            text-align: center;
            line-height: 16px;
        }


        .quantity-plus
        {
            font-family: PingFangSC-Medium;
            font-size: 20px;
            color: #212229;
        }

        .quantity-plus-icon
        {
            width: 21px;
            height: 21px;
        }

        .bill-panel{background: #FFFFFF;
            box-shadow: 0 2px 6px 0 #E7E9F0;
            border-radius: 5px;padding: 16px;}

        .fs-18-fc-000000-m{font-size: 18px;color:#000000;line-height: 18px;font-family: PingFangSC-Medium;letter-spacing: -0.35px;}
        .fs-16-fc-000000-m{font-size: 16px;color:#000000;line-height: 16px;font-family: PingFangSC-Medium;letter-spacing: -0.31px;}
        .fs-14-fc-000000-m{font-size: 14px;color:#000000;line-height: 14px;font-family: PingFangSC-Medium}


        .fs-14-fc-7E7E7E-r {
            font-size: 14px;
            color: #7E7E7E;
            line-height: 14px;
            font-family: PingFangSC-Regular;
            letter-spacing: -0.23px;
        }

        .short-line{display: inline-block;border-right: 4px solid;height: 16px;vertical-align:bottom;margin-right: 8px;}
    </style>
@stop
@section('container')

<div style="min-height: 100%;" id="list" class="vue-dpn">

    <div style="padding: 16px;">
        <div style="position: relative; margin-bottom: 24px;">
            <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 100%;box-sizing: border-box;" class="fs-14-fc-93989e fn-fa" placeholder="输入ID、商品名搜索" name="work_no" value="" v-model="keyword">
            <a style="position: absolute;right: 10px;top:9px;" class="search_btn"><img src="/images/icon_search_nor@3x.png" width="14px"></a>
        </div>


        <div class="bill-panel" style="margin-bottom: 16px;" v-for="(item,index) in currentList" v-on:click="editProduct(item.id)">
            <div class="row">
                <div class="cus-row-col-6">
                    <div class="cus-row-col-6 fs-14-fc-484848 f-f-r v-a-m" style="margin-left:8px; " ><span class="short-line" style="border-right-color: #C50081;display: inline-block;vertical-align: middle;" ></span><span style="display: inline-block;vertical-align: middle;">@{{ item.work_no }}</span></div>
                </div>
            </div>

            <div class="m-t-20">
                <div class="in-bl fs-18-fc-000000-m v-a-m">剩余库存<span style="color: #c50081;">@{{item.quantity}}</span>卷</div>
            </div>


            <div class="fs-14-fc-7E7E7E-r m-t-10">商品名称：@{{item.province}}</div>
        </div>


    </div>



    <div style="position: fixed;top:0;right: 0;left: 0;bottom: 0;background-color:rgba(0,0,0,.6);z-index: 999;" v-if="layerSwitch">
        <div style="background-color: #ffffff;border-radius: 8px;padding: 24px;position: absolute;transform: translate(-50%,-50%);top:50%;left: 50%;width: 80%;">


            <div class="cus-row cus-row-v-m">
                <div class="cus-row-col-6">
                    <span class="fs-18-fc-212229-m">库存编辑</span>
                </div>

                <div class="cus-row-col-6 t-al-r">
                    <div class="in-bl v-a-m quantity-plus-icon" v-on:click="deQuantity"><image src="/images/icon_out_nor@3x.png" class="quantity-plus-icon"/></div>
                    <div class="in-bl v-a-m" style="margin: 0 30px;"><input class="quantity-plus" v-model="quantity" style="display: inline-block;width: 30px;text-align: center;
    border: none;"/></div>
                    <div class="in-bl v-a-m quantity-plus-icon" v-on:click="addQuantity"><image src="/images/icon_add_nor@3x.png" class="quantity-plus-icon"/></div>
                </div>
            </div>

            <div style="margin:26px 0;border: 1px solid #e1e1e1;"></div>



            <div>
                <input placeholder="输入商品编码" style="display:inline-block;line-height: 18px;font-size: 17px;color: #232A31;letter-spacing: 0;background: #F7F7F9;border: 1px solid #EEEEEE;border-radius: 100px;width: 100%;text-align: center;box-sizing: border-box;padding: 13px 0;"  v-model="productId"/>
            </div>


            <div style="margin-top: 20px">
                <input placeholder="输入商品名称" style="display:inline-block;line-height: 18px;font-size: 17px;color: #232A31;letter-spacing: 0;background: #F7F7F9;border: 1px solid #EEEEEE;border-radius: 100px;width: 100%;text-align: center;box-sizing: border-box;padding: 13px 0;"  v-model="productName"/>
            </div>

            <div style="text-align: right;">

                <div style="margin-top: 32px;width: 90px;display: inline-block;">
                    <a class="t-al-c" style="display: inline-block;width: 100%;background: #999999;border-radius: 100px;line-height: 44px;font-size: 17px;color: #FFFFFF;letter-spacing: 0;" id="next_step" v-on:click="closeLayer">取消</a>
                </div>

                <div style="margin-top: 32px;width: 90px;display: inline-block;">
                    <a class="t-al-c" style="display: inline-block;width: 100%;background: #E01885;box-shadow: 0 2px 8px 0 rgba(224,24,133,0.36);border-radius: 100px;line-height: 44px;font-size: 17px;color: #FFFFFF;letter-spacing: 0;" id="next_step" v-on:click="saveProduct">保存</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer style="margin-bottom: 32px;position: fixed;bottom: 0;right: 0;left: 0;z-index:99;" class="t-al-c">
    <div class="btn4" onclick="addNew()">新增商品</div>
</footer>
@stop

@section('script')
<script>

    var pageConfig = {
        imagePrefix:'{{env('IMAGE_PREFIX')}}',
        list:''
    }

    function addNew()
    {
        listVue.layerSwitch = true;
        listVue.productId = '';
        listVue.productName = '';
        listVue.quantity = 0;
        listVue.id = '';
    }

    var listVue = new Vue(
        {
            el:"#list",
            data:{
                list:pageConfig.list,
                confirmFlag:false,
                confirmInd:-1,
                confirmId:0,
                quantity:0,
                productId:'',
                productName:'',
                layerSwitch:false,
                id:'',
                keyword:'',
                list:[],
            },
            created:function()
            {
                var _self = this;
                $('.vue-dpn').removeClass('vue-dpn');
                this.initList();
            },
            methods:{
                remove:function(ind,id)
                {
                    // alert(ind);
                    /*发起删除的网络请求*/
//                        this.list.splice(ind,1);
                    this.confirmFlag = true;
                    this.confirmInd = ind;
                    this.confirmId = id;
                },
                closeMask:function()
                {
                    this.confirmFlag = false;
                },
                yes:function()
                {
                    this.list.splice(this.confirmInd,1);
                    this.confirmFlag = false;
                    $.get('/index/delete',{id:this.confirmId});
                },
                previewImg:function (urls,current) {
                    // console.log(urls);
                    // console.log(current);

                    var urlArr = [];
                    $(urls).each(function(ind,obj){
                        urlArr.push(pageConfig.imagePrefix + obj);
                    });

                    var currentUlr = pageConfig.imagePrefix + current;

                    // console.log(currentUlr);
                    // console.log(urlArr);

                    wx.previewImage({
                        current: currentUlr,
                        urls: urlArr
                    });
                },
                deQuantity:function () {
                    if( this.quantity > 1)
                    {
                        this.quantity = this.quantity - 1;
                    }
                },
                addQuantity:function(){
                    this.quantity = this.quantity + 1;
                },
                saveProduct:function()
                {
                    this.layerSwitch= false;
                    var _self = this;
                    $.post('/index/album-image',{id:this.id,quantity:this.quantity,productId:this.productId,productName:this.productName},function(data){
                        if( data.status )
                        {
                            _self.initList();
                        }else {
                            mAlert(data.desc);
                        }

                    },'json');
                },
                closeLayer:function()
                {
                    this.layerSwitch = false;
                },
                initList:function()
                {
                    var _self = this;
                    $.get('/index/list',{},function(data){
                        _self.list = data.data;
                    },'json');
                },
                editProduct:function(id)
                {
                    for( var i=0; i < this.list.length; i++ )
                    {
                        if ( id == this.list[i].id )
                        {
                            this.id = id;
                            this.quantity = this.list[i].quantity;
                            this.productId = this.list[i].work_no;
                            this.productName = this.list[i].province;
                            this.layerSwitch = true;
                            return;
                        }
                    }
                }
            },
            computed:{
                currentList:function()
                {
                    var tmpList = [];

                    if( this.keyword )
                    {
                        var keyword = this.keyword;
                        var list = this.list;
                        for( var i=0; i < list.length; i++ )
                        {
                            if ( list[i].work_no.indexOf(keyword) != -1 || list[i].province.indexOf(keyword) != -1 )
                            {
                                tmpList.push(list[i]);
                            }
                        }
                    } else
                    {
                        tmpList = this.list;
                    }

                    return tmpList;
                }
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
                        listVue.list.unshift({urls:data.data,attach_msg:data.attach_msg});
                    } else {
                        alert(data.desc);
                    }
                },
                error:function(){
                    alert('网络异常');
                }
            });
        }
    });
</script>
@stop