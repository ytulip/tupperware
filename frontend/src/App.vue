<template>
  <div id="app">
      <div style="min-height: 100%;" id="list">

          <div v-if="!layerSwitch">

              <div style="padding: 16px;margin-top: 30px;">
                  <div style="position: relative; margin-bottom: 24px;">
                      <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 100%;box-sizing: border-box;" class="fs-14-fc-93989e fn-fa" placeholder="输入ID、商品名搜索" name="work_no" value="" v-model="keyword">
                      <a style="position: absolute;right: 10px;top:9px;" class="search_btn"><img src="./assets/icon_search_nor@3x.png" width="14px"></a>
                  </div>

                  <mt-cell-swipe title="" :right="[
    {
      content: '删除',
      style: { background: 'red', color: '#fff' },
      handler: () => deletes(item)
    }
  ]" class="bill-panel" style="margin-bottom: 16px;" v-for="(item,index) in currentList">
                      <div class="fs-12-fc-030303" style="line-height: 16px;padding: 16px 0;width: 100%;text-align: left;" v-on:click="editProduct(item.id)">{{ item.work_no }},剩余库存<span style="color: #c50081;">{{item.quantity}}</span>卷,{{item.province}}</div>
                  </mt-cell-swipe>





              </div>

              <div style="height: 100px;"></div>

          </div>



          <div  v-if="layerSwitch" style="padding: 16px;margin-top: 60px;">
              <div>
                  <div class="cus-row cus-row-v-m">
                      <div class="cus-row-col-4">
                          <span class="fs-14-fc-000000-m">库存编辑</span>
                      </div>

                      <div class="cus-row-col-8 t-al-r">
                          <div class="in-bl v-a-m quantity-plus-icon" v-on:click="deQuantity"><img src="./assets/icon_out_nor@3x.png" class="quantity-plus-icon"/></div>
                          <div class="in-bl" style="margin: 0 10px;vertical-align: top"><input class="quantity-plus" v-model="quantity" style="display: inline-block;width: 30px;text-align: center;
    border: none;"/></div>
                          <div class="in-bl v-a-m quantity-plus-icon" v-on:click="addQuantity"><img src="./assets/icon_add_nor@3x.png" class="quantity-plus-icon"/></div>
                      </div>
                  </div>

                  <div style="margin:26px 0;border: 1px solid #e1e1e1;"></div>



                  <div>
                      <div class="fs-14-fc-000000-m" style="margin-bottom: 6px;">商品编码</div>
                      <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 100%;box-sizing: border-box;" class="fs-14-fc-93989e fn-fa" placeholder="输入商品编码" name="work_no" value="" v-model="productId">
                  </div>


                  <div style="margin-top: 20px">
                      <div class="fs-14-fc-000000-m" style="margin-bottom: 6px;">商品名称</div>
                      <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 100%;box-sizing: border-box;" class="fs-14-fc-93989e fn-fa" placeholder="输入商品名称" name="work_no" value="" v-model="productName">
                  </div>

                  <div style="margin-top: 20px">
                      <div class="fs-14-fc-000000-m" style="margin-bottom: 6px;">进价</div>
                      <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 100%;box-sizing: border-box;" class="fs-14-fc-93989e fn-fa" placeholder="进价" name="work_no" value="" v-model="income" type="number">
                  </div>


                  <div style="margin-top: 20px">
                      <div class="fs-14-fc-000000-m" style="margin-bottom: 6px;">售卖价</div>
                      <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;width: 100%;box-sizing: border-box;" class="fs-14-fc-93989e fn-fa" placeholder="售卖价" name="work_no" value="" v-model="outcome" type="number">
                  </div>

                  <div style="margin-top: 20px">
                      <div class="fs-14-fc-000000-m" style="margin-bottom: 6px;">单位（卷/米）</div>
                      <div class="cus-row" style="margin-top: 6px;">
                          <div class="cus-row-col-6 fs-14-fc-93989e">
                              <img src="./assets/checkbox-marked-circle.png" style="width: 24px;height: 24px;display: inline-block;vertical-align: middle;margin-right: 6px;" v-on:click="changeBit(1)" v-if="bit1Src"/>
                              <img src="./assets/checkbox-blank-circle-outline.png" style="width: 24px;height: 24px;display: inline-block;vertical-align: middle;margin-right: 6px;" v-on:click="changeBit(1)" v-else/>
                              卷</div>
                          <div class="cus-row-col-6 fs-14-fc-93989e">
                              <img src="./assets/checkbox-marked-circle.png" style="width: 24px;height: 24px;display: inline-block;vertical-align: middle;margin-right: 6px;"  v-on:click="changeBit(2)" v-if="bit2Src"/>
                              <img src="./assets/checkbox-blank-circle-outline.png" style="width: 24px;height: 24px;display: inline-block;vertical-align: middle;margin-right: 6px;"  v-on:click="changeBit(2)" v-else/>
                              米</div>
                      </div>
                  </div>

                  <div style="text-align: right;">

                      <div style="margin-top: 32px;width: 90px;display: inline-block;">
                          <a class="t-al-c" style="display: inline-block;width: 100%;background: #999999;border-radius: 100px;line-height: 44px;font-size: 17px;color: #FFFFFF;letter-spacing: 0;"  v-on:click="closeLayer">取消</a>
                      </div>

                      <div style="display: inline-block;padding: 0 16px;"></div>

                      <div style="margin-top: 32px;width: 90px;display: inline-block;">
                          <a class="t-al-c" style="display: inline-block;width: 100%;background: #E01885;box-shadow: 0 2px 8px 0 rgba(224,24,133,0.36);border-radius: 100px;line-height: 44px;font-size: 17px;color: #FFFFFF;letter-spacing: 0;" v-on:click="saveProduct">保存</a>
                      </div>
                  </div>
              </div>
          </div>

          <footer style="margin-bottom: 32px;position: fixed;bottom: 0;right: 0;left: 0;z-index:99;" class="t-al-c" v-if="!layerSwitch">
              <div class="btn4" v-on:click="addNew">新增商品</div>
          </footer>
      </div>
  </div>
</template>

<script>

import common from './assets/common'


export default {
  name: 'app',
    data:function(){return {
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
        beauty:false,
        income:'',
        outcome:'',
        bit:''
    }},
    created:function()
    {
        var _self = this;
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
            // $.get('/index/delete',{id:this.confirmId});
        },
        deQuantity:function () {
            if( this.quantity > 0)
            {
                this.quantity = parseInt(this.quantity) - 1;
            }
        },
        addQuantity:function(){
            this.quantity = parseInt(this.quantity) + 1;
        },
        saveProduct:function()
        {
            this.layerSwitch= false;
            this.$http.post(common.dynamicHost() + '/index/album-image',{id:this.id,quantity:this.quantity,productId:this.productId,productName:this.productName,income:this.income,outcome:this.outcome,bit:this.bit}).then((res)=>{
                this.initList();
            }).catch(err=>{

            });
            // $.post('/index/album-image',{id:this.id,quantity:this.quantity,productId:this.productId,productName:this.productName,income:this.income,outcome:this.outcome,bit:this.bit},function(data){
            //     if( data.status )
            //     {
            //         _self.initList();
            //     }else {
            //         mAlert(data.desc);
            //     }
            //
            // },'json');
        },
        closeLayer:function()
        {
            this.layerSwitch = false;
        },
        initList:function()
        {
            var _self = this;
            this.$http.get(common.dynamicHost() + '/index/list',{}).then((res)=>{
                var data = JSON.parse(res.data);
                this.list = data.data;
            }).catch(err=>{

            });
            // $.get('/index/list',{},function(data){
            //     _self.list = data.data;
            // },'json');

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
                    this.income = this.list[i].income;
                    this.outcome = this.list[i].outcome;
                    this.bit = this.list[i].bit;
                    this.layerSwitch = true;
                    return;
                }
            }
        },
        addNew:function()
        {
            this.layerSwitch = true;
            this.productId = '';
            this.productName = '';
            this.quantity = 0;
            this.income = 0;
            this.outcome = 0;
            this.bit = 1;
            this.id = '';
        },
        changeBit:function(ind)
        {
            this.bit = ind;
        },
        deletes  (obj){
            // alert(123);
            // console.log('delete');
            this.layerSwitch= false;
            this.$http.get(common.dynamicHost() + '/index/delete-item',{id:obj.id}).then((res)=>{
                this.initList();
            }).catch(err=>{

            });
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
        },
        bit1Src:function(){
            if( this.bit == 2)
            {
                return false;
            } else {
                return true;
            }
        },
        bit2Src:function(){
            if( this.bit == 2)
            {
                return true;
            } else {
                return false;
            }
        }
    }
}
</script>

<style>

    /*inline-block布局*/
    html,body{background-color: #ffffff;}
    .bg-color-2{background-color: rgb(239,243,246);}
    .in-bl{display: inline-block}
    .in-bl-line{font-size: 0}
    .in-bl-line-item{font-size: 14px;display: inline-block;}
    .small-a{color:#9c9c9c;font-size: 12px;}
    .small-a-black{color:#000000;font-size: 12px;}
    .small-a-plus{color:#9c9c9c;font-size: 14px;}

    .opr-h-wrap{padding: 80px 0;}
    .opr-h{font-size: 18px;
        font-weight:bold;text-align: center;}

    .fix-bottom{position: fixed;left:0;right: 0;bottom:0;}
    .input-row-type1{background-color: #ffffff !important;}

    a{color: #000000;cursor: pointer;}
    .fl-r{float: right;}
    .t-al-r{text-align: right;}
    .t-al-c{text-align: center;}
    .in-bl-fl-r{display: inline-block;float: right;margin-right: 30px;}

    .padding-passport{}
    .padding-container{padding: 10px;background-color: #ffffff;}
    .btn-block1{display: block;line-height: 40px;text-align: center;
        color: #ffffff;border-radius: 40px;background-color: #98CC3D;border: 1px solid #81BA1F;}

    .btn-block1:hover{color:#ffffff;}

    .remove-radius{border-radius: 0;}

    .btn-block2{background-color: #f5a623;display: block;line-height: 40px;text-align: center;
        color: #ffffff;}

    .btn1{display: inline-block;line-height: 40px;text-align: center;
        color: #ffffff;border-radius: 8px;background-color: rgb(0,164,247);padding: 0 12px;}

    .m-t-20{margin-top: 20px;}
    .m-t-10{margin-top: 10px;}
    .m-t-30{margin-top: 30px;}
    .m-b-20{margin-bottom: 20px;}
    .m-b-10{margin-bottom: 10px;}
    .m-b-10{margin-bottom: 10px;}
    .m-b-60{margin-bottom: 60px;}

    .p-l-r-14{padding-left: 14px;padding-right: 14px;}
    .p-all-14{padding: 16px;}
    header{line-height: 60px;}

    textarea.noscrollbars {
        overflow: hidden;
    }

    .list-mg-bottom{margin-bottom: 6px;}
    .list-mg-top{margin-top: 6px;}
    .card-item{background-color: #ffffff;padding: 15px;position: relative}

    .card-item.navigate:after{
        right: 15px;
        content: '\e583';
        font-family: Muiicons;
        font-size: inherit;
        line-height: 1;
        position: absolute;
        top: 50%;
        display: inline-block;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        text-decoration: none;
        color: #bbb;
        -webkit-font-smoothing: antialiased;
    }

    .x-de-50{position: absolute;top:0;left:50%;transform: translateX(-50%);-webkit-transform: translateX(-50%);}

    .ab-t-t-x-y{
        top:50%;
        left:50%;
        transform: translate(-50%,-50%);
        -webkit-transform: translate(-50%,-50%);
        position: absolute;
    }

    .fs-26-fc-black{font-size: 26px;color:#000000;line-height: 28px;}
    .fs-16-fc-212229{font-size: 16px;color:#212229;line-height: 18px;}
    .fs-17-fc-212229{font-size: 17px;color:#212229;line-height: 19px;}
    .fs-12-fc-909094{font-size: 12px;color:#909094;line-height: 14px;}
    .fs-14-fc-909094{font-size: 14px;color:#909094;line-height: 16px;}
    .fs-16-fc-909094{font-size: 16px;color:#909094;line-height: 18px;}
    .fs-14-fc-212229{font-size: 14px;color:#212229;line-height: 16px;}
    .fs-12-fc-212229{font-size: 12px;color:#212229;line-height: 14px;}
    .fs-14-fc-212229{font-size: 14px;color:#212229;line-height: 16px;}
    .fs-24-fc-212229{font-size: 24px;color:#212229;line-height: 26px;}
    .fs-14-fc-f89a03{font-size: 14px;color: #f89a03;line-height: 16px;}
    .fs-16-fc-f89a03{font-size: 16px;color: #f89a03;line-height: 18px;}

    .fs-12-fc-ffffff{font-size: 12px;color:#ffffff;line-height: 14px;}
    .fs-14-fc-ffffff{font-size: 14px;color:#ffffff;line-height: 16px;}
    .fs-16-fc-ffffff{font-size: 16px;color:#ffffff;line-height: 18px}
    .fs-36-fc-ffffff{font-size: 36px;color:#ffffff;line-height: 40px;}
    .fs-36-fc-212229{font-size: 36px;color:#212229;line-height: 40px;}
    .fs-12-fc-030303{font-size: 12px;color:#030303;line-height: 14px;}
    .fs-16-fc-030303{font-size: 16px;color:#030303;line-height: 18px;}
    .fs-14-fc-030303{font-size: 14px;color:#030303;line-height: 16px;}
    .fs-14-fc-f89a03{font-size: 14px;color:#f89a03;line-height: 16px;}
    .fs-14-fc-98CC3D{font-size: 14px;color:#98CC3D;line-height: 16px;}
    .fs-12-fc-a6a6a6{font-size: 12px;color:#a6a6a6;line-height: 14px;}

    .fs-14-fc-93989E{font-size: 14px;color: #93989E;line-height: 16px;}
    .fs-24-fc-232A31{font-size: 24px;color: #232A31;line-height: 26px;}
    .fs-14-fc-232A31{font-size: 14px;color: #232A31;line-height: 16px;}


    .in-bl-v-m{display: inline-block;vertical-align: middle;}
    .in-bl-v-t{display: inline-block;vertical-align: top;}



    .lms-link-1{color:#98CC3D;font-size: 16px;}


    .cus-row{font-size: 0;line-height: 0;}
    .cus-row-v-m > [class*='cus-row-col-']
    {
        vertical-align: middle;
    }

    .cus-row-v-t > [class*='cus-row-col-']
    {
        vertical-align: top;
    }

    .cus-row-v-b > [class*='cus-row-col-']
    {
        vertical-align: bottom;
    }

    .cus-row > [class*='cus-row-col-']
    {
        display: inline-block;
        font-size: 12px;
    }
    .cus-row-col-12
    {
        width: 100%;
    }
    .cus-row-col-11
    {
        width: 91.66666667%;
    }
    .cus-row-col-10
    {
        width: 83.33333333%;
    }
    .cus-row-col-9
    {
        width: 75%;
    }
    .cus-row-col-8
    {
        width: 66.66666667%;
    }
    .cus-row-col-7
    {
        width: 58.33333333%;
    }
    .cus-row-col-6
    {
        width: 50%;
    }
    .cus-row-col-5
    {
        width: 41.66666667%;
    }
    .cus-row-col-4
    {
        width: 33.33333333%;
    }
    .cus-row-col-3
    {
        width: 25%;
    }
    .cus-row-col-2
    {
        width: 16.66666667%;
    }
    .cus-row-col-1
    {
        width: 8.33333333%;
    }

    .back-icon{
        display: inline-block;
        width: 18px;
        height: 14.5px;
        background: url('/images/login_icon_back@2x.png');
        background-size: 18px 14.5px;
    }

    .next-icon{
        display: inline-block;
        width: 6px;
        height: 11px;
        background: url('/images/icon_next@2x.png');
        background-size: 6px 11px;
        margin-left: 6px;
    }

    .calendar-icon{

    }

    .user-icon{
        display: inline-block;
        width: 26px;
        height: 26px;
        background: url('/images/add_pic@3x.png');
        background-size: 26px 26px;
        vertical-align: middle;
    }

    .agree-icon{
        display: inline-block;
        width: 19px;
        height: 19px;
        background: url('/images/register  register  register_icon.png');
        background-size: 19px 19px;
        margin-right: 6px;
    }

    .attention-icon{
        display: inline-block;
        width: 19px;
        height: 19px;
        background: url('/images/icon_info@2x.png');
        background-size: 19px 19px;
        vertical-align: middle;
        margin-right: 6px;
    }

    .vip-icon{
        display: inline-block;
        width: 21px;
        height: 21px;
        background: url('/images/user_icon_vip.png');
        background-size: 21px 21px;
        margin-right: 6px;
    }

    .off-icon{
        display: inline-block;
        width: 53px;
        height: 36px;
        background: url('/images/icon_Off.png');
        background-size: 53px 36px;
    }

    .copy-icon{
        display: inline-block;
        width: 18px;
        height: 18px;
        background: url('/images/icon_copy.png');
        background-size: 18px 18px;
    }

    .on-icon{
        display: inline-block;
        width: 53px;
        height: 36px;
        background: url('/images/icon_On.png');
        background-size: 53px 36px;
    }

    .ques-icon{
        display: inline-block;
        width: 19px;
        height: 19px;
        background: url('/images/login_icon@2x.png');
        background-size: 19px 19px;
    }

    .close-icon{
        display: inline-block;
        width: 18px;
        height: 18px;
        background:url('/images/icon_cancel@3x.png');
        background-size: 18px 18px;
    }

    .wechat-icon{
        display: inline-block;
        width: 34px;
        height: 34px;
        background:url('/images/icon_wechat@2x.png');
        background-size: 34px 34px;
    }

    .alipay-icon{
        display: inline-block;
        width: 34px;
        height: 34px;
        background:url('/images/icon_alipay@2x.png');
        background-size: 34px 34px;
    }

    .lmspay-icon{
        display: inline-block;
        width: 34px;
        height: 34px;
        background:url('/images/icon_wechat copy@3x.png');
        background-size: 34px 34px;
    }



    .calendar-icon{
        display: inline-block;
        width: 21px;
        height: 21px;
        background:url('/images/icon_calendar@3x.png');
        background-size: 21px 21px;
    }




    .cus-label-1{background-color: #ffffff;padding: 14px;font-size: 14px;font-color:#212229;display: block;}

    .cus-input-1{border: none !important;outline: none !important;width: 100%;font-size: 14px;color:#212229;margin: 0 !important;padding: 0 !important;}

    .cus-input-row{line-height: 46px;font-size: 0;}
    .cus-input-row label{display: inline-block;width: 20%;}
    .cus-input-row input{display: inline-block;width: 80%;}

    .cus-row-bborder > [class*='cus-row-col-']
    {
        border-bottom: 1px solid #EBEAEA;
    }

    .vue-none{display: none;}

    .cus-info-panel{
        border-top:1px solid #EBEAEA;
        background-color: #ffffff;
        border-bottom:1px solid #EBEAEA;
    }

    .cus-info-panel-20{
        padding-left: 20px;
    }

    .cus-info-panel-20 .cus-info-panel-line{
        padding-right: 20px;
    }

    .cus-info-panel .inner-line{
        border-bottom:1px solid #EBEAEA;
    }

    input::-webkit-input-placeholder{color: #bebebe;  }

    .mui-active{background-color: #ffffff !important;}

    .btn3{background-image: linear-gradient(-137deg, #B9E77D 0%, #78CD09 50%);  box-shadow: 0 8px 16px 0 rgba(139,217,75,0.46);border-radius: 44px;line-height: 44px;font-size: 16px;color:#ffffff;font-weight: 800;text-align: center;}

    .btn3:hover{color:#ffffff;}

    .btn4{background: #E01885;box-shadow: 0 2px 8px 0 rgba(224,24,133,0.36);border-radius: 100px;font-family: PingFangSC-Regular;font-size: 17px; color: #FFFFFF; letter-spacing: 0;display: inline-block;line-height: 44px;padding: 0 26px;}

    .fw-m{font-weight: 600;}
    .vue-dpn{display: none;}

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}


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
    font-size: 14px;
    color: #212229;
}

.quantity-plus-icon
{
    width: 21px;
    height: 21px;
}

.bill-panel{background: #FFFFFF;
    box-shadow: 0 2px 6px 0 #E7E9F0;
    border-radius: 5px;}

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

.mint-cell-value{width: 100%;}
    .mint-cell{min-height: 12px !important;}
</style>
