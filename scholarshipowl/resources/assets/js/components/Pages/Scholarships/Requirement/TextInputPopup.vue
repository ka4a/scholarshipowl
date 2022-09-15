<template lang="html">
  <div ref="tih" :class="[{'tih-description-open': descriptionOpen}, 'tih']">
    <section class="tih__frame">
      <div class="tih__wrp">
        <h2 class="tih__title">{{ scholarship.title }}</h2>
        <text-description @click.native="handleClick" class="tih__description-text"
          type="html"
          :text="requirement.description" clamp="Show more"
          :length="descriptionLength" collapsed-text-class="collapsed" />
        <p v-if="words && xs || s" class="tih__text-requirememnt"><strong class="tih__bold">words: </strong>{{ words }}</p>
        <textarea @focus="textareaInView" @input="inputHolder" v-model="inputText" class="tih__textarea" cols="30" rows="10"></textarea>
        <div class="tih__statistic">
          <p v-if="words && !(xs || s)" class="tih__text-requirememnt"><strong class="tih__bold">words: </strong>{{ words }}</p>
          <p>
            <span>Word count: <span>{{ wordsCount }}</span></span>
            <span class="tih__character-count">Character count: <span>{{ charactersCount }}</span></span>
          </p>
        </div>
        <button v-if="saving || isClosing" class="tih__btn">saving...</button>
        <button v-else @click="isClosing = true; saveAndClose()" class="tih__btn">save</button>
      </div>
    </section>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { debounce } from "lib/utils/utils";
import textDescription from "vue-truncate-collapsed";

export default {
  name: "RequirementTextInputPopup",
  props: {
    requirement:      { type: Object,   required: true },
    saving:           { type: Boolean,  required: true },
    applyRequirement: { type: Function, required: true }
  },
  components: {
    textDescription
  },
  mounted() {
    this.initialHeight = this.$refs.tih.clientHeight;

    this.inputText = this.sentText;
  },
  data: function() {
    return {
      initialBlockScroll: null,
      timeout: null,
      descriptionOpen: false,
      isClosing: false,
      inputText: "",
    };
  },
  computed: {
    words() {
      const requirement = this.requirement;
      let requirementWords = "";

      if (requirement.minWords > 0) {
        if (requirement.maxWords > 0) {
          requirementWords += `${requirement.minWords} - ${requirement.maxWords} words`;
        } else {
          if (requirement.minWords === 1) {
            requirementWords += "Any number of characters.";
          } else {
            requirementWords += `Minimum ${requirement.minWords} words`;
          }
        }
      } else if (requirement.maxWords > 0) {
        requirementWords += `Up to ${requirement.maxWords} words`;
      } else {
        if (requirement.minCharacters > 0) {
          if (requirement.maxCharacters > 0) {
            requirementWords +=
              `${requirement.minCharacters} to ${requirement.maxCharacters} characters`;
          } else {
            if (requirement.minCharacters === 1) {
              requirementWords += "Any number of characters.";
            } else {
              requirementWords += `Minimum ${requirement.minCharacters} characters`;
            }
          }
        } else if (requirement.maxCharacters > 0) {
          requirementWords += `Up to ${requirement.maxCharacters} characters`;
        }
      }

      return requirementWords;
    },
    wordsCount() {
      return this.inputText ? this.inputText.match(/\S+/g).length : 0;
    },
    charactersCount() {
      return this.inputText ? this.inputText.length : 0;
    },
    descriptionLength() {
      if(this.xs || this.s) return 105;
      if(this.m) return 165;
      if(this.l) return 310;
      if(this.xl) return 160;
      if(this.xxl) return 370;
    },
    ...mapState({
      scholarship: state => state.scholarships.selected,
    }),
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl",
    }),
    sentText() {
      const application = this.$store.getters["scholarships/getRequirementApplication"](this.requirement);

      return application && application.text ? application.text : "";
    },
  },
  methods: {
    inputHolder() {
      if(!this.inputText) return;

      debounce(() => {
        this.applyRequirement({ text: this.inputText })
          .then(() => {
            if(this.isClosing) {
              this.$emit('close');
            }

            this.resetInputStates();
          })
          .catch(() => {
            this.resetInputStates();
          })
      })
    },
    saveAndClose() {
      if(this.saving || this.timeout) return;

      if(!this.inputText.length) {
        this.$emit('close');
        this.resetInputStates();
        return;
      }

      this.applyRequirement({ text: this.inputText })
          .then(() => {
              this.$emit('close');
              this.resetInputStates();
          })
    },
    resetInputStates() {
      this.isClosing = false;
      this.timeout = null;
    },
    handleClick(ev) {
      if(ev.target.tagName.toLowerCase() === "a") {
        this.descriptionOpen = !this.descriptionOpen;
      }
    },
    textareaInView(ev) {
      if(navigator.userAgent.toLowerCase().indexOf("android") > -1) {
        setTimeout(() => {
          this.$refs.tih.scrollTop = ev.target.getBoundingClientRect().top + this.$refs.tih.scrollTop - 70; // top bar height;
        }, 500);
      }
    },
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

.tih {
  $blue: #708FE7;
  $blue-light: #F3F8FF;
  $blue-dark: #4E8EEC;
  $dark: #2F2F2F;
  $grey: #D8D8D8;
  $dark-grey: #797979;

  font-size: 14px;
  line-height: 1.42em;
  color: $dark;
  background-color: white;

  width: 100%;
  top: 0; left: 0;
  position: fixed;
  z-index: 9999;
  @include flexbox();

  @include breakpoint($s $l - 1px) {
    background-color: #F3F8FF;
  }

  @include breakpoint(max-width $l - 1px) {
    top: 0; bottom: 0;
    overflow-y: auto;
    min-height: 0;
  }

  @include breakpoint($l) {
    background-color: rgba(53, 76, 109, 0.25);
    @include flexbox();
    @include align-items(center);
    height: 100%;
  }

  &__frame {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    padding: 0 15px;
    background-color: white;
    box-sizing: border-box;
    @include flexbox();
    width: 100%;

    @include breakpoint($s) {
      margin: 25px;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.07);
    }

    @include breakpoint($l) {
      max-height: 475px;
      width: 632px;
      margin-left: auto;
      margin-right: auto;
      padding: 0 25px;
      display: block;
    }

    @include breakpoint($xl) {
      width: 890px;
      max-height: 556px;
    }
  }

  &__wrp {
    margin-top: 15px;
    margin-bottom: 15px;
    overflow: hidden;
    @include flexbox();
    @include flex-direction(column);
    @include flex(1 1 auto);

    @include breakpoint($l) {
      margin-top: 25px;
      margin-bottom: 25px;
    }
  }

  &-description-open {
    .tih__frame {
      @include breakpoint($l) {
        overflow: auto;
      }
    }

    .tih__wrp {
      @include breakpoint(max-width $l - 1px) {
        overflow: auto;
      }
    }

    .tih__textarea {
      min-height: 130px;

      @include breakpoint($l) {
        min-height: 200px;
      }
    }

    .tih__description-text {
      min-height: auto;
    }
  }

  &__btn {
    .icon {
      color: $grey;
      margin-right: 5px;
      vertical-align: middle;
      font-size: 24px;
    }
  }

  &__title {
    color: $blue;
    font-size: 16px;
    font-weight: 700;
  }

  &__text-requirememnt {
    color: $dark;
    font-size: 12px;
    line-height: 1.66em;

    strong {
      font-weight: 700;
      text-transform: uppercase;
    }
  }

  &__description-text {
    line-height: 1.5em;
    margin-top: 17px;
    min-height: 80px;

    .collapsed {
      &:after {
        content: '...';
        margin-left: -2px;
      }
    }

    a {
      color: $blue;
      text-decoration: underline;
      display: block;
    }
  }

  &__character-count {
    margin-left: 20px;

    @include breakpoint($m) {
      margin-left: 28px;
    }

    @include breakpoint($l) {
      margin-left: 22px;
    }
  }

  &__textarea {
    font-size: 12px;
    color: $blue;
    background-color: $blue-light;
    box-sizing: border-box;
    width: 100%;
    padding: 10px;
    border: none;
    resize: none;
    margin-top: 14px;
    min-height: 60px;
    @include flex(1 1 auto);

    @include breakpoint($l) {
      min-height: 200px;
    }
  }

  &__statistic {
    color: $blue;
    font-size: 12px;
    margin-top: 14px;
    line-height: 1.666em;
    min-height: 20px;

    @include breakpoint($s) {
      @include flexbox();
      @include justify-content(space-between);
    }
  }

  &__btn {
    margin-top: 20px;
    float: right;

    color: white;
    text-transform: uppercase;
    text-align: center;
    font-size: 12px;
    font-weight: 700;

    width: 118px;
    min-height: 30px;
    line-height: 30px;
    background-color: $blue-dark;
    @include align-self(flex-end);

    &:hover {
      background-color: darken($blue-dark, 5);
    }
  }
}

</style>
