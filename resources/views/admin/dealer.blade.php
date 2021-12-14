@extends('admin.master',['headerTitle'=>'','block'=>'8'])
@section('style')
    <link rel="stylesheet" href="//unpkg.com/view-design/dist/styles/iview.css">
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
   <div id="vue_target" style="margin-top: 24px;">
       <Card title="授权店管理">

           <div style="text-align: right;margin-bottom: 24px;">
               <i-button type="primary" @click="add">新增授权店</i-button>
           </div>

           <s-table :columns="columns" :data="loadData" ref="table">

           </s-table>
       </Card>


       <Modal
               v-model="editor_flag"
               :title="title"
               width="400px"
               @on-ok="asyncOK"
               :loading="loading"
               footer-hide="true"
       >

           <div>
               <i-form ref="formValidate" :label-width="103" >

                   <form-item label="授权店名称：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" style="display: inline-block" v-model="name"/>
                       </div>
                   </form-item>

                   <form-item label="授权店电话：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" style="display: inline-block" v-model="mobile">
                       </div>
                   </form-item>


                   <form-item label="授权店地址：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" type="textarea" :rows="4" style="display: inline-block" v-model="address" />
                       </div>
                   </form-item>

                   <form-item label="密码：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" style="display: inline-block"  type="password" v-model="password">
                       </div>
                   </form-item>


                   <form-item label="授权店头像：" class="required-item">
                       <div class="form-value" >
                           <upload-block v-model="header_img" ref="upload"></upload-block>
                       </div>
                   </form-item>


                   <form-item label="状态：" class="required-item">
                       <div class="form-value" >

                           <i-select  style="width:260px" v-model="status">
                               <i-option value="1">启用</i-option>
                               <i-option value="0">禁用</i-option>
                           </i-select>


                       </div>
                   </form-item>


               </i-form>
           </div>

           <div class="ivu-modal-footer" style="margin: 0 -16px;"> <button type="button" class="ivu-btn ivu-btn-primary" @click="asyncOK"><!----> <!----> <span>确定</span></button></div>
       </Modal>


   </div>
@stop

@section('script')
    <!-- 配置文件 -->
    <script type="text/javascript" src="/admin/js/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/admin/js/ueditor/ueditor.all.js"></script>
    <script type="text/javascript" src="/admin/js/vue.js"></script>
    <script src="//unpkg.com/view-design/dist/iview.min.js"></script>
    <script src="/admin/js/httpVueLoader.js"></script>
    <script src="/admin/js/fly.min.js"></script>
    <script src="/admin/js/jquery.base64.js"></script>



    <script>
        Vue.use(httpVueLoader);

        let target_vue = new Vue({
            el: '#vue_target',
            components:{
                's-table': 'url:/vue/s-table.vue?v=66',
                'upload-block': 'url:/vue/uploadblocknewheader.vue?v=78'
            },
            data: {
                name: '',
                mobile: '',
                password: '',
                address: '',
                status: '',
                loading: true,
                id: '',
                header_img: '',
                columns: [
                    {
                        title: '授权店名称',
                        key: 'name',
                    },
                    {
                        title: '授权店手机号',
                        key: 'mobile',
                    },
                    {
                        title: '授权店地址',
                        key: 'address',
                    },
                    {
                        title: '授权店头像',
                        key: 'header_img',
                        render: (h, params) => {


                            // console.log('操作值加密')
                            // console.log(params.row)
                            // let encode_str = $.base64.encode(encodeURIComponent(JSON.stringify(params.row)))

                            // let  txt = (params.row.status ==
                            // 1)?'启用':'禁用';

                            console.log('头像地址')
                            console.log(params.row.header_img)


                            return h('div', [
                                h('img', {
                                    attrs: {
                                        src: params.row.header_img,
                                        class: 'header_img'
                                    }
                                }, '')

                            ]);

                        }
                    },
                    {
                        title: '状态',
                        key: 'status',
                        render: (h, params) => {


                            // console.log('操作值加密')
                            // console.log(params.row)
                            // let encode_str = $.base64.encode(encodeURIComponent(JSON.stringify(params.row)))

                            let  txt = (params.row.status == 1)?'启用':'禁用';


                            return h('div', [
                                h('span', {
                                    attrs: {
                                        class: "view_btn"
                                    }
                                }, txt)

                            ]);

                        }
                    },
                    {
                        title: '操作',
                        render: (h, params) => {


                            console.log('操作值加密')
                            console.log(params.row)
                            let encode_str = $.base64.encode(encodeURIComponent(JSON.stringify(params.row)))



                            return h('div', [
                                h('a', {
                                    attrs: {
                                        href: "javascript:edit('"+encode_str+"')",
                                        class: "view_btn"
                                    }
                                }, '编辑')

                            ]);

                        }
                    },
                ],
                editor_flag: false,
                loadData: parameter => {
                    return new Fly().get('/admin/index/dealer-list', Object.assign(parameter, {
                    })).then((res)=>{
                        res = JSON.parse(res.data)
                        return res.data.result
                    })
                }
            },
            methods:{

                edit(record)
                {
                    // console.log('编辑值:')
                    console.log(record)
                    this.name = record.name
                    this.mobile = record.mobile
                    this.address = record.address
                    this.id = record.id
                    this.header_img = record.header_img
                    this.$refs.upload.setValue(this.header_img)
                    this.status = record.status.toString()
                    this.editor_flag = true
                },

                add:function(){
                    this.name = ''
                    this.mobile = ''
                    this.id = ''
                    this.status = ''
                    this.address = ''
                    this.header_img = ''
                    this.$refs.upload.deleteImg()
                    this.editor_flag = true
                },

                asyncOK(){

                    //值判断
                    if( !this.name )
                    {

                        this.$Message.error('请输入授权店名称');
                        return
                    }

                    if( !this.mobile )
                    {
                        this.$Message.error('请输入授权店电话');
                        return
                    }

                    // if( !this.password )
                    // {
                    //     this.$Message.error('This is an error tip');
                    //     return
                    // }


                    return new Fly().post('/admin/index/dealer', Object.assign({}, {
                        id: this.id,
                        name: this.name,
                        mobile: this.mobile,
                        password: this.password,
                        address: this.address,
                        status: this.status,
                        header_img: this.header_img
                    })).then((res)=>{
                        res = JSON.parse(res.data)

                        if( res.status )
                        {
                            this.editor_flag = false
                            this.$refs.table.reload()
                        }
                        // return res.data.result
                    })

                    return false
                }
            },
        computed:{
                title:function(){
                    if( this.id )
                    {
                        return '修改授权店'
                    }else{
                        return '新增授权店'
                    }
                }
            }
        })


        function edit(record)
        {
            // alert(record)
            record = decodeURIComponent($.base64.decode(record))
            target_vue.edit(JSON.parse(record))
        }
    </script>
@stop
