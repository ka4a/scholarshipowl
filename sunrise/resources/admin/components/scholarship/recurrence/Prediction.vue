<template>
  <ul class="recurrence-prediction">
    <template v-for="prediction, index in recurrencePrediction" v-if="!minified || (index < 5)">
      <li>
        <div class="info">
          <span class="occurrence">#{{ prediction.occurrence }}</span>
          <span class="date">{{ prediction.start | date }} - {{ prediction.deadline | date }}</span>
        </div>
        <div>
          <slot name="edit" v-bind:prediction="prediction" />
        </div>
      </li>
      <!-- <li v-if="editableOpen[index]">
        <slot name="edit" v-bind:prediction="prediction" @input="testEditClose" />
      </li> -->
    </template>
    <template v-if="recurrencePrediction.length">
      <div>
        <span v-if="minified" class="minified-button" @click="minified = false">Show more instances</span>
        <span v-else class="minified-button" @click="minified = true">Hide instances</span>
      </div>
    </template>
  </ul>
</template>
<script>
const monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"];
export default {
  props: {
    config: Object,
    editable: Boolean,
  },
  filters: {
    date(value) {
      const parsed = Date.parse(value);
      if (parsed) {
        const date = new Date(parsed);
        return `${monthNames[date.getUTCMonth()]} ${date.getUTCDate()}, ${date.getUTCFullYear()}`
      }
    }
  },
  create() {

  },
  data() {
    return {
      minified: true,
      occurrences: 15,
      recurrencePrediction: [],
      editableOpen: {},
    }
  },
  methods: {
    show() {
      this.minified = false;
      this.occurrences = 15;
    },
    hide() {
      this.minified = true;
    },
    moreOccurrences() {
      this.occurrences = this.occurrences + 15;
    },
    predict() {
      const occurrences = this.config.occurrences || this.occurrences;

      if (!this.config) {
        this.recurrencePrediction = [];
        return;
      };

      this.$http.post('/api/scholarship_template/recurrence_prediction', { ...this.config, occurrences })
        .then(({ data }) => this.recurrencePrediction = data)
        .catch((error) => {
          this.recurrencePrediction = [];
          if (error && error.response && error.response.status === 422 && error.response.data) {
            this.$emit('errors', error.response.data);
          }
        });
    },
    openEditable(index) {
      Vue.set(this.editableOpen, index, true);
    },
    closeEditable(index) {
      Vue.delete(this.editableOpen, index);
    }
  },
  watch: {
    config: {
      deep: true,
      immediate: true,
      handler: function() {
        this.predict();
      }
    },
    occurrences: {
      handler: function() {
        this.predict();
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.recurrence-prediction {
  position: relative;
  width: 100%;
  font-size: 15px;
  color: #656565;

  > li {
    // border-bottom: 1px solid #CCD6E6;
    position: relative;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.5);
    margin-bottom: 7px;
    color: #656565;

    .info {
      display: flex;
      padding: 10px 16px;
    }

    span {
      &.occurrence {
        flex-grow: 1;
      }
      &.date {
        flex-grow: 8;
      }
    }
    &:hover {
      cursor: pointer;
      background: #FFFFFF;
      border-radius: 5px;
    }
    & :not(:first-child) {
    }
  }

  .minified-button {
    cursor: pointer;
    margin-top: 13px;
    color: #909CB0;
    border-bottom: 1px dotted #909CB0;
  }

  // .fade {
  //   cursor: pointer;
  //   padding: 10px;
  //   text-align: center;
  //   .action {
  //     text-decoration: underline;
  //     font-size: 16px;
  //   }
  // }

  // &.minified {
  //   max-height: 170px;
  //   overflow: hidden;
  //   .fade {
  //     position: absolute;
  //     top: 100px;
  //     left: 0;
  //     right: 0;
  //     background: linear-gradient(to bottom, rgba(white,0) 0%,rgba(white,1) 75%);
  //     height: 70px;
  //     .action {
  //       position: absolute;
  //       bottom: 0;
  //       left: 0;
  //       right: 0;
  //     }
  //   }
  // }
}
</style>
