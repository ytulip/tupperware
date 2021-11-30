
@extends('admin.master',['headerTitle'=>'','block'=>'10'])
@section('style')
    <link rel="stylesheet" href="//unpkg.com/view-design/dist/styles/iview.css">
@stop

@section('left_content')
    <div id="vue_target" style="margin-top: 24px;">
        <Card title="质保审核">
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




                    <form-item label="质保手机号：" class="required-item">
                        <div class="form-value" v-html="record.mobile">

                        </div>
                    </form-item>

                    <form-item label="车牌号码：" class="required-item">
                        <div class="form-value" v-html="record.brand_card">>

                        </div>
                    </form-item>

                    <form-item label="被保车型：" class="required-item">
                        <div class="form-value" v-html="record.car_type">>

                        </div>
                    </form-item>

                    <form-item label="交车日期：" class="required-item">
                        <div class="form-value" v-html="record.valid_date">>

                        </div>
                    </form-item>

                    <form-item label="施工店面：" class="required-item">
                        <div class="form-value" v-html="record.store">>

                        </div>
                    </form-item>

                    <form-item label="施工部位：" class="required-item">
                        <div class="form-value" v-html="record.part">>

                        </div>
                    </form-item>

                    <form-item label="产品：" class="required-item">
                        <div class="form-value" v-html="record.product">>

                        </div>
                    </form-item>


                    <form-item label="车膜颜色：" class="required-item">
                        <div class="form-value" v-html="record.color">>

                        </div>
                    </form-item>

                    <form-item label="质保年限：" class="required-item">
                        <div class="form-value" v-html="record.seri_no">>

                        </div>
                    </form-item>


                    <form-item label="质保图片：" class="required-item">
                        <div class="form-value" >
                            <img style="width: 100%;margin: 12px;" v-for="(item, index) in imgs" :src="item"/>
                        </div>
                    </form-item>



                    <form-item label="状态：" class="required-item">
                        <div class="form-value" >

                            <i-select  style="width:260px" v-model="status">
                                <i-option value="1">通过</i-option>
                                <i-option value="0">不通过</i-option>
                            </i-select>


                        </div>
                    </form-item>


                    <form-item label="不通过原因：" class="required-item">
                        <div class="form-value" >
                            <i-input v-model="remark" type="textarea" :rows="4"  />
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
                remark: '',
                record: {},
                imgs: [],
                columns: [
                    {
                        title: '经销商名称',
                        key: 'name',
                    },
                    {
                        title: '车牌',
                        key: 'brand_card',
                    },
                    {
                        title: '手机号',
                        key: 'mobile'
                    },
                    {
                        title: '反馈内容',
                        key: 'remark'
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
                                }, '审核')

                            ]);

                        }
                    },
                ],
                editor_flag: false,
                loadData: parameter => {
                    return new Fly().get('/admin/index/audit-list', Object.assign(parameter, {
                    })).then((res)=>{
                        res = JSON.parse(res.data)
                        return res.data.result
                    })
                }
            },
            methods:{

                edit(record)
                {
                    console.log('编辑值:')
                    console.log(record)
                    if( record.imgs )
                    {
                        this.imgs = JSON.parse(record.imgs)
                    }else{
                        this.imgs = []
                    }


                    this.record = record
                    this.name = record.name
                    this.mobile = record.mobile
                    this.id = record.id
                    this.status = '1'
                    this.remark = ''
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
                    if( this.status == '0' && !this.remark)
                    {

                        this.$Message.error('请输入不通过原因');
                        return
                    }
                    //
                    // if( !this.mobile )
                    // {
                    //     this.$Message.error('请输入经销商电话');
                    //     return
                    // }

                    // if( !this.password )
                    // {
                    //     this.$Message.error('This is an error tip');
                    //     return
                    // }


                    return new Fly().get('/admin/index/do-audit', Object.assign({}, {
                        id: this.id,
                        status: this.status,
                        remark: this.remark
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
                        return '审核'
                    }else{
                        return '审核'
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
