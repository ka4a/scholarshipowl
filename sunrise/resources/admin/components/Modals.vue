<template>
  <div class="modals">

    <b-modal
       class="organisation-setup"
      :active="active === 'organisationSetup'"
      :canCancel="['escape', 'outside']"
       @close="close('organisationSetup')">
      <div class="box" :style="{ backgroundImage: 'url(' + require('./modals/OrganisationSetupBg.svg') + ')' }">
        <i class="boxclose" @click="close('organisationSetup')" />
        <organisation-setup />
      </div>
    </b-modal>

  </div>
</template>
<script>
import OrganisationSetup from './modals/OrganisationSetup';

export default {
  name: 'ApplicationModals',
  components: {
    OrganisationSetup,
  },
  computed: {
    active: ({ $store }) => $store.getters['modals/active'],
  },
  methods: {
    close(modal) {
      this.$store.dispatch('modals/close', modal);
    },
  },
}
</script>
<style lang="scss">
@import '../scss/variables';
.modals {
  .modal {

    &.organisation-setup {
      .modal-content {
        width: auto;

        > .box {
          padding: 30px 70px;
          background-position: right -20px bottom -20px;
          background-repeat: no-repeat;
        }
      }
    }

  }
}
</style>
