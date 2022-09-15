import Layout from 'components/layouts/oauth.vue';
import Authorize from '../pages/authorize.vue';
import { USER_PERMISSION, GUEST_PERMISSION } from 'lib/acl';

export default {
  path: '/oauth',
  component: Layout,
  children: [
    {
      name: 'oauth.authorize',
      path: 'authorize',
      component: Authorize,
      meta: {
        title: "Authentication",
      }
    }
  ]
}
