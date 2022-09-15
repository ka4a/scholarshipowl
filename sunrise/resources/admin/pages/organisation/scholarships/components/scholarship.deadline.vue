<template>
  <div class="scholarship-deadline">
    <p class="info-block">
      Choose scholarship repetition type and set scholarship deadline date.
    </p>

    <b-tabs v-model="configType" :animated="false">
      <b-tab-item label="One time">
        <one-time-config v-model="config" :ref="types[0]" />
      </b-tab-item>
      <b-tab-item label="Weekly">
        <weekly-config v-model="config" :ref="types[1]" />
      </b-tab-item>
      <b-tab-item label="Monthly">
        <monthly-config v-model="config" :ref="types[2]" />
      </b-tab-item>
      <b-tab-item label="Other">
        <advanced-config v-model="config" :ref="types[3]" />
      </b-tab-item>
    </b-tabs>

    <b-field class="is-pulled-right">
      <button class="button is-rounded is-primary" @click="save()">
        <template v-if="$route.params.isNewScholarship">
          <span>Save & Continue</span>
          <c-icon icon="arrow-right" :class="{ 'is-loading': loading }" />
        </template>
        <template v-else>
          <c-icon icon="check-circle" />
          <span>Save</span>
        </template>
      </button>
    </b-field>
  </div>
</template>
<script>
import { parseErrors } from 'lib/utils';

import Timezone from 'components/scholarship/recurrence/Timezone';
import WeeklyConfig from 'components/scholarship/recurrence/WeeklyConfig';
import MonthlyConfig from 'components/scholarship/recurrence/MonthlyConfig';
import OneTimeConfig from 'components/scholarship/recurrence/OneTimeConfig';
import AdvancedConfig from 'components/scholarship/recurrence/AdvancedConfig';

const types = ['oneTime', 'weeklyScholarship', 'monthlyScholarship', 'advanced'];

export default {
  components: {
    Timezone,
    WeeklyConfig,
    MonthlyConfig,
    OneTimeConfig,
    AdvancedConfig
  },
  data: function() {
    const { recurrenceConfig: config, timezone }  = this.$store.getters['organisation/scholarshipSettings/item'];

    return {
      types,
      config: { ...config, timezone },
      configType: (config && config.type) ? types.indexOf(config.type) : 0,
    }
  },
  methods: {
    save() {
      const type = this.types[this.configType];
      const configForm = this.$refs[type];
      const validator = configForm.$validator;

      if (!configForm) {
        throw new Error('Config form not found');
      }

      validator.errors.clear();
      validator.validateAll()
        .then((result) => {
          if (result) {
            const recurrenceConfig = { ...this.config, type };
            const timezone = this.config.timezone;
            delete recurrenceConfig['timezone'];

            const form = {
              data: {
                attributes: {
                  recurrenceConfig,
                  timezone,
                }
              }
            };

            this.$store.dispatch('organisation/scholarshipSettings/save', { form })
              .then(() => this.$toast.open({ type: 'is-success', message: 'Deadline configurations updated' }))
              .then(() => {
                if (this.$route.params.isNewScholarship) {
                  this.$router.push({
                    name: 'scholarships.settings.fields',
                    params: { id: this.scholarship.id, isNewScholarship: true }
                  })
                }
              })
              .catch((response) => {
                if (response && response.status === 422) {
                  parseErrors(response.data, validator)
                  return;
                }
                throw response;
              })
          }
        })
    }
  },
  computed: {
    loading: ({ $store }) => $store.getters['organisation/scholarshipSettings/loading'],
    scholarship: ({ $store }) => $store.getters['organisation/scholarshipSettings/item'],
  },
}
</script>
<style lang="scss" scoped>
.b-tabs {
  .tab-item {
    &.slide-next-enter-active {
      background: red;
      overflow: hidden;
    }
    &.slide-prev-enter-active {
      background: red;
      overflow: hidden;
    }
    /deep/ .control {
      .input, .select {
        max-width: 420px;
      }
    }
  }
}
/deep/ .helper-right {
  background: #F5F7FA;
  border-radius: 5px;
  padding: 30px 40px;
  font-size: 15px;
  .help {
    font-size: 15px;
    color: #656565;
    strong {
      color: #656565;
    }
    & + .help {
      margin-top: 20px;
    }
  }
}
/deep/ .field {
  margin-bottom: 20px;
  .label {
    font-size: 15px;
    font-weight: 600;
    color: #1B2942;
    margin-bottom: 13px;
  }
  & + .help {
    margin-bottom: 20px;
    font-size: 15px;
    color: #656565;
  }
}
</style>
