<template>
  <div class="scholarship-published-review">
    <b-loading :active="loading" :isFullPage="false" />
    <div class="toolbar">
      <span class="title">{{ total }} applications in total</span>
      <button class="button is-rounded is-primary is-outlined is-refresh" @click="store.dispatch('load')">
        <c-icon icon="refresh" />
        <span>Refresh</span>
      </button>
    </div>
    <div class="is-clearfix" />
    <div class="columns">
      <column-status class="column"
        ref="column-received"
        label="Received"
        @moved="onMoved"
        :applications="received">
      </column-status>
      <column-status class="column"
        ref="column-review"
        label="Under Review"
        @moved="onMoved"
        status-dot="review"
        :applications="review">
      </column-status>
      <column-status class="column"
        ref="column-accepted"
        label="Accepted"
        @moved="onMoved"
        status-dot="accepted"
        :applications="accepted">
      </column-status>
      <column-status class="column"
        ref="column-rejected"
        label="Rejected"
        @moved="onMoved"
        status-dot="rejected"
        :applications="rejected">
      </column-status>
    </div>
    <application-review
      v-if="$route.name === 'scholarships.published.review.application'"
      @close="store.dispatch('load')"
      :id="$route.params.application"
      :total="total"
      :current="current"
      :next="next"
      :prev="prev"
      :active="true" />
  </div>
</template>
<script>
import { Store } from 'vuex';
import { ItemStore, GridStore } from 'lib/store/factory';
import { JsonaModel } from 'lib/jsona';
import ColumnStatus from './column.status.vue';
import ApplicationReview from './application.review.vue';
import Draggable from 'vuedraggable';

export default {
  components: {
    ColumnStatus,
    ApplicationReview,
    Draggable
  },
  created() {
    this.store.dispatch('load');
  },
  data: function() {
    return {
      applicationReview: null,
      store: new Store(
        GridStore('application', {
          baseURL: () => `/api/scholarship/${this.$route.params.id}/`,
        })
      )
    }
  },
  methods: {
    onMoved({ application, to }) {
      let status;

      switch (to) {
        case this.$refs['column-received'].$el.childNodes[2]:
          status = 'received';
          break;
        case this.$refs['column-review'].$el.childNodes[2]:
          status = 'review';
          break;
        case this.$refs['column-rejected'].$el.childNodes[2]:
          status = 'rejected';
          break;
        case this.$refs['column-accepted'].$el.childNodes[2]:
          status = 'accepted';
          break;
        default:
          return;
      }

      if (application.status.id === status) {
        return;
      }

      const form = {
        data: {
          relationships: {
            status: {
              data: { id: status, type: 'application_status' }
            }
          }
        }
      }

      const oldStatus = application.status;
      application.status = JsonaModel.instance(status, 'application_status');

      return (new Store(ItemStore('application', { item: application })))
        .dispatch('save', { form })
        .catch((error) => {
          application.status = oldStatus;
          throw error;
        })
    }
  },
  computed: {
    loading: ({ store }) => store.state.loading,

    next: function({ waiting }) {
      if (this.current && this.waiting[this.current]) {
        return this.waiting[this.current].id;
      }
    },

    prev: function({ waiting }) {
      if (this.current && this.waiting[this.current-2]) {
        return this.waiting[this.current-2].id;
      }
    },

    current: function({ waiting }) {
      for (let i = 0; i < waiting.length; i++) {
        if (waiting[i].id === this.$route.params.application) {
          return i + 1;
        }
      }
    },

    waiting: ({ received, review }) => review.concat(received),
    total: ({ store }) => store.state.collection.length,

    received: ({ store }) => {
      return store.state.collection.filter(application => {
        return application.status && application.status.id === 'received';
      })
    },
    review: ({ store }) => {
      return store.state.collection.filter(application => {
        return application.status && application.status.id === 'review';
      })
    },
    rejected: ({ store }) => {
      return store.state.collection.filter(application => {
        return application.status && application.status.id === 'rejected';
      })
    },
    accepted: ({ store }) => {
      return store.state.collection.filter(application => {
        return application.status && application.status.id === 'accepted';
      })
    },
  },

}
</script>
<style lang="scss" scoped>
@import "~scss/variables.scss";
.scholarship-published-review {
  display: flex;
  flex-direction: column;
  height: 100%;

  > .tile.is-parent {
    padding: 0;
  }
  .toolbar {
    padding: 10px 0;
    display: flex;
    justify-content: space-between;
    .title {
      font-size: 20px;
      font-weight: bold;
      color: $black;
    }
  }
  /deep/ .column-status {
    display: flex;
    flex-direction: column;
  }
  > .columns {
    flex: 1;
  }
}
</style>
