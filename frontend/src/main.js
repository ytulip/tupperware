import Vue from 'vue'
import App from './App.vue'
import Fly from 'flyio/dist/npm/fly'
import Mint from 'mint-ui'
import 'mint-ui/lib/style.css'

var fly = new Fly
Vue.config.productionTip = false
Vue.use(Mint)
Vue.prototype.$http = fly
new Vue({
  render: h => h(App),
}).$mount('#app')
