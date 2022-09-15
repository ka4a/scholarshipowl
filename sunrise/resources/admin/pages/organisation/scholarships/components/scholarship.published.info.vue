<template>
  <div class="scholarship-published-info">
    <b-loading :active="$store.state.organisation.scholarshipsPublishedPage.loading" :isFullPage="false" />

    <div class="card">
      <div class="card-header">
        <div class="card-header-title">Base information</div>
        <div class="card-header-icon" @click="cardOpen = !cardOpen">
          <b-icon :icon="!cardOpen ? 'menu-down' : 'menu-up'" />
        </div>
      </div>
      <div class="card-content" v-if="cardOpen">
        <base-information :scholarship="scholarship" />
      </div>
    </div>

  </div>
</template>
<script>
import BaseInformation from 'components/scholarship/published/BaseInformation';

export default {
  components: {
    BaseInformation
  },
  data() {
    return {
      cardOpen: true,
    }
  },
  computed: {
    scholarship: ({ $store }) => $store.state.organisation.scholarshipsPublishedPage.item,
    templates: ({ $store }) => $store.state.templates.list,
    website: ({ scholarship }) => scholarship.website,
    stats: ({ scholarship }) => scholarship.stats,
  }
}
</script>
<style lang="scss">
@import "../../../../scss/variables.scss";
.scholarship-published-info {
  .columns.is-full-width {
    width: 100%;
  }
  .image.is-138x132 {
    position: relative;
    cursor: pointer;
  }
  .box-flat {
    background-color: #FAFBFC;
    padding: 30px 30px 20px;
    .deadline-info {
      color: #979797;
      font-size: 14px;
    }
    .has-text-grey {
      color: #595959;
    }
    .box-footer {
      border-top: 1px solid #D0D0D0;
      .is-link {
        text-decoration: underline;
      }
    }
    .applications-number {
      > strong {
        color: $primary;
        font-size: 72px;
        white-space: nowrap;
      }
    }
    .title {
      font-family: $family-sans;
    }
    &:not(:last-child) {
      // margin-bottom: 20px;
      margin-right: 40px !important;
    }
  }
}
</style>
