<template>
  <div class="page scholarship-settings">
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div class="container">
      <div class="b-tabs is-fullwidth">
        <nav class="tabs">
          <ul>
            <li :class="{ 'is-active': (isAttributeSettings || isNewScholarship )}">
              <router-link v-if="isNewScholarship" :to="{ name: 'scholarships.create' }">
                <span>SCHOLARSHIP ATTRIBUTES</span>
              </router-link>
              <router-link v-else :to="{ name: 'scholarships.settings.base', params: { id: $route.params.id } }">
                <span>SCHOLARSHIP ATTRIBUTES</span>
              </router-link>
            </li>
            <li :class="{ 'is-active': isDeadlineSettings, 'is-disabled': isNewScholarship }">
              <router-link :to="{ name: 'scholarships.settings.deadline', params: { id: $route.params.id } }">
                <span>DEADLINE</span>
              </router-link>
            </li>
            <li :class="{ 'is-active': isFieldsSettings, 'is-disabled': isNewScholarship }">
              <router-link :to="{ name: 'scholarships.settings.fields', params: { id: $route.params.id } }">
                <span>FIELDS</span>
              </router-link>
            </li>
            <li :class="{ 'is-active': isRequirementsSettings, 'is-disabled': isNewScholarship }">
              <router-link :to="{ name: 'scholarships.settings.requirements', params: { id: $route.params.id } }">
                <span>REQUIREMENTS</span>
              </router-link>
            </li>
            <li :class="{ 'is-active': isDesignSettings, 'is-disabled': isNewScholarship }">
              <router-link :to="{ name: 'scholarships.settings.design', params: { id: $route.params.id } }">
                <span>WEB PAGE</span>
              </router-link>
            </li>
            <li :class="{ 'is-active': isLegalSettings, 'is-disabled': isNewScholarship }">
              <router-link :to="{ name: 'scholarships.settings.legal', params: { id: $route.params.id } }">
                <span>LEGAL</span>
              </router-link>
            </li>
            <!-- <li :class="{ 'is-active': isIntegrationSettings, 'is-disabled': isNewScholarship }">
              <router-link :to="{ name: 'scholarships.settings.integrations', params: { id: $route.params.id } }">
                <span>INTEGRATIONS</span>
              </router-link>
            </li> -->
          </ul>
        </nav>
        <div class="tab-content">
          <div class="columns tab-item">
            <div class="column block is-9 is-fullheight">
              <router-view></router-view>
            </div>
            <aside class="column sidebar ml-20">
              <div v-if="scholarship.id" class="block">

                <template v-if="previewWebsite" >
                  <div class="action" @click.prevent="openPreview">
                    <i class="icon"><icon-view /></i>
                    <span>Preview</span>
                  </div>
                  <form-preview
                    ref="preview"
                    class="mb-10"
                    :scholarship="scholarship"
                  />
                </template>

                <div class="action action-delete">
                  <i class="icon" @click="isConfirmDeleteOpen = true"><icon-trash /></i>
                  <span v-if="!isConfirmDeleteOpen"  @click="isConfirmDeleteOpen = true">Delete</span>
                  <div v-else>
                    <p>This scholarship item will be permanently deleted</p>
                    <a class="ms-10" href="#" @click.prevent="removeScholarship()">
                      Yes
                    </a>
                    <a class="ms-10" href="#" @click.prevent="isConfirmDeleteOpen = false">
                      Cancel
                    </a>
                  </div>
                </div>

                <template v-if="scholarship.published">
                  <div class="action action-republish" @click="republishScholarship">
                    <b-icon icon="publish" />
                    <span>Republish</span>
                  </div>
                </template>

                <!-- <b-modal :active="false" ref="confirmDeleteModal">
                  <div class="modal-card is-confirm">
                    <section class="modal-card-body">
                      <h3 class="title is-3">Are you sure want to delete "{{ scholarship.title }}" scholarship?</h3>
                    </section>
                    <footer class="modal-card-foot">
                      <button class="button is-success is-pulled-right" @click="removeScholarship()">Confirm</button>
                      <button class="button is-danger is-pulled-right" @click="$refs.confirmDeleteModal.close()">Cancel</button>
                    </footer>
                  </div>
                </b-modal> -->

              </div>

              <div class="block helper" v-if="isNewScholarship || isAttributeSettings">
                <h4 class="title has-barline">
                  <icon-helper class="icon" />
                  <span>Helper</span>
                </h4>
                <p>If you wish to provide a $200 scholarship once a month for half a year, please indicate as follows:</p>
                <br/>
                <p>Award amount: $200</p>
                <p>Awards: 6</p>
                <p>Start date: 01/01/2019</p>
                <p>Expiration date: 06/30/2019</p>
                <p>Award frequency type: Monthly</p>
                <p>Award frequency value: 6</p>
              </div>

              <div class="block helper" v-else-if="isFieldsSettings">
                <h4 class="title has-barline">
                  <icon-helper class="icon" />
                  <span>Helper</span>
                </h4>
                <p>You may add conditions to specific fields if the scholarship has eligibility rules.</p>
                </br>
                <p>Example:</p>
                <p>If scholarship is suitable only for students 16 years old, please enable "Date Of Birth" field and add the following condition:</p>
                <p><span class="is-italic	">Applicant's age "greater or is" 16</span></p>
              </div>

              <div class="block helper" v-else-if="isDesignSettings">
                <h4 class="title has-barline">
                  <icon-helper class="icon" />
                  <span>Helper</span>
                </h4>
                <p>Here you can choose the template for your landing page, and it's color variant. Select a desirable content sections, and generate a landing page with a form to receive an applications from people.</p>
              </div>

              <section v-if="scholarship.website" class="block">
                <div class="tile"><icon-url class="icon" />URL link</div>
                <div class="tile mt-10">
                  <a class="is-small" :href="scholarship.website.meta.url" target="_blank">
                    {{ scholarship.website.meta.url }}
                  </a>
                </div>
              </section>

            </aside>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import IconUrl from 'icon/url.vue';
import IconView from 'icon/view.vue';
import IconDelete from 'icon/delete.vue';
import IconTrash from 'icon/trash.vue';
import IconHelper from 'icon/helper.vue';
import FormPreview from './components/scholarship.preview.form.vue';

import store from 'store';

const befoureRouteHandler = (to, from, next) => {
  if (to.name === 'scholarships.create') {
    store.dispatch('organisation/scholarshipSettings/reset');
  }

  if (to.params.id) {
    return store.dispatch('organisation/scholarshipSettings/load', to.params.id)
      .then(() => {
        next();
      });
  }

  return next();
};

export default {
  components: {
    IconDelete,
    IconTrash,
    IconHelper,
    IconView,
    IconUrl,
    FormPreview
  },
  beforeRouteEnter: befoureRouteHandler,
  beforeRouteUpdate: befoureRouteHandler,
  data: function() {
    return {
      isConfirmDeleteOpen: false,
    };
  },
  methods: {
    openPreview() {
      this.$refs['preview'].open();
    },
    removeScholarship() {
      this.$store.dispatch('organisation/scholarshipSettings/delete', this.scholarship).then(() => {
        this.$store.dispatch('organisation/scholarships/load');
        this.$router.push({ name: 'scholarships' });
      })
    },
    republishScholarship() {
      this.$dialog.confirm({
        title: `Re-publish scholarship "${this.scholarship.title}"`,
        message: 'Re-publishing will update current published instance with new settings.',
        confirmText: 'Re-publish',
        type: 'is-warning',
        onConfirm: () => {
          this.$store.dispatch('organisation/scholarshipSettings/republish')
            .then(() => this.$toast.open({ type: 'is-success', message: 'Scholarship republished' }))
        }
      });
    }
  },
  computed: {

    scholarship: ({ $store }) => $store.state.organisation.scholarshipSettings.item,

    isNewScholarship: ({ $route }) =>
      $route.name === 'scholarships.create',

    isAttributeSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.base',

    isDeadlineSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.deadline',

    isFieldsSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.fields',

    isRequirementsSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.requirements',

    isDesignSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.design',

    isLegalSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.legal',

    isIntegrationSettings: ({ $route }) =>
      $route.name === 'scholarships.settings.integrations',

    previewWebsite: ({ isDesignSettings, $store }) => {
      return false;
      // const website = $store.state.organisation.scholarshipSettings.website.item;
      // return isDesignSettings && website && website.layout && website.variant;
    },

    breadcrumbs: ({
      scholarship,
      isDesignSettings,
      isDeadlineSettings,
      isFieldsSettings,
      isRequirementsSettings,
      isAttributeSettings,
      isLegalSettings,
      isNewScholarship
    }) => {
      let breadcrumbs = {
        'Scholarships': {
          name: 'scholarships'
        },
      };

      if (scholarship.id) {
        breadcrumbs[scholarship.title] = {
          name: 'scholarships.show',
          params: { id: scholarship.id }
        }
        if (isAttributeSettings) {
          breadcrumbs['Settings'] = {
            name: 'scholarships.settings.base',
            params: { id: scholarship.id }
          }
        }
        if (isDeadlineSettings) {
          breadcrumbs['Settings'] = {
            name: 'scholarships.settings.deadline',
            params: { id: scholarship.id }
          }
        }
        if (isFieldsSettings) {
          breadcrumbs['Fields'] = {
            name: 'scholarships.settings.fields',
            params: { id: scholarship.id }
          }
        }
        if (isRequirementsSettings) {
          breadcrumbs['Requirements'] = {
            name: 'scholarships.settings.requirements',
            params: { id: scholarship.id }
          }
        }
        if (isDesignSettings) {
          breadcrumbs['Design'] = {
            name: 'scholarships.settings.design',
            params: { id: scholarship.id }
          }
        }
        if (isLegalSettings) {
          breadcrumbs['Legal'] = {
            name: 'scholarships.settings.legal',
            params: { id: scholarship.id }
          }
        }
      } else {
        breadcrumbs['Scholarship new'] = {
          name: 'scholarships.create'
        };
      }

      return breadcrumbs;
    }
  },
}
</script>
<style lang="scss">
@import "../../../scss/variables.scss";

.scholarship-settings {
  .helper {
    color: $grey;

    .title {
      display: flex;
      margin-bottom: 10px;
      line-height: 16px;
      font-weight: 300;
      font-size: 16px;
      .icon {
        margin-right: 16px;
        width: auto;
        height: 100%;
      }
    }
  }
  .modal-card.is-confirm {
    .modal-card-foot {
      justify-content: flex-end;
    }
  }
  .b-tabs {
    > .tab-content {
      & > .columns {
        margin: 0;
      }
    }
  }
  .sidebar {
    padding: 0;

    .icon {
      margin-right: 10px;
    }
    .action {
      display: flex;
      .icon {
        cursor: pointer;
      }
      > span {
        cursor: pointer;
      }
      a {
        color: #121212;
      }
      &.action-delete {
        a:hover {
          color: $primary;
          text-decoration: underline;
        }
      }
      &:not(:last-child) {
        margin-bottom: 16px;
      }
    }
  }
}
</style>
