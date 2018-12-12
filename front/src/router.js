import Vue from 'vue'
import Router from 'vue-router'
import entry from '@/components/entry.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VModal from 'vue-js-modal'

Vue.use(VModal)
Vue.use(VueAxios, axios)
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '*',
      name: 'entry',
      component: entry
    }
  ]
})
