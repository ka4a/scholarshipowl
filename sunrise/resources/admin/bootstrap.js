import Vue from 'vue';

window.axios = require('axios');
window.axios.defaults.headers.common['Accept'] = 'application/vnd.api+json';
window.axios.defaults.headers.common['Content-Type'] = 'application/vnd.api+json';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = '/';
window.axios.defaults.emulateJSON = true;

window.Vue = Vue;
Vue.config.debug = true;
Vue.config.devtools = true;

Vue.use(VueAxios, window.axios);

import generateAuth from './lib/auth';

const auth = generateAuth();

Object.defineProperty(Vue.prototype, '$auth', {
  get: () => auth,
});

import VeeValidate from 'vee-validate';
import Vuex from 'vuex';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import VueMoment from 'vue-moment';
import VueScrollTo from 'vue-scrollto';
import Buefy from 'buefy';

/*=============================
 * Quill setup
 * TODO: Move to VueJS plugin
 *============================*/
import VueQuillEditor, { Quill } from 'vue-quill-editor';
const Parchment = Quill.import('parchment')

const Inline = Quill.import('blots/inline');
class DynamicTagBlot extends Inline {
  static create() {
    const node = super.create();
    node.classList.add('ql-dynamic-tag');
    return node;
  }
  static formats(node) {
    return node.classList.contains('ql-dynamic-tag');
  }
}
DynamicTagBlot.blotName = 'dynamic-tag';
DynamicTagBlot.tagName = 'span'

Quill.register(DynamicTagBlot);

const sizeStyle = Quill.import('attributors/style/size');
sizeStyle.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '30px', '32px', '36px']
const fontStyle = Quill.import('attributors/style/font');
const alignStyle = Quill.import('attributors/style/align');

class IndentAttributor extends Parchment.Attributor.Style {
  add (node, value) {
    if (value === 0) {
      this.remove(node)
      return true
    } else {
      return super.add(node, `${value * 3}em`)
    }
  }
  value (node) {
    return parseInt(super.value(node))/3 || undefined;
  }
}

const indentStyle = new IndentAttributor('indent', 'padding-left', {
  scope: Parchment.Scope.BLOCK,
  whitelist: ['1em', '2em', '3em', '6em', '9em', '12em', '15em', '18em', '21em', '24em']
})

Quill.register(alignStyle, true);
Quill.register(indentStyle, true);
Quill.register(fontStyle, true);
Quill.register(sizeStyle, true);
Vue.use(VueQuillEditor);
/*=============================
 * Finish Quill setup
 *============================*/

import UserMixin from 'mixins/user';
import UtilsMixin from 'mixins/utils';

Vue.mixin(UserMixin);
Vue.mixin(UtilsMixin);

Vue.use(Vuex);
Vue.use(Buefy, {
  defaultIconPack: 'mdi',
});
Vue.use(VueRouter);
Vue.use(VueMoment);
Vue.use(VeeValidate);
Vue.use(VueScrollTo)

import ClickOutside from 'vue-click-outside';
Vue.directive('click-outside', ClickOutside);

import Field from 'components/common/field';
import Table from 'components/common/table/Table.vue';
import Breadcrumbs from 'components/breadcrumbs.vue';
import Icon from 'components/common/icon';

Vue.component('breadcrumbs', Breadcrumbs);
Vue.component('c-field', Field);
Vue.component('c-table', Table);
Vue.component('c-icon', Icon);
