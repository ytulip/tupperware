<template>
  <div style="text-align: left;">

    <div style="display: inline-block;margin-right: 24px;margin-bottom: 24px" v-for="(item, index) in value">
      <upload-block v-model="item" @delete="remove(index)"></upload-block>
    </div>

    <upload-block v-model="add_src" ref="add" style="display: inline-block;"></upload-block>

  </div>
</template>
<script>


module.exports = {
  data() {
    return {
      value: this.itemValue,
      add_src: ''
    }
  },
  model: {
    prop: 'itemValue',
    event: 'selectterm'
  },

  components: {
    // 将组建加入组建库
    'upload-block': 'url:/vue/uploadblockpic.vue?v=77'
  },

  watch: {
    value: function (newval) {
      this.$emit('selectterm',newval);
    },
    add_src: function (newval){
      //新值
      if(newval)
      {
        this.value.push(newval)
        //
        console.log('重置为空')
        this.$refs.add.deleteImg()
      }
    }
  },

  props: {
    itemValue:{
      type: String,
      default: ''
    },
    desc:{
      type: String,
      default: ''
    },
  },
  methods: {

    remove(index)
    {
      this.value.splice(index, 1)
    },

    deleteImg()
    {
      this.value = ''
    },

    getFile() {
      // var that = this;
    },
    uploadImg() {
      this.$refs.filElem.dispatchEvent(new MouseEvent('click'))
    },
    pageInit() {
    }
  },
  mounted() {
  },
  computed: {
    logoUrl: function () {
      return this.value;
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
