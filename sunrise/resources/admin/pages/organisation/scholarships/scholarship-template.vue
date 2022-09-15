<template>
  <div v-if="template && template.id" class="page scholarship-template">
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div class="container">
      <div class="block">

        <div class="tile is-parent">
          <div class="tile is-child">
            <button class="button is-round is-grey is-pulled-right" @click="deleteTemplate">
              <c-icon icon="cancel" />
            </button>
            <router-link class="button is-round is-grey is-pulled-right mr-5" :to="{ name: 'scholarships.settings', params: { id: template.id } }">
              <c-icon icon="pencil" />
            </router-link>
            <p class="title">{{ template.title }}</p>
            <p class="subtitle">
              {{ template.description }}
            </p>
          </div>
        </div>
        <div class="tile is-parent">
          <div class="tile is-child">
            <div class="tile is-parent basic-info">
              <div class="tile is-child">
                <!-- <strong>Deadline</strong> -->
                <template v-if="!template.recurrenceConfig">
                  <p><strong>Dealine</strong></p>
                  <p class="has-text-danger">Scholarship missing deadline configurations</p>
                </template>
                <template v-else-if="template.recurrenceConfig.type === 'oneTime'">
                  <p><strong>One time scholarship</strong></p>
                  <p><span>Start: </span><strong>{{ template.recurrenceConfig.start }}</strong></p>
                  <p><span>Deadline: </span><strong>{{ template.recurrenceConfig.deadline }}</strong></p>
                </template>
                <template v-else-if="template.recurrenceConfig.type === 'weeklyScholarship'">
                  <p><strong>Weekly scholarship</strong></p>
                  <p><span>Start:</span><strong>{{ weekdaysFull[template.recurrenceConfig.startDay - 1] }}</strong></p>
                  <p><span>Deadline:</span><strong>{{ weekdaysFull[template.recurrenceConfig.deadlineDay - 1] }}</strong></p>
                  <p v-if="template.recurrenceConfig.occurrences">
                    <span>Occurrences:</span><strong>{{ template.recurrenceConfig.occurrences }}</strong>
                  </p>
                  <p v-if="template.recurrenceConfig.startsAfterDeadline">
                    <strong>Starts after deadline</strong>
                  </p>
                </template>
                <template v-else-if="template.recurrenceConfig.type === 'monthlyScholarship'">
                  <p><strong>Monthly scholarship</strong></p>
                  <p><span>Start:</span><strong>{{ template.recurrenceConfig.startDate }}</strong></p>
                  <p>
                    <span>Deadline:</span>
                    <strong v-if="template.recurrenceConfig.deadlineEndOfMonth">End of month</strong>
                    <strong v-else>{{ template.recurrenceConfig.deadlineDate }}</strong>
                  </p>
                  <p v-if="template.recurrenceConfig.occurrences">
                    <span>Occurrences:</span><strong>{{ template.recurrenceConfig.occurrences }}</strong>
                  </p>
                </template>
                <template v-else-if="template.recurrenceConfig.type === 'advanced'">
                  <p><strong>Advanced config</strong></p>
                  <p><span>Start: </span><strong>{{ template.recurrenceConfig.start }}</strong></p>
                  <p><span>Deadline: </span><strong>{{ template.recurrenceConfig.deadline }}</strong></p>
                  <p><span>Period: </span>
                    <strong>{{ template.recurrenceConfig.periodValue }}</strong>
                    <strong>{{ template.recurrenceConfig.periodType }}</strong>
                  </p>
                  <p v-if="template.recurrenceConfig.occurrences">
                    <span>Occurrences:</span><strong>{{ template.recurrenceConfig.occurrences }}</strong>
                  </p>
                </template>
                </br>
                <strong>Timezone</strong>
                <p>{{ template.timezone }}</p>
              </div>
              <div class="tile is-child">
                <strong>Awards</strong>
                <p>{{ template.awards }}</p>
                <strong>Amount</strong>
                <p>${{ template.amount }}</p>
              </div>
              <div v-if="template.website" class="tile is-child">
                <strong>URL</strong>
                <a :href="template.website.meta.url" target="_blank">
                  <p>{{ template.website.meta.url }}</p>
                  <template
                    v-if="template.website.layout === design.layout && template.website.variant === design.variant"
                    v-for="design in designs">
                    <figure class="image is-138x132">
                      <img :src="design.imageSmall" />
                    </figure>
                  </template>
                </a>
              </div>
            </div>
          </div>

          <div class="tile box-flat is-child is-3">
            <h4 class="title is-4">Published scholarship</h4>
            <div v-if="template.published">
              <p>
                <router-link :to="{ name: 'scholarships.published.show', params: { id: template.published.id }}">
                  {{ template.published.id }}
                </router-link>
              </p>
              <strong>Starts:</strong>
              <p>
                <span>{{ template.published.start | moment('utc') | moment('MM/DD/YYYY, h:mm a') }}</span>
                <span class="has-text-success">{{ template.published.start | moment('utc') | moment('from', 'now') }}</span>
              </p>
              <strong>Deadline:</strong>
              <p>
                <span>{{ template.published.deadline | moment('utc') | moment('MM/DD/YYYY, h:mm a') }}</span>
                <span class="has-text-danger">{{ template.published.deadline | moment('utc') | moment('from', 'now') }}</span>
              </p>
            </div>
            <div v-else>
              <p class="has-text-warning">Scholarship is not published!</p>
            </div>
          </div>
        </div>

        <div class="tile is-parent">
          <div class="tile is-child">
            <expired-instances :templateId="$route.params.id"/>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
<script>
import ExpiredInstances from 'components/scholarship/template/ExpiredInstances';
import store from 'store';

const loadScholarshipTemplate = (to, from, next) => {
  const loaded = store.state.organisation.scholarshipTemplate.item.id;

  // if (to.params.id === loaded) {
  //   return next();
  // }

  store.dispatch('organisation/scholarshipTemplate/load', to.params.id)
    .then(next);
};

export default {
  name: 'ScholarshipTemplate',
  components: {
    ExpiredInstances
  },
  beforeRouteEnter: loadScholarshipTemplate,
  beforeRouteUpdate: loadScholarshipTemplate,
  computed: {
    designs: () => store.state.templates.list,
    weekdaysFull: () => (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
    breadcrumbs({ $route, template }) {
      return {
        'Scholarships': { name: 'scholarships' },
        [template.title]: {
           name: 'scholarships.show',
           params: { id: this.$route.params.id }
        }
      }
    },
    template({ $store }) {
      return $store.state.organisation.scholarshipTemplate.item;
    },
  },
  methods: {
    deleteTemplate() {
      const title = this.template.title;
      this.$dialog.confirm({
        title: 'Delete scholarship confirmation',
        message: `Are you sure you want remove <b>${title}</b>?`,
        confirmText: 'Delete scholarship',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => {
          this.$store.dispatch('organisation/scholarshipTemplate/delete', this.template).then(() => {
            this.$store.dispatch('organisation/scholarships/load');
            this.$toast.open({ type: 'is-danger', message: `Scholarship <b>${title}</b> deleted.`});
            this.$router.push({ name: 'scholarships' });
          })
        }
      })
    }
  }
}
</script>
<style lang="scss">
.scholarship-template {
  .box-flat {
    background-color: #FAFBFC;
    padding: 30px 30px 20px;
  }
  .tile.basic-info {
    .is-child {
      padding: 10px;
    }
  }
}
</style>
