<template>
  <div class="page scholarship-published" v-if="scholarship && scholarship.template">
    <breadcrumbs :breadcrumbs="breadcrumbs">
      <b-dropdown class="date-select" slot="right">
        <div class="button is-transparent" slot="trigger" @click="loadScholarshipList">
          <span>{{ scholarship.start | moment('DD.MM.YYYY') }} - {{ scholarship.deadline | moment('DD.MM.YYYY') }}</span>
          <b-icon icon="menu-down"></b-icon>
        </div>

        <b-dropdown-item v-if="scholarshipList" v-for="item in scholarshipList" :key="item.id" has-link active>
          <router-link :to="{ name: 'scholarships.published.show', params: { id: item.id }}">
            <span>{{ item.start | moment('DD.MM.YYYY') }} - {{ item.deadline | moment('DD.MM.YYYY') }}</span>
            <span v-if="item.id === scholarship.id">(this)</span>
            <span v-else-if="!item.expiredAt" class="has-text-success">(active)</span>
            <span v-else class="has-text-warning">(expired)</span>
          </router-link>
        </b-dropdown-item>

        <b-dropdown-item v-if="!scholarshipList" class="loading" disabled >
          <b-loading :active="true" :isFullPage="false" />
        </b-dropdown-item>
      </b-dropdown>
    </breadcrumbs>

    <div class="container">
      <section class="block is-fullheight">
        <div class="b-tabs is-fullwidth">
          <nav class="tabs">
            <ul>
              <li :class="{ 'is-active': ($route.name === 'scholarships.published.show' )}">
                <router-link :to="{ name: 'scholarships.published.show', params: { id: $route.params.id } }">
                  <span>INFO</span>
                </router-link>
              </li>
              <li :class="{ 'is-active': ($route.name === 'scholarships.published.review' ) }">
                <router-link :to="{ name: 'scholarships.published.review', params: { id: $route.params.id } }">
                  <span>REVIEW APPLICATIONS</span>
                </router-link>
              </li>
              <li :class="{ 'is-active': ($route.name === 'scholarships.published.list' ) }">
                <router-link :to="{ name: 'scholarships.published.list', params: { id: $route.params.id } }">
                  <span>APPLICATIONS LIST</span>
                </router-link>
              </li>
              <li :class="{ 'is-active': ($route.name === 'scholarships.published.winners' ), 'is-disabled': scholarship.expiredAt === null }">
                <router-link :to="{ name: 'scholarships.published.winners', params: { id: $route.params.id } }">
                  <span>WINNERS</span>
                </router-link>
              </li>
            </ul>
          </nav>
          <div class="tab-content">
            <router-view></router-view>
          </div>
        </div>
      </section>
    </div>

  </div>
</template>
<script>
import store from 'store';
import queryString from 'qs';
import { jsona } from 'lib/jsona';

const loadPublishedScholarship = (to, from, next) => {
  store.dispatch('organisation/scholarshipsPublishedPage/load', to.params.id)
    .then(next);
};

export default {
  name: 'ScholarshipPublished',
  data() {
    return {
      scholarshipList: null,
      openRules: false,
      openTos: false,
      openPp: false,
    };
  },
  beforeRouteEnter: loadPublishedScholarship,
  beforeRouteUpdate: loadPublishedScholarship,
  computed: {
    breadcrumbs({ scholarship }) {
      const breadcrumbs = {
        'Scholarships': { name: 'scholarships' },
        [scholarship.template.title]: {
           name: 'scholarships.show',
           params: { id: scholarship.template.id }
        },
      }

      if (this.$route.name === 'scholarships.published.show') {
        breadcrumbs['Published'] = {
           name: 'scholarships.published.show',
           params: { id: this.$route.params.id }
        }
      }


      if (this.$route.name === 'scholarships.published.review') {
        breadcrumbs['Review'] = {
           name: 'scholarships.published.review',
           params: { id: this.$route.params.id }
        }
      }


      if (this.$route.name === 'scholarships.published.list') {
        breadcrumbs['Application List'] = {
           name: 'scholarships.published.list',
           params: { id: this.$route.params.id }
        }
      }

      if (this.$route.name === 'scholarships.published.winners') {
        breadcrumbs['Winners'] = {
           name: 'scholarships.published.winners',
           params: { id: this.$route.params.id }
        }
      }

      return breadcrumbs;
    },
    scholarship({ $store }) {
      return $store.state.organisation.scholarshipsPublishedPage.item;
    },
    needReview() {
      return this.scholarship.requirements && this.scholarship.requirements.length;
    }
  },
  methods: {
    loadScholarshipList() {
      if (this.scholarshipList) {
        return;
      }

      const tid = this.scholarship.template.id;
      const qs = queryString.stringify({
        sort: '-deadline',
        fields: { scholarship: 'start,deadline,expiredAt' },
      }, { arrayFormat: 'index' });

      this.$http.get(`/api/scholarship_template/${tid}/scholarship?${qs}`)
        .then(rsp => {
          if (Array.isArray(rsp.data.data)) {
            this.scholarshipList = jsona.deserialize(rsp.data);
          }
        })
    }
  }
}
</script>
<style lang="scss">
.scholarship-published {
  .breadcrumb {
    overflow: visible;
    padding-right: 0;
    .date-select {
      .dropdown-trigger > .button {
        height: 100%;
      }
      .dropdown-menu {
        left: -50px;
      }
      .dropdown-item {
        &.loading {
          height: 100px;
        }
      }
    }
  }
  .b-tabs {
    display: flex;
    flex-direction: column;
    height: 100%;
    > .tab-content {
      flex: 1;
    }
  }
}
</style>
