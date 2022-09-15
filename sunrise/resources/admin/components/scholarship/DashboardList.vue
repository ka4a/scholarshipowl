<template>
  <div class="grid-scholarship-templates">
    <b-loading :active="loading" :is-full-page="false" />
    <ul class="scholarships-list">
      <li v-for="scholarship in scholarships">
        <div class="tile scholarships-list_item" :class="{ 'is-not-published': !scholarship.published }">
          <div class="tile is-title">
            <router-link
              class="title is-5"
              :to="{ name: 'scholarships.settings', params: { id: scholarship.id }}"
            >
              {{ scholarship.title }}
            </router-link>
            <router-link
              v-if="scholarship.published"
              class="applications-stats"
              :to="{ name: 'scholarships.published.show', params: { id: scholarship.published.id }}"
            >
              <i class="applications-stats_state-icon" :class="{ 'is-empty': !scholarship.published.stats.new }" />
              <span>{{ scholarship.published.stats.new }} new applications</span>
              <span>| {{ scholarship.published.stats.total }} in total</span>
            </router-link>
          </div>
          <template v-if="scholarship.published">
            <span class="tile dates">
              <c-icon icon="clock-circular-outline" />
              <span>{{ scholarship.published.start | moment('MM/DD/YYYY') }}</span>
              <span>&nbsp;-&nbsp;</span>
              <span>{{ scholarship.published.deadline | moment('MM/DD/YYYY') }}</span>
            </span>
            <a v-if="scholarship.website" class="tile url" :href="scholarship.website.meta.url" target="_blank">
              <c-icon icon="link" />
              <span>{{ scholarship.website.meta.url }}</span>
            </a>
            <span class="tile status has-text-success">
              <c-icon icon="published" />
              <span>Published</span>
            </span>
          </template>
          <template v-else>
            <router-link class="tile notice" :to="{ name: 'scholarships.settings', params: { id: scholarship.id }}">
              <u>Finish setting up scholarship to publish it</u>
            </router-link>
            <div class="tile status">
              <span>Not published</span>
            </div>
          </template>
          <div class="tile integrations">
            <router-link :to="{ name: 'scholarships.integrations', params: { id: scholarship.id } }">
              <figure class="integrations--image">
                <img :src="require('assets/img/integration/iframe.svg')" />
              </figure>
            </router-link>
          </div>
        </div>
      </li>
    </ul>
    <b-pagination
      v-if="pagination.total_pages > 1"
      order="is-centered"
      :total="pagination.total"
      :current="pagination.current_page"
      :per-page="pagination.per_page"
      @change="store.dispatch('page', { number: $event })"
    />
  </div>
</template>
<script>
import Grid from 'components/grid.vue';
import { createGridStore } from 'lib/store/factory';

const createScholarshipsGridStore = ($store) => {
  return createGridStore('scholarships', {
    baseURL: () => `/api/organisation/${$store.getters['user/workingOrganisation']}/`,
    include: ['website', 'published', 'published.stats'],
    sorting: [{
      field: 'published',
      direction: 'desc',
    }, {
      field: 'createdAt',
      direction: 'desc',
    }],
  })
}

export default {
  name: 'ScholarshipList',
  components: {
    Grid
  },
  created() {
    /**
     * Refresh the page after working organisation changed.
     */
    this.store.dispatch('page', { size: 6 });
    this.$store.subscribe(({ type }, state) => {
      if (type === 'user/setWorkingOrganisation') {
        this.store = createScholarshipsGridStore(this.$store);
        this.store.dispatch('page', { size: 6 });
      }
    });
  },
  data: function() {
    return {
      store: createScholarshipsGridStore(this.$store),
    }
  },
  computed: {
    scholarships: ({ store }) => store.getters['collection'],
    pagination: ({ store }) => store.getters['pagination'],
    loading: ({ store }) => store.getters['loading'],
  }
}
</script>
<style lang="scss">
@import '~scss/variables.scss';
.grid-scholarship-templates {
  display: flex;
  flex-direction: column;
  height: 100%;
  /deep/ .pagination {
    margin-top: auto;
  }
}
.scholarships-list {
  > li {
    &:not(:first-child) {
      margin-top: 20px;
    }
  }
  &_item {
    justify-content: space-between;
    background-color: #FFFFFF;
    box-shadow: 0px 7px 25px rgba(36, 60, 97, 0.08);
    border-radius: 5px;
    padding: 17px 27px;

    .tile {
      align-items: center;
      &.is-title {
        flex: 2;
        flex-direction: column;
        align-items: baseline;
        .title {
          margin-bottom: 5px;
        }
        a {
          color: $body-color;
          &:hover {
            color: $primary;
          }
        }
      }
      &.dates {
        flex: 1;
        padding: 0 10px;
        color: $body-color;
      }
      &.url {
        flex: 1;
        padding: 0 10px;
        color: $body-color;
      }
      &.status {
        flex: 1;
        max-width: 120px;
        color: $body-color;
      }
      &.integrations {
        cursor: pointer;
        justify-content: flex-end;
      }
    }

    .applications-stats {
      &_state-icon {
        display: inline-block;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: $primary;
        &.is-empty {
          background-color: $grey-lighter;
        }
      }
    }

    &.is-not-published {
      color: $grey-lighter;
      border: 2px dashed $grey-lighter;
      background: none;
      .tile {
        &.is-title {
          .title {
            color: $grey-lighter;
          }
        }
        &.status {
          color: $grey-lighter;
        }
        &.notice {
          flex: 2;
        }
      }
    }
    &:hover {
      background: rgba(255, 255, 255, 0.6);
      .title {
        color: $primary;
      }
    }
  }
}
</style>
