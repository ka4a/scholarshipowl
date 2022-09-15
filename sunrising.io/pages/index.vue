<template>
  <div>
    <sun-toolbar
      @openTerms="onTermsOpen"
    />
    <sun-header />
    <sun-needs />
    <sun-video />
    <sun-benefits />
    <sun-partnerships />
    <sun-founders />
    <sun-footer
      @openTerms="onTermsOpen"
    />

    <terms-popup
      :visible="isTermsOpen"
      @close="onTermsClose"
    />
  </div>
</template>

<script>
import {
  SunHeader, SunNeeds, SunVideo, SunBenefits, SunPartnerships, SunFounders, SunFooter, SunToolbar,
  TermsPopup,
} from '../components';

export default {
  name: 'Homepage',

  components: {
    SunToolbar,
    SunHeader,
    SunNeeds,
    SunVideo,
    SunBenefits,
    SunPartnerships,
    SunFounders,
    SunFooter,
    TermsPopup,
  },

  data() {
    return {
      isTermsOpen: false,
    };
  },

  methods: {
    onTermsOpen() {
      // Disable scroll when popup active
      document.getElementsByTagName('html')[0].style.overflow = 'hidden';
      this.isTermsOpen = true;
    },

    onTermsClose() {
      // Enable scroll when popup closed
      document.getElementsByTagName('html')[0].style.overflow = 'auto';
      this.isTermsOpen = false;
    },
  },
};
</script>
