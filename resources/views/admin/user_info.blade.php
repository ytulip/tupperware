@extends('admin.master',['headerTitle'=>'','hideList'=>true])
@section('left_content')
    <div class="mt-32 padding-col">
        <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: 24px;">
            <div class="row" style="font-size: 0;text-align: right;">
                <div style="float:left;line-height: 34px;" class="fs-16-fc-232A31">基本信息</div>
            </div>
            <div class="row" style="border-top: 1px solid #EAEEF7;margin-top: 16px;" id="user_panel">
                <div class="cus-row-col-12">
                    <div class="t-al-c" style="margin-top: 60px;">
                        <span class="fs-14-fc-4E5661">账号</span>
                        <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 320px;margin-left: 23px;" class="fs-14-fc-93989e fn-fa" placeholder="输入账号" readonly value="{{$user->email}}"/>
                        <span class="fs-14-fc-E01885" style="margin-left: 16px;" v-on:click="openEmailLayer">修改</span>
                    </div>
                    <div class="t-al-c" style="margin-top: 14px;margin-bottom: 40px;">
                        <span class="fs-14-fc-4E5661">密码</span>
                        <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 320px;margin-left: 23px;" class="fs-14-fc-93989e fn-fa" type="password" placeholder="输入密码" readonly="" value="123456"/>
                        <span class="fs-14-fc-E01885" style="margin-left: 16px;" v-on:click="openPassLayer">修改</span>
                    </div>


                    <div style="position: fixed;top:0;left:0;right: 0;bottom: 0;z-index: 9999;" v-if="emailLayerFlag">
                        <div style="position: absolute;top:0;right: 0;left: 0;bottom: 0;background-color: rgba(35,42,49,.5)"></div>
                        <div class="ab-t-t-x-y" style="background: #FFFFFF;border: 1px solid #DCE0E5;box-shadow: 0 4px 12px 0 rgba(0,0,0,0.20);border-radius: 2px;padding: 16px 24px;box-sizing: border-box;">
                            <div style="font-size: 16px;color: #000000;line-height: 18px;">修改账号</div>
                            <div style="margin-top: 16px;border-top: 1px solid #EAEEF7;margin-left:-24px;margin-right: -24px; "></div>

                            <div class="t-al-c" style="margin-top: 18px;">
                                <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 392px;" class="fs-14-fc-93989e fn-fa" placeholder="请输入新的账号" v-model="email"/>
                            </div>

                            <div style="margin-top: 24px;" class="t-al-r">
                                <a class="btn-new2" v-on:click="closeEmailLayer">取 消</a>
                                <a class="btn-new" v-on:click="emailLayerYes">确 定</a>
                            </div>
                        </div>
                    </div>

                    <div style="position: fixed;top:0;left:0;right: 0;bottom: 0;z-index: 9999;" v-if="passLayerFlag">
                        <div style="position: absolute;top:0;right: 0;left: 0;bottom: 0;background-color: rgba(35,42,49,.5)"></div>
                        <div class="ab-t-t-x-y" style="background: #FFFFFF;border: 1px solid #DCE0E5;box-shadow: 0 4px 12px 0 rgba(0,0,0,0.20);border-radius: 2px;padding: 16px 24px;box-sizing: border-box;">
                            <div style="font-size: 16px;color: #000000;line-height: 18px;">修改密码</div>
                            <div style="margin-top: 16px;border-top: 1px solid #EAEEF7;margin-left:-24px;margin-right: -24px; "></div>
                            <div class="t-al-c" style="margin-top: 14px;">
                                <span class="fs-14-fc-4E5661">旧密码</span>
                                <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 328px;margin-left: 16px;" class="fs-14-fc-93989e fn-fa" type="password" placeholder="" v-model="password"/>
                            </div>

                            <div class="t-al-c" style="margin-top: 18px;">
                                <span class="fs-14-fc-4E5661">新密码</span>
                                <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 328px;margin-left: 16px;" class="fs-14-fc-93989e fn-fa" type="password" placeholder="" v-model="newPassword"/>
                            </div>

                            <div style="margin-top: 24px;" class="t-al-r">
                                <a class="btn-new2" v-on:click="closePassLayer">取 消</a>
                                <a class="btn-new" v-on:click="passLayerYes">确 定</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
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

        var userPanelVue = new Vue({
            el:"#user_panel",
            data:{
                passLayerFlag:false,
                emailLayerFlag:false,
                email:'',
                password:'',
                newPassword:''
            },
            methods:{
                openPassLayer:function()
                {
                    this.passLayerFlag = true;
                },
                closePassLayer:function()
                {
                    this.passLayerFlag = false;
                },
                passLayerYes:function()
                {
                    if( !this.newPassword )
                    {
                        mAlert('新密码不能为空');
                        return;
                    }

                    $.get('/admin/index/modify-password',{password:this.password,newPassword:this.newPassword},function(data){
                        if(data.status){
                            mAlert('修改成功');
                            location.reload(true);
                        } else {
                            mAlert(data.desc);
                        }
                    },'json');
                },
                openEmailLayer:function()
                {
                    this.emailLayerFlag = true;
                },
                closeEmailLayer:function()
                {
                    this.emailLayerFlag = false;
                },
                emailLayerYes:function()
                {
                    if( !this.email )
                    {
                        mAlert('账号不能为空');
                        return;
                    }

                    $.get('/admin/index/modify-email',{email:this.email},function(data){
                        if(data.status){
                            mAlert('修改成功');
                            location.reload(true);
                        } else {
                            mAlert(data.desc);
                        }
                    },'json');
                }
            }
        });
    </script>
@stop