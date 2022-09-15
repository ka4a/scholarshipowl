<template>
  <div class="container">
    <section>
      <div>
        <h1 class="title">${{ scholarship.amount }} {{ scholarship.title }}</h1>
        <h2 class="subtitle">{{ scholarship.description }}</h2>
        <p>
          <strong>Started:</strong>{{ scholarship.start }}
        </p>
        <p>
          <strong>Deadline:</strong>{{ scholarship.deadline }}
        </p>
      </div>
    </section>
    <section>
      <span v-if="applicationErrors" v-for="(error,i) in applicationErrors" :key="i" style="color: red; font-size: 14px;">
        <div>{{ error }}</div>
      </span>
      <b-field label="Full name">
        <b-input v-model="application.name" />
      </b-field>
      <b-field label="Email">
        <b-input v-model="application.email" />
      </b-field>
      <b-field label="Phone">
        <b-input v-model="application.phone" />
      </b-field>
      <b-field label="State">
        <b-select v-model="state" placeholder="Select a name">
          <option
            v-for="(option,id) in states"
            :value="id"
            :key="id">
            ({{ option.abbreviation }}) {{ option.name }}
          </option>
        </b-select>
      </b-field>
      <button class="button" @click="apply">
        Apply
      </button>
    </section>
  </div>
</template>

<script>
import api from '~/lib/api';
import jsona from '~/lib/jsona';
import { cookieFromResponse } from '~/utils';
import scholarship from '~/config/scholarship.json';
import states from '~/config/states.json';
import AppLogo from '~/components/AppLogo.vue'

export default {
  components: {
    AppLogo,
  },
  data: function() {
    return {

      states,
      scholarship,
      applicationErrors: null,

      state: null,
      application: {
        name: null,
        email: null,
        phone: null,
      },
    }
  },
  methods: {
    apply() {
      console.log('application', this.application);
      console.log('scholarship', this.scholarship.type);
      api.post('http://sunrise.dev.scholarshipowl.com/api/application', {
        data: {
          attributes: {
            ...this.application
          },
          relationships: {
            scholarship: { data: { type: 'scholarship', id: this.scholarship.id } },
            state:       { data: { type: 'state',       id: this.state } },
          }
        }
      })
        .then((response) => {
          console.log('applied', response);
          this.applicationErrors = null;
          alert('success apply!');
        })
        .catch((error) => {
          console.log('error', error);
          if (error.response && error.response.status === 422) {
            this.applicationErrors = error.response.data.errors.map((error) => {
              return error.detail[0];
            });
          }
        })
    }
  }
}
</script>

<style>
.container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

p {
  font-size: 18px;
}

.title {
  font-family: "Quicksand", "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; /* 1 */
  display: block;
  font-weight: bold;
  font-size: 46px;
  letter-spacing: 1px;
}

.subtitle {
  font-weight: 300;
  font-size: 26px;
  word-spacing: 5px;
  padding-bottom: 15px;
}

.links {
  padding-top: 15px;
}
</style>
