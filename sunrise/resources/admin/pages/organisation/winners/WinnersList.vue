<template>
  <ul class="winners-list" :class="{ 'is-loading': loading }">
    <li v-for="winner in winners" class="columns is-desktop" :key="winner.id">
      <div class="photo">
        <router-link :to="{ name: 'winner', params: { id: winner.id}}">
          <winner-photo :photo="winner.photoSmall || winner.photo" size="80" />
        </router-link>
      </div>
      <div class="column winner-name">
        <p class="winner-name is-size-5">
          <router-link :to="{ name: 'winner', params: { id: winner.id}}">
            {{ winner.name }}
          </router-link>
        </p>
        <p class="winner-scholarship">
          <router-link :to="{ name: 'scholarships.published.show', params: { id: winner.scholarship.id }}">
            <span>Deadline: {{ winner.scholarship.deadline | moment('MM/DD/YYYY') }}</span>
            <span>({{ winner.scholarship.id }})</span>
          </router-link>
        </p>
        <p class="has-text-grey-light">Selected on: {{ winner.createdAt | moment('MM/DD/YYYY') }}</p>
      </div>
      <div class="column published">
        <p v-if="winner.meta.published" class="yes">
          <c-icon icon="done" class="mr-10" />
          <span>Published</span>
        </p>
        <p v-else class="no">
          <i class="circle mr-10" />
          <span>Not published</span>
        </p>
      </div>
      <div class="advanced-details">
        <strong class="mr-10">Advanced details:</strong>
        <span v-if="winner.meta.filled">Received</span>
        <span v-else>Pending</span>
      </div>
    </li>
    <empty-winners v-if="!loading && !winners.length" label="Waiting for next scholarship deadline!" />
    <b-loading :active="loading" :isFullPage="false" />
  </ul>
</template>
<script>
import Vuex from 'vuex';
import { GridStore } from 'lib/store/factory';
import WinnerPhoto from './winner-photo';
import EmptyWinners from './EmptyWinners';

export default {
  name: 'WinnersList',

  components: {
    WinnerPhoto,
    EmptyWinners,
  },

  props: {
    scholarshipTemplateId: {
      type: String,
      required: true,
    },
    disqualified: Boolean,
  },

  data() {
    const storeOptions = {
      include: ['scholarship'],
      basicQuery: {
        filter: {
           disqualifiedAt: { operator: this.disqualified ? 'neq':'eq', value: null },
           scholarship_template: this.scholarshipTemplateId
        },
        sort: '-createdAt',
      },
    };

    return {
      store: new Vuex.Store(GridStore('application_winner', storeOptions)),
    }
  },

  created() {
    this.store.dispatch('load');
  },

  computed: {
    winners: ({ store }) => store.getters['collection'],
    loading: ({ store }) => store.getters['loading'],
  },
}
</script>
<style lang="scss" scoped>
@import '../../../scss/variables.scss';

.winners-list {
  position: relative;
  &.is-loading {
    min-height: 50px;
  }
  > li {
    border-top: 1px solid #D7D7D7;
    padding: 12px 30px;

    .winner-scholarship a {
      color: #000000;
    }

    .advanced-details {
      > span {
        color: $grey-light;
      }
    }

    .published {
      > p {
        display: flex;
        align-items: center;
      }
      .no {
        color: #888888;
      }
      i.circle {
        width: 14px;
        height: 14px;
        border: 1.5px solid #888888;
        border-radius: 50%;
        margin: 5px 10px 5px 5px;
      }
    }

    > .column {
      padding: 0;
    }

    @include tablet {
      display: flex;
      align-items: center;
      justify-content: space-between;

      > .photo {
        margin-right: 15px;
        margin-left: 15px;
      }
      > .winner-name {
        // flex: 3;
      }
      > .published {
        // flex: 1;
        max-width: 150px;
      }
      > .advanced-details {
        margin-left: 5%;
        // flex: 2;
        // margin-left: auto;
      }
    }
  }
}
</style>
