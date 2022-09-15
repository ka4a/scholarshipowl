import { ROOT_PERMISSION } from 'lib/acl';
import LegalContent from 'pages/settings/legal.vue';
import LegalContentEdit from 'pages/settings/legal.edit.vue';

export default {
  path: 'settings',
  name: 'settings',
  component: { render: (c) => c('router-view') },
  children: [
    {
      path: 'legal',
      name: 'settings.legal',
      component: LegalContent,
      meta: {
        title: "Settings - Legal",
        permission: ROOT_PERMISSION,
      }
    },
    {
      path: 'legal/:id',
      name: 'settings.legal.edit',
      component: LegalContentEdit,
      meta: {
        title: "Settings - Legal Edit",
        permission: ROOT_PERMISSION,
      }
    }
  ]
}
