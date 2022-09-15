<template>
  <b-modal :active="active"
    class="application-modal"
    @close="close()">
    <div class="modal-card" v-if="application && application.id">
      <div class="modal-card-head">
        <b-icon icon="close cursor-pointer" class="close" @click.native="close()"/>
        <div class="modal-card-title">
          <h3 class="title is-3">Application ({{ application.id }})</h3>
          <p class="subtitle is-5"><c-icon icon="clock" />{{ application.createdAt | moment('MMMM Do YYYY, h:mm:ss a') }}</p>
          <p v-if="application.source === 'sowl'" class="source">from SOWL</p>
          <p v-else-if="application.source === 'barn'" class="source">from landing page</p>
          <p v-else class="source">from unknown.</p>
        </div>
        <div class="modal-card-icon">
          <button class="button is-rounded is-outlined is-accept" @click="accept"
            :class="{ 'is-active': application.status && application.status.id === 'accepted' }">
            <i class="button_dot" />
            <span>Accept</span>
          </button>
          <button class="button is-rounded is-outlined is-reject" @click="reject"
            :class="{ 'is-active': application.status && application.status.id === 'rejected' }">
            <i class="button_dot" />
            <span>Reject</span>
          </button>
          <router-link v-if="next"
            class="button is-rounded is-outlined is-skip"
            :to="{ name: 'scholarships.published.review.application', params: { id: $route.params.id, application: next }}">
            <i class="button_dot" />
            <span>Skip</span>
          </router-link>
        </div>
      </div>
      <div class="modal-card-body">
        <div class="application-fields">
          <h3 class="application-title is-3">Form information</h3>
          <table class="table is-striped">
            <tbody>
              <tr v-for="field in scholarship.fields">
                <td>{{ field.field.name }}</td>
                <td>{{ fieldData(field) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="application-requirements">
          <h3 class="application-title is-3">Requirements</h3>
            <requirement-view
              v-for="(requirement,index) in application.requirements"
              :key="requirement.id"
              :number="index + 1"
              :requirement="requirement"
            />
          </div>
      </div>
    </div>
  </b-modal>
</template>
<script>
import { Store } from 'vuex';
import { ItemStore } from 'lib/store/factory';
import RequirementView from 'components/scholarship/RequirementView';

export default {
  components: {
    RequirementView
  },
  props: {
    active: Boolean,
    id: String,
    total: Number,
    next: String,
    prev: String,
  },
  created() {
    this.loading = true;
    this.store.dispatch('load', this.id)
      .then(() => {
        this.loading = false;
      })
      .catch(() => {
        this.loading = false;
      })
  },
  watch: {
    id: function(to) {
      if (this.application.id !== to) {
        this.loading = true;
        this.store.dispatch('load', to)
          .then(() => {
            this.loading = false;
          })
          .catch(() => {
            this.loading = false;
          })
      }
    }
  },
  data: function() {
    return {
      loading: false,
      store: new Store(
        ItemStore('application', {
          include: ['requirements', 'status']
        })
      )
    }
  },
  methods: {
    fieldData(field) {
      let value = this.application.data[field.field.id];

      if (field.field.type === 'option') {
        value = field.field.options[this.application.data[field.field.id]];

        if (typeof value === 'object' && value.name) {
          value = value.name;
        }
      }

      return value;
    },
    applicationRequirement(requirement) {
      return this.application.requirements.find(r => r.requirement.id === requirement.id);
    },
    close() {
      this.$emit('close');
    },
    accept() {
      if (this.application.status.id === 'accepted') {
        return;
      }

      const form = {
        data: {
          relationships: {
            status: {
              data: { id: 'accepted', type: 'application_status' }
            }
          }
        }
      }

      this.store.dispatch('save', { form })
      .then(application => {
        this.$emit('updated', application);
        if (this.next) {
          this.$router.push({
            name: 'scholarships.published.review.application',
            params: { id: this.$route.params.id, application: this.next }
          });
        }
      })
    },
    reject() {
      if (this.application.status.id === 'rejected') {
        return;
      }

      const form = {
        data: {
          relationships: {
            status: {
              data: { id: 'rejected', type: 'application_status' }
            }
          }
        }
      }

      this.store.dispatch('save', { form }).then(() => {
        if (this.next) {
          this.$router.push({
            name: 'scholarships.published.review.application',
            params: { id: this.$route.params.id, application: this.next }
          });
        }
      })
    }
  },
  computed: {
    application: ({ store }) => store.state.item,
    requirementData: ({ application }) => (r) => application.requirements.find(req => req.requirement.id === r.id),
    scholarship() {
      return this.$store.state.organisation.scholarshipsPublishedPage.item;
    },
  }
}
</script>
<style lang="scss">
@import "~scss/variables.scss";
.application-modal {
  .modal-content {
    width: 1024px !important;
    overflow: initial;
    margin: 0 50px;
  }
  .modal-card {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #E6EAEE;
    border-radius: 8px;
    padding: 0;

    .modal-card-head {
      background: #ffffff;
      padding: 40px 30px 0 30px;
      border: none;
      .modal-card-title {
        text-align: left;
        .title {
          font-size: 20px;
          font-weight: 500;
          color: #000000;
          margin: 0;
        }
        .subtitle {
          font-size: 13px;
          color: $grey;
          margin-left: -8px;
          margin: 0;
        }
        .source {
          font-size: 13px;
          color: #0294FF;
        }
      }
      .icon.close {
        position: absolute;
        top: 15px;
        right: 15px;
      }
      .modal-card-icon {
        .button {

          &_dot {
            width: 15px;
            height: 15px;
            display: inline-block;
            border-radius: 50%;
            margin-right: 10px;
          }

          &:not(:last-child) {
            margin-right: 10px;
          }

          &.is-skip {
            &:hover {
              background: rgba(204, 214, 230, 0.18);
            }
            .button_dot {
              background: radial-gradient(6.50px at 50% 50%, #CAD3E0 0%, #B6BECA 100%), radial-gradient(6.50px at 50% 50%, #9DC58B 0%, #96B787 100%);
            }
          }
          &.is-accept {
            &:hover {
              border-color: #9BC089;
            }
            &.is-active {
              background: rgba(156, 194, 138, 0.18);
            }
            .button_dot {
              background: radial-gradient(6.50px at 50% 50%, #9DC58B 0%, #96B787 100%);
            }
          }
          &.is-reject {
            &:hover {
              border-color: #E23F54;
            }
            &.is-active {
              background: rgba(217, 55, 76, 0.18);
            }
            .button_dot {
              background: radial-gradient(6.50px at 50% 50%, #EE4B60 0%, #D9374C 100%), radial-gradient(6.50px at 50% 50%, #9DC58B 0%, #96B787 100%);
            }
          }
        }
      }
    }
  }

  .modal-card-body {
    padding: 20px 30px;
  }
  .application-title {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 10px;
    color: rgba(27, 41, 66, 0.41);
  }
  .application-fields {
    .table {
      width: 100%;
      &.is-striped {
        tbody tr {
          background: #F5F7FA;
          border: none;
          &:nth-child(even) {
            background: rgba(245, 247, 250, 0.3);
          }
        }
      }
    }
  }
  .application-requirements {
    > .card {
      &:not(:first-child) {
        margin-top: 13px;
      }
    }
  }

  .application-requirements {
    .card-content {
      overflow: hidden;
    }
  }

  .modal-close {
    display: none;
  }
  .modal-background {
    background: rgba(72, 69, 91, 0.8);
  }
}
</style>
