@extends('admin.master',['headerTitle'=>'','block'=>'8'])
@section('style')
    <link rel="stylesheet" href="//unpkg.com/view-design/dist/styles/iview.css">
@stop

@section('left_content')
   <div id="vue_target" style="margin-top: 24px;">
       <Card title="经销商管理">

           <div style="text-align: right;margin-bottom: 24px;">
               <i-button type="primary" @click="add">新增经销商</i-button>
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

                   <form-item label="经销商名称：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" style="display: inline-block" v-model="name">
                       </div>
                   </form-item>

                   <form-item label="经销商电话：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" style="display: inline-block" v-model="mobile">
                       </div>
                   </form-item>

                   <form-item label="密码：" class="required-item">
                       <div class="form-value" >
                           <i-input placeholder="" style="display: inline-block"  type="password" v-model="password">
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
                's-table': 'url:/vue/s-table.vue?v=66'
            },
            data: {
                name: '',
                mobile: '',
                password: '',
                status: '',
                loading: true,
                id: '',
                columns: [
                    {
                        title: '经销商名称',
                        key: 'name',
                    },
                    {
                        title: '经销商手机号',
                        key: 'mobile',
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
                    this.id = record.id
                    this.status = record.status.toString()
                    this.editor_flag = true
                },

                add:function(){
                    this.name = ''
                    this.mobile = ''
                    this.id = ''
                    this.status = ''
                    this.editor_flag = true
                },

                asyncOK(){

                    //值判断
                    if( !this.name )
                    {

                        this.$Message.error('请输入经销商名称');
                        return
                    }

                    if( !this.mobile )
                    {
                        this.$Message.error('请输入经销商电话');
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
                        status: this.status
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
                        return '修改经销商'
                    }else{
                        return '新增经销商'
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
