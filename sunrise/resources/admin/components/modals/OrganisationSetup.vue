<template>
  <div class="organisation-setup-content">
    <h3 class="title is-3">Organisation Setup</h3>
    <h4 class="subtitle">
      <p>Important to fill the following information. You could change some of it later in
        <router-link :to="{ name: 'profile' }">Profile Settings</router-link>
      </p>
      <p>It is used at scholarship creation, and will be part of automatically generated documents.</p>
    </h4>
    <form @submit.prevent="save">

    <c-field
      horizontal
      label="Company Full Name"
      :message="errors.first('name')"
      :type="errors.has('name') ? 'is-danger' : null">
      <b-input
        name="name"
        placeholder="Company Name"
        v-model="item.name"
        data-vv-validate-on="blur"
        v-validate="'required|max:255'"
      />
    </c-field>

    <c-field
      horizontal
      label="Business Name"
      :message="errors.first('businessName')"
      :type="errors.has('businessName') ? 'is-danger' : null">
      <b-input
        name="businessName"
        placeholder="Business Name"
        v-model="item.businessName"
        data-vv-validate-on="blur"
        v-validate="'required|max:255'"
      />
    </c-field>

    <c-field
      horizontal
      label="Company E-mail"
      :message="errors.first('email')"
      :type="errors.has('email') ? 'is-danger' : null">
      <b-input
        name="email"
        placeholder="E-mail"
        v-model="item.email"
        data-vv-validate-on="blur"
        v-validate="'required|email|max:255'"
      />
    </c-field>

    <c-field
      horizontal
      label="Country"
      :message="errors.first('country')"
      :type="errors.has('country') ? 'is-danger' : null">
      <b-select
        name="country"
        :value="item.country ? item.country.id : null"
        @input="setCountryModel"
        v-validate="'required'"
        data-vv-validate-on="blur"
        placeholder="Select country">
        <option v-for="(country, id) in countries" :value="id" :key="id">
          {{ country.name }}
        </option>
      </b-select>
    </c-field>

    <c-field v-if="item.country && item.country.id === '1'" horizontal label="State">
      <c-field
        :message="errors.first('state')"
        :type="errors.has('state') ? 'is-danger' : null">
        <b-select
          name="state"
          :value="item.state ? item.state.id : null"
          @input="setStateModel"
          v-validate="'required'"
          data-vv-validate-on="blur"
          placeholder="Select state">
          <option v-for="(state, id) in states" :value="id" :key="id">
            ({{ state.abbreviation }}) {{ state.name }}
          </option>
        </b-select>
      </c-field>
      <c-field
        horizontal
        label="Zip code"
        :message="errors.first('zip')"
        :type="errors.has('zip') ? 'is-danger' : null">
        <b-input
          name="zip"
          placeholder="Zip Code"
          v-model="item.zip"
          v-validate="'required|min:5|max:255'"
          data-vv-validate-on="blur"
        />
      </c-field>
    </c-field>

    <c-field
      horizontal
      label="City"
      :message="errors.first('city')"
      :type="errors.has('city') ? 'is-danger' : null">
      <b-input
        name="city"
        placeholder="City"
        v-model="item.city"
        v-validate="'required|max:255'"
        data-vv-validate-on="blur"
      />
    </c-field>

    <c-field
      horizontal
      label="Address"
      :message="errors.first('address')"
      :type="errors.has('address') ? 'is-danger' : null">
      <b-input
        name="address"
        placeholder="Address"
        v-model="item.address"
        v-validate="'required|max:255'"
        data-vv-validate-on="blur"
      />
    </c-field>

    <c-field horizontal>
      <b-input
        name="address2"
        v-model="item.address2"
      />
    </c-field>

    <c-field
      v-if="item.country && item.country.id === '1'"
      horizontal
      label="Company Phone Number"
      :message="errors.first('phone')"
      :type="errors.has('phone') ? 'is-danger' : null">
      <masked-input
        name="phone"
        class="input"
        :class="{ 'is-danger': errors.has('phone') }"
        v-model="item.phone"
        v-validate.disable="'required|max:255'"
        mask="\+\1 (111) 111-1111"
        placeholder="+1 (XXX) XXX-XXXX"
        type="text" />
    </c-field>
    <c-field
      v-else
      horizontal
      label="Company Phone Number"
      :message="errors.first('phone')"
      :type="errors.has('phone') ? 'is-danger' : null">
      <b-input
        name="phone"
        v-model="item.phone"
        v-validate.disable="'required|max:255'"
        placeholder="Phone number"
        type="text" />
    </c-field>

    <c-field
      horizontal
      label="Website"
      :message="errors.first('website')"
      :type="errors.has('website') ? 'is-danger' : null">
      <b-input
        name="website"
        placeholder="www."
        v-model="item.website"
        data-vv-validate-on="blur"
        v-validate="'required|max:255'"
      />
    </c-field>

    <c-field class="has-text-centered">
      <button class="button is-rounded is-primary" type="submit">
        <span>PUBLISH</span>
      </button>
    </c-field>

    </form>
  </div>
</template>
<script>
import Vue from 'vue';
import Vuex from 'vuex';
import MaskedInput from 'vue-masked-input/src/MaskedInput';

import states from 'states.json';
import countries from 'countries.json';
import { ItemStore } from 'lib/store/factory';
import { JsonaModel } from 'lib/jsona';
import { parseErrors } from 'lib/utils';

export default {
  name: 'OrganisationSetupModal',
  components: {
    MaskedInput,
  },
  data: function() {
    return {
      states,
      countries,
      store: new Vuex.Store(
        ItemStore('organisation', {
          item: this.$store.getters['user/organisation'],
          include: ['state', 'country'],
        })
      )
    }
  },
  created() {
    this.store.dispatch('load', this.item.id);
  },
  methods: {
    save() {
      this.$validator.validateAll()
        .then((result) => {
            if (!result) return;
            this.store.dispatch('save')
              .then(() => {
                this.$store.dispatch('modals/resolve', { modal: 'organisationSetup', value: true })
              })
              .catch((error) => {
                if (error.response && error.response.status === 422) {
                  parseErrors(error.response.data, this.$validator);
                }
              })
        });
    },
    setStateModel(id) {
      Vue.set(this.item, 'state', JsonaModel.instance(id, 'state'));
    },
    setCountryModel(id) {
      Vue.set(this.item, 'country', JsonaModel.instance(id, 'country'));
    },
  },
  computed: {
    item: ({ store }) => store.state.item,
  },
};
</script>
<style lang="scss" scoped>
.organisation-setup-content {
  padding-right: 55px;

  .field /deep/ {
    .field-body {
      flex-grow: 2.5;
    }
  }

  .button {
    height: 62px;
    width: 172px;
  }

  .bg {
    position: absolute;
    margin: 10px 0;
    bottom: 0;
    right: 0;
  }
}
</style>
