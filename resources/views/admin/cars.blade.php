@extends('admin.master',['headerTitle'=>'','block'=>'7'])


@section('style')
    <link rel="stylesheet" href="/admin/css/iview.css">
    <style>
        .header_img{
            margin: 6px 0;
            width: 48px;
            height: 48px;
            border-radius: 100%;
        }
    </style>
@stop

@section('left_content')
    <div class="mt-32 padding-col" id="vue_target">


        <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: 24px;">
            <div class="row" style="font-size: 0;text-align: right;">
                <div style="float:left;margin-left: 15px;line-height: 34px;" class="fs-16-fc-232A31">车型管理</div>
                <div style="position: relative;display: inline-block;">
                    <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;" class="fs-14-fc-93989e fn-fa" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}" name="work_no" placeholder="输入关键字搜索"/>
                    <a style="position: absolute;right: 10px;top:9px;" class="search_btn"><img src="/images/icon_search_nor@3x.png" width="14px"/></a>
                </div>
                <a class="btn-new" style="margin-left: 16px;" @click="addNew">新增车型</a>
            </div>
        </div>


        <div class="tr-border fs-14-fc-4E5761 fn-fa bg-fc" style="margin-top: -1px;">
            <div class="row">
                <div class="col-md-4 col-lg-4">车牌号码</div>
                <div class="col-md-4 col-lg-4">状态</div>
                <div class="col-md-4 col-lg-4">操作</div>
            </div>
        </div>

        {{--<div class="block-card">--}}
        @foreach($paginate as $item)
            <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: -1px;" ><div class="row">
                    <div class="col-md-4 col-lg-4">
                        {{$item->car_brand}}
                    </div>
                    <div class="col-md-4 col-lg-4" style="line-height: 24px;">{{$item->status? '启用': '禁用'}}</div>
                    <div class="col-md-4 col-lg-4" style="line-height: 24px;">
                    <a @click="setItem({{$item->id}}, '{{$item->car_brand}}', {{$item->status}})">编辑</a>
                    
                    <a href="javascript:goDetail({{$item->brand_id}})" style="margin-left: 24px;">编辑子车型</a>


                        <a style="margin-left: 24px;" href="javascript: setStatus({{$item->id}},{{$item->status?1:0}})">{{$item->status? '禁用': '启用'}}</a>

                    </div>
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


        
        <Modal
                    v-model="add_flag"
                    :title="add_flag_title"
                    footer-hide="true"
        

            >
            <div>
                <i-form>
            <form-item label="名称：" :label-width="140" class="required-item">
                                <div>
                                    <i-input style="width: 320px;" size="small" v-model="item_value"></i-input>
                                </div>
                            </form-item>


                            <form-item label="状态：" :label-width="140" class="required-item">
                                <div>
                                <i-select v-model="status" size="small">
                                <i-option value="1">使用中</i-option>
                                <i-option value="0">已禁用</i-option>
                            </i-select>
                                </div>
                            </form-item>

                </i-form>


                <div style="text-align: center;">
                                <i-button type="primary" @click="nextStep">提交</i-button>
                            </div>

            </div>
        </Modal>


    </div>
@stop

@section('script')


<script src="/admin/js/iview.min.js"></script>
    <script src="/admin/js/httpVueLoader.js"></script>
    <script src="/admin/js/fly.min.js"></script>
    <script>

Vue.use(httpVueLoader);


let target_vue = new Vue({
            el: '#vue_target',
            data: {
                add_flag: false,
                name: '', 
                year: '',
                item_id: '',
                item_value: '',
                year: '',
                status: ''
            },
            computed:{
                add_flag_title(){
                    if( this.item_id )
                    {
                        return '修改车型';
                    }else{
                        return '新增车型';
                    }
                }
            },
            methods:{

                deleteItem(id){
                    //删除产品系列
                    this.$Modal.confirm({
                        'title': '操作确认',
                        'content': '是否确定删除该产品系列',
                        'onOk':function(){
                            flyPost('/admin/index/delete-classify', Object.assign(  {}, {
                                id: id
                            })).then((res)=>{
                                location.reload()
                            })
                        }
                    })
                }, 

                addNew(){
                    console.log('新增车辆')
                    this.item_id = ''
                    this.item_value = ''
                    this.year = ''
                    this.status = ''
                    this.add_flag = true
                },
                setItem(id, name, status )
                {

                    this.item_id = id
                    this.item_value = name
                    // this.year = year
                    this.status = status.toString()
                    this.add_flag = true

                },
                nextStep()
                {
                    if( !this.item_value )
                    {
                        this.$Message.error('产品名称必须填写');
                        return
                    }


                    if( this.status == '' )
                    {
                        this.$Message.error('状态必须选择');
                        return
                    }


                    this.submit_loading = true

                    flyPost('/admin/index/add-or-save-brand', Object.assign(  {}, {
                        item_name: this.item_value,
                        id: this.item_id,
                        status: this.status
                    })).then((res)=>{
                        location.reload()
                    })


                }
            }
        });



        function setStatus(id, status)
        {
            $.ajax({
                data:{
                    id: id,
                    status: status?'0':'1'
                },
                url: '/admin/index/cars',
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



        function goDetail(id)
        {
            // location.href = '/admin/index/car?id=' + id;
            window.open('/admin/index/car?id=' + id, '_blank')
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
            location.href = '/admin/index/cars?province=' + $('.selectpicker1').val() +  '&keyword=' + $('input[name="work_no"]').val();
        });

        $('.search_btn_download').click(function(){
            // location.href = '/admin/index/records?download=1&province=' + $('.selectpicker1').val() +  '&work_no=' + $('input[name="work_no"]').val();
            location.href = '/admin/index/quality?type=add'
        });
    </script>
@stop