@extends('admin.master',['headerTitle'=>'','block'=>'9'])
@section('style')
    <link rel="stylesheet" href="//unpkg.com/view-design/dist/styles/iview.css">
@stop

@section('left_content')
    <div id="vue_target" style="margin-top: 24px;">
        <Card title="视频案例">

            <div style="text-align: right;margin-bottom: 24px;">
                <i-button type="primary" @click="add">新增视频案例</i-button>
            </div>

            <s-table :columns="columns" :data="loadData" ref="table">

            </s-table>
        </Card>


        <Modal
                v-model="editor_flag"
                :title="modal_title"
                width="500px"
                @on-ok="asyncOK"
                :loading="loading"
                footer-hide="true"
        >

            <div>
                <i-form ref="formValidate" :label-width="103" >

                    <form-item label="案例标题：" class="required-item">
                        <div class="form-value" >
                            <i-input placeholder="" style="display: inline-block" v-model="title">
                        </div>
                    </form-item>


                    <form-item label="视频：" class="required-item">
                        <div class="form-value" >
                            <upload-block v-model="url" ref="upload"></upload-block>
                        </div>
                    </form-item>


                    <form-item label="是否主视频：" class="required-item">
                        <div class="form-value" >
                            <i-select  style="width:260px" v-model="is_main">
                                <i-option value="1">是</i-option>
                                <i-option value="0">否</i-option>
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
                'upload-block': 'url:/vue/uploadblocknew.vue?v=66'
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
                        title: '标题',
                        key: 'title',
                    },
                    {
                        title: '是否主视频',
                        key: 'is_main',
                        render: (h, params) => {



                            params.row.is_main


                            return h('div', {}, params.row.is_main?'是':'');

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
                                }, '编辑'),
                                h('a', {
                                    attrs: {
                                        href: "javascript:doDelete('"+encode_str+"')",
                                        class: "view_btn",
                                        style: "margin-left: 24px"
                                    }
                                }, '删除')
                            ]);

                        }
                    },
                ],
                title: '',
                editor_flag: false,
                loadData: parameter => {
                    return new Fly().get('/admin/index/media-list', Object.assign(parameter, {
                    })).then((res)=>{
                        res = JSON.parse(res.data)
                        return res.data.result
                    })
                },
                url: '',
                is_main: ''
            },
            methods:{

                edit(record)
                {
                    console.log('编辑值:')
                    console.log(record)
                    this.title = record.title
                    // this.mobile = record.mobile
                    this.id = record.id
                    this.is_main = (record.is_main == '1')?'1':'0'
                    this.$refs.upload.setValue(record.url)
                    // this.url = record.url
                    // this.status = record.status.toString()
                    this.editor_flag = true
                },

                doDelete(record)
                {
                    let _self = this
                    this.$Modal.warning({
                        'title':'操作提醒',
                        'content': '您确定要删除该案例吗？',
                        'closable': true,
                        'onOk': function(){
                            new Fly().post('/admin/index/delete-media',{id: record.id}).then((res)=>{
                                // location.reload()
                                _self.$refs.table.reload()
                            }).catch((err)=>{
                                // location.reload()
                                _self.$refs.table.reload()
                            })
                        }
                    })
                },

                add:function(){
                    this.title = ''
                    // this.url = ''
                    this.id = ''
                    this.is_main = '0'
                    this.$refs.upload.setValue('')
                    this.editor_flag = true
                },

                asyncOK(){

                    //值判断
                    if( !this.title )
                    {

                        this.$Message.error('请输入标题');
                        return
                    }

                    // if( !this.password )
                    // {
                    //     this.$Message.error('This is an error tip');
                    //     return
                    // }


                    return new Fly().post('/admin/index/media', Object.assign({}, {
                        id: this.id,
                        title: this.title,
                        url: this.url,
                        is_main: this.is_main
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
                modal_title:function(){
                    if( this.id )
                    {
                        return '修改视频案例'
                    }else{
                        return '新增视频案例'
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


        function doDelete(record)
        {
            // alert(record)
            record = decodeURIComponent($.base64.decode(record))
            target_vue.doDelete(JSON.parse(record))
        }
    </script>
@stop
