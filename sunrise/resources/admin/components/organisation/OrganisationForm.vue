<template>
  <div>

    <c-field
      horizontal
      label="Company Name"
      :message="errors.first('name')"
      :type="errors.has('name') ? 'is-danger' : null">
      <b-input
        name="name"
        v-model="item.name"
        data-vv-validate-on="blur"
        v-validate="'required|max:255'"
      />
    </c-field>

    <c-field
      horizontal
      label="Bussines Name"
      :message="errors.first('businessName')"
      :type="errors.has('businessName') ? 'is-danger' : null">
      <b-input
        name="businessName"
        v-model="item.businessName"
        data-vv-validate-on="blur"
        v-validate="'required|max:255'"
      />
    </c-field>

    <h6 class="title is-6 has-barline">
      <span>Company Address</span>
    </h6>

    <c-field
      horizontal
      label="Country"
      :message="errors.first('country')"
      :type="errors.has('country') ? 'is-danger' : null">
      <b-select
        name="country"
        :value="item.country ? item.country.id : null"
        @input="item.country = getCountryModel($event)"
        v-validate="'required'"
        data-vv-validate-on="blur"
        placeholder="Select country">
        <option v-for="(country, id) in countries" :value="id" :key="id">
          {{ country.name }}
        </option>
      </b-select>
    </c-field>

    <c-field
      v-if="item.country && item.country.id === '1'"
      horizontal
      label="State"
      :message="errors.first('state')"
      :type="errors.has('state') ? 'is-danger' : null">
      <b-select
        name="state"
        :value="item.state ? item.state.id : null"
        @input="item.state = getStateModel($event)"
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
      label="City"
      :message="errors.first('city')"
      :type="errors.has('city') ? 'is-danger' : null">
      <b-input
        name="city"
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
      horizontal
      label="Zip code"
      :message="errors.first('zip')"
      :type="errors.has('zip') ? 'is-danger' : null">
      <b-input
        name="zip"
        v-model="item.zip"
        v-validate="'required|min:5|max:255'"
        data-vv-validate-on="blur"
      />
    </c-field>

    <h6 class="title is-6 has-barline">
      <span>Company Contacts</span>
    </h6>

    <c-field
      v-if="item.country && item.country.id === '1'"
      horizontal
      label="Phone"
      :message="errors.first('phone')"
      :type="errors.has('phone') ? 'is-danger' : null">
      <masked-input
        name="phone"
        class="input"
        :class="{ 'is-danger': errors.has('phone') }"
        v-model="item.phone"
        v-validate="'required|max:255'"
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
      label="E-mail"
      :message="errors.first('email')"
      :type="errors.has('email') ? 'is-danger' : null">
      <b-input
        name="email"
        v-model="item.email"
        data-vv-validate-on="blur"
        v-validate="'required|email|max:255'"
      />
    </c-field>

    <c-field
      horizontal
      label="Website"
      :message="errors.first('website')"
      :type="errors.has('website') ? 'is-danger' : null">
      <b-input
        name="website"
        v-model="item.website"
        data-vv-validate-on="blur"
        v-validate="'required|max:255'"
      />
    </c-field>

  </div>
</template>
<script>
import states from 'states.json';
import countries from 'countries.json';

import { ItemStore } from 'lib/store/factory';
import { JsonaModel } from 'lib/jsona';
import Vuex from 'vuex';
import MaskedInput from 'vue-masked-input/src/MaskedInput';

export default {
  name: 'OrganisationForm',

  components: {
    MaskedInput
  },

  props: {
    organisationId: {
      type: String,
      required: true,
    }
  },

  created() {
    this.store.dispatch('load', this.organisationId);
  },

  data() {
    return {
      states,
      countries,
      store: new Vuex.Store(ItemStore('organisation', {
        include: ['state', 'country']
      }))
    }
  },

  computed: {
    item: ({ store }) => store.state.item,
  },

  methods: {
    save() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll()
          .then((result) => {
            if (!result) return reject();

            this.store.dispatch('save')
              .then(resolve)
              .catch(error => {
                if (error.response && error.response.status === 422) {
                  parseErrors(error.response.data, this.$validator);
                }

                reject(error);
              })
          })
          .catch(reject);
      });
    },
    getStateModel(id) {
      return JsonaModel.instance(id, 'state');
    },
    getCountryModel(id) {
      return JsonaModel.instance(id, 'country');
    }
  },

  watch: {
    organisationId: (id) => this.store.dispatch('load', id),
  },

}
</script>
<style lang="scss" scoped>
.title.has-barline {
  margin-top: 1rem;
}
</style>
