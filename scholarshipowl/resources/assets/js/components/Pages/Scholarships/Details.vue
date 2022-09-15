<template lang="html">
  <section v-if="scholarship" :class="['scholarship-details', { 'freemium': isFreemium }]">
    <back-button class="scholarship-details__back" v-if="!(xl || xxl)"
      @click.native="$emit('global', { ev:'show-details', value: false })" />

    <banner-adapter v-if="!isSafari && isFreemium && bannerMode === 0" class="banner-top" />

    <div v-if="!requirementsCount && selectedTab !== SENT_SCHOLARSHIPS" class="scholarship-details__wrp">
      <notification class="scholarship-details__notif"
        name="no-requirements" :notification="noRequirementsNotification" />
    </div>

    <div class="scholarship-details__wrp scholarship-details__scholarship">
      <div :class="{'banner-middle-wrp': isFreemium}">
        <div>
          <h2 class="title">{{ scholarship.title }}</h2>
          <div class="scholarship-description" v-html="scholarship.description"></div>
          <a v-if="scholarship.externalUrl && !isFreemiumMVP" :href="scholarship.externalUrl"
            class="paragraph-link" target="_blank">Scholarship details</a>
        </div>
        <banner-details v-if="isFreemium" class="banner-middle" />
      </div>
      <h4 v-if="(selectedTab !== SENT_SCHOLARSHIPS && requirementsCount)
        || (selectedTab === SENT_SCHOLARSHIPS && applicationCount)" class="title-attachment">
        <span class="title-attachment__text">Requirements</span>
        <span class="title-attachment__delimeter"></span>
      </h4>
      <div v-if="requirementsCount"
        v-for="(requirements, requirementType) in scholarship.requirements" :key="requirementType">
        <div v-for="requirement in requirements" v-if="selectedTab !== SENT_SCHOLARSHIPS
          || (selectedTab === SENT_SCHOLARSHIPS && getApplication(requirement))" :key="requirement.id">
          <h4 class="requirement-title">{{ requirement.name.toLowerCase() === 'input' ? requirement.title : requirement.name }}<span v-if="requirement.isOptional" class="requirement-title__opt"> (optional):</span><span v-else>:</span>
          </h4>
          <h5 v-if="requirement.name.toLowerCase() === 'essay'" class="requirement-sub-title">{{ requirement.title }}</h5>
          <p class="paragraph scholarship-details__description" v-html="requirement.description"></p>
          <div v-if="isRequired(requirement) && attachmentType !== 'inputs'">
            <ul class="requirement-list">
              <li v-if="requirement.fileExtension"><span>File extension: </span>{{ requirement.fileExtension }}</li>
              <li v-if="requirement.maxFileSize"><span>Maximum file size: </span>{{ requirement.maxFileSize }}MB</li>
              <li v-if="requirement.minWords"><span>Minimum words: </span>{{ requirement.minWords }}</li>
              <li v-if="requirement.maxWords"><span>Maximum words: </span>{{ requirement.maxWords }}</li>
              <li v-if="requirement.minCharacters"><span>Minimum characters: </span>{{ requirement.minCharacters }}</li>
              <li v-if="requirement.maxCharacters"><span>Maximum characters: </span>{{ requirement.maxCharacters }}</li>
              <li v-if="requirement.minHeight"><span>Minimum height: </span>{{ requirement.minHeight }}</li>
              <li v-if="requirement.maxHeight"><span>Maximum height: </span>{{ requirement.maxHeight }}</li>
              <li v-if="requirement.minWidth"><span>Minimum width: </span>{{ requirement.minWidth }}</li>
              <li v-if="requirement.maxWidth"><span>Maximum width: </span>{{ requirement.maxWidth }}</li>
            </ul>
          </div>
          <requirement-sent v-if="selectedTab === SENT_SCHOLARSHIPS" :requirement="requirement" />
          <requirement @global="ev => $emit('global', ev)" v-else
            :key="requirementType + '-' + requirement.id" :requirement="requirement"
            :req-set-name="requirementType" />
        </div>
      </div>
    </div>
    <banner-adapter class="banner-bottom" v-if="!isSafari && isFreemium && bannerMode === 1" />
    <div v-if="selectedTab !== SENT_SCHOLARSHIPS" class="scholarship-details__wrp">
      <div class="block-sealer block-sealer_disable-wide">
        <apply @global="ev => $emit('global', ev)" :scholarship="scholarship" />
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters, mapState }  from "vuex";
import { SENT_SCHOLARSHIPS } from "store/scholarships";
import notifications from "components/Pages/Scholarships/Notifications/notifications";

import Requirement     from "./Requirement.vue";
import Apply           from "./Apply.vue";
import BannerAdapter   from "./BannerAdapter.vue";
import BannerDetails   from "banners/Freemium/Scholarships/Details.vue";
import RequirementSent from "components/Pages/Scholarships/Requirement/RequirementSent.vue";
import Notification    from "components/Pages/Scholarships/Notifications/Notification.vue";
import BackButton      from "components/Pages/Own/BackButton.vue"

//TODO remove it
import Upload from "components/Pages/Scholarships/Requirement/Upload.vue";

const countOfBanners = 2;

export default {
  components: {
    Requirement,
    Apply,
    BannerAdapter,
    BannerDetails,
    RequirementSent,
    Notification,
    BackButton,

    Upload
  },
  data() {
    return {
      SENT_SCHOLARSHIPS,
      noRequirementsNotification: notifications['no-requirements']
    }
  },
  methods: {
    isRequired(requirement) {
      return requirement["fileExtension"] || requirement["maxFileSize"] || requirement["minWords"] ||
             requirement["maxWords"] || requirement["minCharacters"] || requirement["maxCharacters"] ||
             requirement["minHeight"] || requirement["maxHeight"] || requirement["minWidth"] || requirement["maxWidth"];
    },
    getApplication(req) {
      return this.$store.getters["scholarships/getRequirementApplication"](req);
    }
  },
  computed: {
    isSafari () {
      return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    },
    requirementsCount() {
        return Object.values(this.scholarship.requirements).reduce((acc, req) => (acc + req.length), 0);
    },
    applicationCount() {
      const applications = {...this.scholarship.application};

      delete applications.externalStatusUpdatedAt;
      delete applications.submitedDate;
      delete applications.status;

      return Object.values(applications).reduce((acc, req) => (acc + req.length), 0);
    },
    bannerMode() {
      return this.scholarship.scholarshipId % countOfBanners;
    },
    ...mapGetters({
      scholarshipId: "scholarships/scholarshipId",
      isFreemium: "account/isFreemium",
      isFreemiumMVP: "account/isFreemiumMVP",
      xl: "screen/xl",
      xxl: "screen/xxl",
    }),
    ...mapState({
      scholarship: state => state.scholarships.selected,
      selectedTab: state => state.scholarships.selectedTab
    })
  },
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';
@import 'main/meta/palette';

$small-devices: 600px;

$blue: #708FE7;
$dark: #2f2f2f;
$very-light-blue: #e2eaff;
$black: #000;
$grey: #c2c2c2;

// meta classes
%paddings {
  padding-left: 25px;
  padding-right: 25px;
}

.block-sealer {
  padding-left: 17px;
  padding-right: 17px;

  @include breakpoint($s) {
    padding-left: 30px;
    padding-right: 30px;
  }

  @include breakpoint($m) {
    padding-left: 69px;
    padding-right: 69px;
  }

  @include breakpoint($l) {
    padding-right: 47px;
    padding-left: 47px;
  }

  @include breakpoint($xl) {
    padding-left: 61px;
    padding-right: 61px;
  }

  &_disable-wide {
    @include breakpoint($xl) {
      padding: 0;
    }
  }
}

.banner-middle-wrp {
  @include breakpoint($m) {
    @include flexbox();
  }
}

.banner-middle {
  width: 300px; height: 250px;
  display: block;

  @include breakpoint(max-width $m - 1px) {
    margin: 20px auto;
  }

  @include breakpoint($m) {
    margin-left: 25px;
  }

  @include breakpoint($l) {
    margin-left: 30px;
  }
}

.scholarship-details {
  max-width: 730px;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
  @include flex(1 1 auto);

  &__back {
    @include flex(0 0 auto);

    @include breakpoint(max-width $s - 1px) {
      padding-left: 10px;
    }
  }

  &__wrp {
    @include breakpoint(max-width $s - 1px) {
      padding-left: 10px;
      padding-right: 10px;
    }
  }

  &__notif {
    padding-bottom: 25px;
    border-bottom: 1px solid $grey;
    margin-bottom: 25px;

    @include breakpoint($m) {
      padding-bottom: 30px;
      margin-bottom: 30px;
    }
  }

  &__scholarship {
    @include breakpoint(max-width $l - 1px) {
      margin-top: 25px;
    }

     .banner-top + & {
      margin-top: 15px;

      @include breakpoint($s) {
        margin-top: 20px;
      }

      @include breakpoint($m) {
        margin-top: 28px;
      }

      @include breakpoint($l) {
        margin-top: 30px;
      }
    }
  }

  &__description {
    @extend %default-list-styles;
    @extend %style-formating-tags;
  }

  %sub-title {
    color: #2F2F2F;
    font-weight: bold;
  }

  %delimeter {
    width: 100%;
    height: 1px;
    border-bottom: 1px dashed #D8D8D8;
  }

  %title-delimeter {
    @extend %sub-title;
    text-align: center;
    background-color: #fff;
    margin: -11px auto 30px;
  }

  .requirement-wrp {
    margin-top: 20px;
  }

  .title {
    color: $blue;
    font-size: 19px;
    line-height: 1.35em;
    font-weight: 700;
    text-transform: capitalize;

    @include breakpoint($s) {
      font-size: 23px;
    }

    @include breakpoint($m) {
      font-size: 25px;
    }
  }

  .requirement-title {
    @extend %sub-title;
    line-height: 1.428em;
    font-size: 14px;
    margin-top: 25px;
    margin-bottom: 17px;

    @include breakpoint($s) {
      font-size: 16px;
      line-height: 1.25em;
      margin-top: 30px;
      margin-bottom: 19px;
    }

    &__opt {
      color: $nobel;
    }
  }

  .requirement-sub-title {
    line-height: 1.428em;
    font-size: 14px;
    color: $blue;
    text-transform: capitalize;
    margin-bottom: 20px;

    @include breakpoint($s) {
      font-size: 16px;
      line-height: 1.25em;
    }
  }

  .requirement-list {
    list-style-type: disc;
    margin-left: 1em;
    padding-left: 25px;
    font-size: 14px;
    line-height: 1.5em;
    margin-top: 13px;
  }

  .paragraph {
    color: $black;
    font-size: 14px;
    line-height: 1.428em;
  }

  .scholarship-description {
    margin-top: 17px;
    color: $black;
    font-size: 14px;
    line-height: 1.428em;

    @include breakpoint($l) {
      margin-top: 30px;
    }

    a {
      color: #708FE7;
      text-decoration: underline;
      cursor: pointer;
    }

    p {
      display: block;
      margin-top: 0.5em;
      margin-bottom: 0.5em;
      margin-left: 0;
      margin-right: 0;
    }

    b,
    strong {
      font-weight: bold;
    }

    s,
    strike {
      text-decoration: line-through;
    }

    em {
      font-style: italic;
    }

    ul {
      display: block;
      list-style-type: disc;
      margin-top: 0.5em;
      margin-bottom: 0.5em;
      margin-left: 0;
      margin-right: 0;
      padding-left: 40px;
    }

    ol {
      display: block;
      list-style-type: decimal;
      margin-top: 0.5em;
      margin-bottom: 0.5em;
      margin-left: 0;
      margin-right: 0;
      padding-left: 40px;
    }

    li {
      display: list-item;
    }
  }

  .paragraph-link {
    font-size: 14px;
    color: #551A8B;
    text-decoration: underline;
    display: block;
  }

  .title-attachment {
    position: relative;
    text-align: center;
    margin-top: 17px;
    margin-bottom: 20px;

    @include breakpoint($m) {
      margin-bottom: 30px;
    }

    @include breakpoint($l) {
      margin-top: 30px;
    }

    &__text {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      line-height: 1.375em;
      text-transform: capitalize;
      text-align: center;
      color: $dark;
      font-weight: 700;
      padding-left: 15px;
      padding-right: 15px;
      background-color: white;
      position: relative;
      z-index: 2;
    }

    &__delimeter {
      height: 2px;
      background-color: #F0F4FF;
      position: absolute;
      top: 50%; left: 0;
      width: 100%;
    }
  }

  %cover {
    border: 1px solid  #b9d5ff;
    border-radius: 4px;
    height: 52px;
  }
}
</style>

