<template>
  <div>
    <i-table :columns="columns" :data="localDataSource" border ></i-table>

    <div style="text-align: right;margin-top: 24px;">
      <Page :total="dataCount" :page-size="pageSize" :current="current"
            show-total  @on-change="changepage"/>
    </div>


    <Spin size="large" fix v-if="localLoading"></Spin>

  </div>
</template>
<script>


module.exports = {
  data() {
    return {
      logo_path: '',
      localDataSource: [],
      pageSize: 10,
      dataCount: 30,
      current: 1,
      localLoading: false

    }
  },
  props: {
    desc: String,
    columns: Object,
    data: {
      type: Function,
      required: true
    },
  },
  watch:
  {
    current:function(newval)
    {
      // alert(newval)
      this.loadData()
    }
  },
  created(){
    this.loadData()
  },
  methods: {


    changepage(index) {
      //需要显示开始数据的index,(因为数据是从0开始的，页码是从1开始的，需要-1)
      // let _start = (index - 1) * this.pageSize;
      // //需要显示结束数据的index
      // let _end = index * this.pageSize;
      // //截取需要显示的数据
      // this.nowData = this.data.slice(_start, _end);
      // //储存当前页
      // this.pageCurrent = index;

      //真分页
      //

      // alert(index)
      // alert(this.current)
      this.current = index
    },

    reload(){
      this.loadData()
    },


    forceReload()
    {
      this.dataCount = 0
      this.current =1
      this.loadData()
    },

    /**
     * 加载数据方法
     * @param {Object} pagination 分页选项器
     * @param {Object} filters 过滤条件
     * @param {Object} sorter 排序条件
     */
    loadData (pagination, filters, sorter) {
      console.log('加在数据')
      this.localLoading = true
      // const parameter = Object.assign({
      //       pageNo: (pagination && pagination.current) ||
      //           this.showPagination && this.localPagination.current || this.pageNum,
      //       pageSize: (pagination && pagination.pageSize) ||
      //           this.showPagination && this.localPagination.pageSize || this.pageSize
      //     }
      // )
      const parameter = Object.assign({
            pageNo: this.current,
            pageSize: this.pageSize
          },{}
      )



      const result = this.data(parameter)
      // // 对接自己的通用数据接口需要修改下方代码中的 r.pageNo, r.totalCount, r.data
      // // eslint-disable-next-line
      if ((typeof result === 'object' || typeof result === 'function') && typeof result.then === 'function') {
        result.then(r=>{
          console.log('内部返回值')
          console.log(r)

          this.dataCount = r.totalCount

          this.localDataSource = r.data
          this.localLoading = false
        })
      }
      //   result.then(r => {
      //     this.localPagination = this.showPagination && Object.assign({}, this.localPagination, {
      //       current: r.pageNo, // 返回结果中的当前分页数
      //       total: r.totalCount, // 返回结果中的总记录数
      //       showSizeChanger: this.showSizeChanger,
      //       pageSize: (pagination && pagination.pageSize) ||
      //           this.localPagination.pageSize
      //     }) || false
      //     // 为防止删除数据后导致页面当前页面数据长度为 0 ,自动翻页到上一页
      //     if (r.data.length === 0 && this.showPagination && this.localPagination.current > 1) {
      //       this.localPagination.current--
      //       this.loadData()
      //       return
      //     }
      //
      //     // 这里用于判断接口是否有返回 r.totalCount 且 this.showPagination = true 且 pageNo 和 pageSize 存在 且 totalCount 小于等于 pageNo * pageSize 的大小
      //     // 当情况满足时，表示数据不满足分页大小，关闭 table 分页功能
      //     try {
      //       if ((['auto', true].includes(this.showPagination) && r.totalCount <= (r.pageNo * this.localPagination.pageSize))) {
      //         this.localPagination.hideOnSinglePage = true
      //       }
      //     } catch (e) {
      //       this.localPagination = false
      //     }
      //     this.localDataSource = r.data // 返回结果中的数组数据
      //     this.localLoading = false
      //   })
      // }
    },
  },
  mounted() {
  },
  computed: {
    logoUrl: function () {
      return this.logo_path;
    }
  }
}
</script>

<style>


.delete_icon{
  display: none;
}

.upload_block:hover .delete_icon{
  display: block;
}

.cert_img
{
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.short_input{
  width: 45px;
  border: none;
  /*line-height: ;*/
  border-bottom: 1px solid #222222;
  padding: 0;
  line-height: 16px;
  text-align: center;
  outline:0;
}

.re-upload
{
  position: absolute;
  bottom: 0;
  right: 0;
  font-size: 12px;
  line-height: 24px;
  padding: 0 10px;
  background-color: rgba(0,0,0,.6);
  color:#efefef;
}

.ab-t-t-x-y{
  top:50%;
  left:50%;
  transform: translate(-50%,-50%);
  -webkit-transform: translate(-50%,-50%);
  position: absolute;
}
</style>
