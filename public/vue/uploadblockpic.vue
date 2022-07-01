<template>
  <div>


    <div  style="height: 100px;border-radius: 4px;border: 1px solid #d9d9d9;display: inline-block;text-align: center;position: relative;width: 100px;overflow: hidden;cursor: pointer;" class="upload_block" :upload_src="value">





      <img  src="/images/delete.png" class="ab-t-t-x-y delete_icon" style="width: 28px;line-height: 100px;font-size: 32px;z-index: 999;"
            v-if="value" @click.stop="deleteImg" />




      <img src="/images/add.png" style="width: 28px;margin-top: 34px;line-height: 100px;font-size: 32px;"
           v-if="!value" @click.stop="uploadImg"/>


      <img :src="logoUrl" v-else class="cert_img"  @click.stop="openInNew"/>

      <!--重新上传的小icon-->
      <div class="re-upload" v-if="value" @click="uploadImg">重新上传</div>
    </div>

    <div style="font-size: 12px;color: #9c9c9c">{{desc}}</div>


    <form style="display: none;" ref="upload">
      <input ref="filElem" type="file" name="images[]" style="display: none"
             accept="image/gif,image/jpeg,image/png" @change="getFile"/>
    </form>

  </div>
</template>
<script>


module.exports = {
  data() {
    return {
      value: this.itemValue
    }
  },
  model: {
    prop: 'itemValue',
    event: 'selectterm'
  },

  watch: {
    value: function (newval) {
      this.$emit('selectterm',newval);
    },
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


    openInNew(){
      window.open(this.logoUrl, '_blank')
    },

    deleteImg()
    {
      this.value = ''
      this.$emit('delete', {})
    },

    getFile() {
      // var that = this;
      const inputFile = this.$refs.filElem.files[0];
      if (inputFile) {
        if (inputFile.type !== 'image/jpeg' && inputFile.type !== 'image/png' && inputFile.type !== 'image/gif') {
          alert('不是有效的图片文件！');
          return;
        }

        // var formElement = document.querySelector("#data-form");
        var formElement = this.$refs.upload
        var formData = new FormData(formElement);



        new Fly().post('/index/album-image',formData).then((res)=>{


          console.log('返回值')
          console.log(res)

          res = JSON.parse(res.data)

          this.$refs.filElem.value = '';

          if( res.status)
          {
            this.value = res.data
            this.$emit('ok', {})
          }


          // $('input[name="picture"]').val('')
          // this.authUploadFlag = false;
          // this.$refs.table.reload()
        })

        // uploadCertificate(formData).then((res) => {
        //   this.$refs.filElem.value = '';
        //   if (res.errcode === 0) {
        //     this.value = res.url;
        //   } else {
        //     this.$notification['error']({
        //       message: '错误',
        //       description: res.errmsg,
        //       duration: 4
        //     });
        //   }
        // })
      } else {
        return;
      }
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
