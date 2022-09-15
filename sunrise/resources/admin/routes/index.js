import { USER_PERMISSION, GUEST_PERMISSION } from 'lib/acl';

import settings from './settings';
import oauth from './oauth';

const WinnerInformationLayout = () => import('components/layouts/winner-information.vue');
const WinnerInformationPage = () => import('pages/winner-information.vue');

const LoginLayout = () => import('components/layouts/login.vue');
const LoginPage = () => import('pages/login.vue');
const RegisterPage = () => import('pages/registration.vue');
const ResetPassword = () => import('pages/reset_password.vue');

const Layout = () => import('components/layouts/default.vue');
const Dashboard = () => import('pages/dashboard.vue');
const Profile = () => import('pages/profile.vue');
const NotFound = () => import('components/404.vue');

const OrgWinner = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/winner.vue');
const OrgWinners = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/winners.vue')
const OrgScholarships = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships.vue')
const OrgScholarshipTemplate = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/scholarship-template.vue')
const OrgScholarshipSettings = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/scholarship-settings.vue')
const OrgScholarshipPublished = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/scholarship-published.vue')
const OrgScholarshipPublishedInfo = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.published.info.vue')
const OrgScholarshipPublishedReview = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.published.review.vue')
const OrgScholarshipPublishedList = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.published.list.vue')
const OrgScholarshipPublishedWinners = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.published.winners.vue')

const OrgScholarshipSettingsAttributes = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.attributes.vue')
const OrgScholarshipSettingsDeadline = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.deadline.vue')
const OrgScholarshipSettingsDesign = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.design.vue')
const OrgScholarshipSettingsFields = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.fields.vue')
const OrgScholarshipSettingsRequirements = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.requirements.vue')
const OrgScholarshipSettingsLegal = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/components/scholarship.legal.vue')
const OrgScholarshipIntegrations = () => import(/* webpackChunkName: "group-org-scholarships" */ 'pages/organisation/scholarships/scholarship-integrations.vue')

const routes = [
  {
    path: '',
    component: Layout,
    children: [
      settings,

      {
        path: '',
        name: 'dashboard',
        redirect: { name: 'scholarships' },
        // component: Dashboard,
        meta: {
          title: "Dashboard",
          permission: USER_PERMISSION,
        }
      },

      {
        path: 'profile',
        name: 'profile',
        component: Profile,
        meta: {
          title: 'Profile',
          permission: USER_PERMISSION,
        }
      },

      {
        path: 'winners/:id',
        name: 'winner',
        component: OrgWinner,
        meta: {
          title: 'Winners',
          permission: USER_PERMISSION,
        }
      },

      /**
       * Organisation routes
       */
      {
        path: 'winners',
        name: 'winners',
        component: OrgWinners,
        meta: {
          title: 'Winners',
          permission: USER_PERMISSION,
        }
      },

      {
        path: 's',
        name: 'scholarships',
        component: OrgScholarships,
        meta: {
          title: 'Scholarships',
          permission: USER_PERMISSION,
        }
      },
      {
        path: 's/create',
        redirect: { name: 'scholarships.create' },
        component: OrgScholarshipSettings,
        meta: {
          title: 'New Scholarship',
          permission: USER_PERMISSION,
        },
        children: [
          {
            path: '',
            name: 'scholarships.create',
            component: OrgScholarshipSettingsAttributes,
            meta: {
              title: 'Scholarship View',
              permission: USER_PERMISSION
            },
          }
        ]
      },
      {
        path: 's/:id',
        component: { render: (c) => c('router-view') },
        children: [
          {
            path: '',
            name: 'scholarships.show',
            component: OrgScholarshipTemplate,
            meta: {
              title: 'Scholarship View',
              permission: USER_PERMISSION
            },
          },
          {
            path: 'integrations',
            name: 'scholarships.integrations',
            component: OrgScholarshipIntegrations,
            meta: {
              title: 'Scholarship Integrations',
              permission: USER_PERMISSION,
            }
          },
          {
            path: 'settings',
            name: 'scholarships.settings',
            redirect: { name: 'scholarships.settings.base' },
            component: OrgScholarshipSettings,
            meta: {
              title: 'Scholarship Settings',
              permission: USER_PERMISSION
            },
            children: [
              {
                path: '',
                name: 'scholarships.settings.base',
                component: OrgScholarshipSettingsAttributes,
                meta: {
                title: 'Scholarship Settings',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'deadline',
                name: 'scholarships.settings.deadline',
                component: OrgScholarshipSettingsDeadline,
                meta: {
                title: 'Scholarship Deadline',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'design',
                name: 'scholarships.settings.design',
                component: OrgScholarshipSettingsDesign,
                meta: {
                title: 'Scholarship Design',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'fields',
                name: 'scholarships.settings.fields',
                component: OrgScholarshipSettingsFields,
                meta: {
                title: 'Scholarship Fields',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'requirements',
                name: 'scholarships.settings.requirements',
                component: OrgScholarshipSettingsRequirements,
                meta: {
                  title: 'Scholarship Requirements',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'legal',
                name: 'scholarships.settings.legal',
                component: OrgScholarshipSettingsLegal,
                meta: {
                  title: 'Scholarship Legal',
                  permission: USER_PERMISSION,
                }
              },
            ]
          },
        ]
      },
      {
        path: 's/p/:id',
        component: { render: (c) => c('router-view') },
        children: [
          {
            path: '',
            component: OrgScholarshipPublished,
            redirect: { name: 'scholarships.published.show' },
            meta: {
              title: 'Scholarships Published View',
              permission: USER_PERMISSION,
            },
            children: [
              {
                path: '',
                name: 'scholarships.published.show',
                component: OrgScholarshipPublishedInfo,
                meta: {
                  title: 'Scholarships Published View',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'review',
                name: 'scholarships.published.review',
                component: OrgScholarshipPublishedReview,
                meta: {
                  title: 'Scholarships Published Review',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'list',
                name: 'scholarships.published.list',
                component: OrgScholarshipPublishedList,
                meta: {
                  title: 'Scholarships Published List',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'list/:application',
                name: 'scholarships.published.list.application',
                component: OrgScholarshipPublishedList,
                meta: {
                  title: 'Scholarships Published Review Application',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'winners',
                name: 'scholarships.published.winners',
                component: OrgScholarshipPublishedWinners,
                meta: {
                  title: 'Scholarships Published Winners',
                  permission: USER_PERMISSION,
                }
              },
              {
                path: 'review/:application',
                name: 'scholarships.published.review.application',
                component: OrgScholarshipPublishedReview,
                meta: {
                  title: 'Scholarships Published Review Application',
                  permission: USER_PERMISSION,
                }
              },
            ]
          }
        ]
      },
    ]
  },
  {
    path: '',
    component: LoginLayout,
    children: [
      {
        path: 'login',
        name: 'login',
        component: LoginPage,
        meta: {
          title: 'Login',
          permission: GUEST_PERMISSION,
        }
      }, {
        path: 'registration',
        name: 'registration',
        component: RegisterPage,
        meta: {
          title: 'Registration',
          permission: GUEST_PERMISSION,
        }
      }, {
        path: 'password/reset/:token',
        name: 'reset_password',
        component: ResetPassword,
        meta: {
          title: 'Reset Password',
          permission: GUEST_PERMISSION,
        }
      }
    ]
  },
  {
    path: '/winner-information/:id',
    component: WinnerInformationLayout,
    children: [{
      path: '/',
      name: 'winner-information',
      component: WinnerInformationPage,
      meta: {
        title: 'Winners',
        permission: GUEST_PERMISSION,
      }
    }]
  },
  oauth,
  {
    path: '*',
    name: '404',
    component: NotFound,
    meta: {
      title: "404",
    }
  }
];

export default routes
