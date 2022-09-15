<template>
  <div class="page winners-page">
    <breadcrumbs :breadcrumbs="{ 'Winners': { name: 'winners' }}" />
    <div class="container">

      <b-tabs v-if="scholarships && scholarships.length" v-model="activeTab">
        <b-tab-item label="WINNERS">
          <template v-if="!loading">
            <ul v-if="activeTab === 0">
              <li class="block scholarship-item"
                v-for="scholarship in scholarships"
                :class="{ 'is-closed': !opened[scholarship.id] }"
                :key="scholarship.id">
                <div class="base-info" @click="toggle(scholarship.id)">
                  <c-icon icon="arrow-down" />
                  <h4 class="title is-4">{{ scholarship.title }}</h4>
                </div>
                <winners-list
                  v-if="opened[scholarship.id]"
                  :scholarship-template-id="scholarship.id"
                />
              </li>
            </ul>
          </template>
        </b-tab-item>
        <b-tab-item label="DISQUALIFIED" :disabled="!scholarships.length">
          <ul v-if="activeTab === 1">
            <li class="block scholarship-item"
              v-for="scholarship in scholarships"
              :class="{ 'is-closed': !opened[scholarship.id] }"
              :key="scholarship.id">
              <div class="base-info" @click="toggle(scholarship.id)">
                <c-icon icon="arrow-down" />
                <h4 class="title is-4">{{ scholarship.title }}</h4>
              </div>
              <winners-list
                v-if="opened[scholarship.id]"
                :scholarship-template-id="scholarship.id"
                disqualified
              />
            </li>
          </ul>
        </b-tab-item>
      </b-tabs>
      <div v-else class="block is-fullheight empty-state">
        <empty-winners />
      </div>
      <b-loading :active="loading" :isFullPage="false" />
    </div>
  </div>
</template>
<script>
import Vue from 'vue';
import EmptyWinners from './winners/EmptyWinners';
import WinnersList from './winners/WinnersList';

const winnersOpenStoragItem = 'winners-page-winners-opened';

export default {
  name: 'WinnersListIndex',
  props: {
    store: String,
  },
  components: {
    EmptyWinners,
    WinnersList,
  },
  data() {
    const storageItem = localStorage.getItem(winnersOpenStoragItem);

    return {
      activeTab: 0,
      opened: storageItem ? JSON.parse(storageItem) : {},
    };
  },
  created() {
    if (!this.loaded) this.$store.dispatch('organisation/scholarships/load');
  },
  computed: {
    scholarships: ({ $store }) => $store.getters['organisation/scholarships/collection'],
    loading: ({ $store }) => $store.getters['organisation/scholarships/loading'],
    loaded: ({ $store }) => $store.getters['organisation/scholarships/loaded'],
  },
  methods: {
    toggle(id) {
      if (this.opened[id]) {
        Vue.delete(this.opened, id);
      } else {
        Vue.set(this.opened, id, true);
      }

      localStorage.setItem(winnersOpenStoragItem, JSON.stringify(this.opened));
    }
  },
}
</script>
<style lang="scss" scoped>
.winners-page {
  .container {
    overflow: hidden;
  }
  .scholarship-item {
    .base-info {
      cursor: pointer;
      height: 45px;
      display: flex;
      align-items: center;
      > .scholarship-id {
        font-size: 18px;
        margin-right: 30px;
      }
      > .icon {
        margin-right: 6px;
        transition: transform .5s ease-in-out;
      }
    }

    /deep/ .winners-list {
      padding: 20px 0;
    }

    &.is-closed {
      .base-info > .icon {
        transform: rotate(-90deg);
      }
    }
  }
}
</style>
