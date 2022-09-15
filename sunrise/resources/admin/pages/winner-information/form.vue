<template>
  <div class="content">
    <b-field label="* Full name"
     :type="error['data.attributes.name'] ? 'is-danger':undefined"
     :message="error['data.attributes.name'] || undefined">
      <b-input v-model="winner.name" placeholder="Full name"></b-input>
    </b-field>

    <b-field class="file" label="Photo"
      :type="error['data.relationships.photo.data'] ? 'is-danger':undefined">
        <b-upload v-model="photoFile">
          <a class="button is-primary">
            <icon-upload class="icon-upload" fill="white"/><span>Upload</span>
          </a>
        </b-upload>
        <span class="control file-name" v-if="photoFile && photoFile.length">
          {{ photoFile[0].name }}
        </span>
    </b-field>
    <p class="help is-danger" v-html="photoError" />

    <b-field label="* Date of birth"
      :type="error['data.attributes.dateOfBirth'] ? 'is-danger':undefined"
      :message="error['data.attributes.dateOfBirth'] || undefined">
        <datepicker :error="!!error['data.attributes.dateOfBirth']" placeholder="Click to select..." v-model="dateOfBirth" :readonly="false" />
    </b-field>

    <b-field label="Testimonial"
     :type="error['data.attributes.testimonial'] ? 'is-danger':undefined"
     :message="error['data.attributes.testimonial'] || undefined">
      <div class="field-testimonial">
        <b-input type="textarea" v-model="winner.testimonial"></b-input>
        <span class="icon-information" @click="toggleTestimonialHelper">
          <icon-information />
          <div v-if="showTestimonialHelper" class="information-text">
            <div class="triangle"></div>
            <p>We encourage you to write a very short testimonial about how you got into our website and applied, how you feel about the win and what your plans are for the future in terms of your education and career.</p>
          </div>
        </span>
      </div>
    </b-field>

    <b-field label="* Email Address"
     :type="error['data.attributes.email'] ? 'is-danger':undefined"
     :message="error['data.attributes.email'] || undefined">
      <b-input v-model="winner.email" type="email" placeholder="nobody@nowhere.com" expanded></b-input>
    </b-field>

    <b-field label="* Phone Number"
     :type="error['data.attributes.phone'] ? 'is-danger':undefined"
     :message="error['data.attributes.phone'] || undefined">
      <div :class="'control' + ( error['data.attributes.phone'] ? ' has-icons-right' : '' )">
        <masked-input :class="'input' + ( error['data.attributes.phone'] ? ' is-danger' : '' )" v-model="phone" @input="winner.phone = arguments[1]" mask="\+\1 (111) 111-1111" placeholder="+1 (XXX) XXX-XXXX" type="text" />
        <b-icon class="icon is-right has-text-danger" v-if="error['data.attributes.phone']" icon="exclamation-circle" />
      </div>
    </b-field>

    <b-field label="* City"
      :type="(error['data.attributes.city']) ? 'is-danger' : undefined"
      :message="error['data.attributes.city']">
        <b-input v-model="winner.city" type="text" placeholder="City"></b-input>
    </b-field>
    <b-field label="* Address"
      :type="(error['data.attributes.address']) ? 'is-danger' : undefined"
      :message="error['data.attributes.address']">
        <b-input v-model="winner.address" type="text" placeholder="Please fill your address..."></b-input>
    </b-field>
    <b-field
      :type="(error['data.attributes.address2']) ? 'is-danger' : undefined"
      :message="error['data.attributes.address2']">
        <b-input v-model="winner.address2" type="text"></b-input>
    </b-field>

    <div class="columns">
      <div class="column is-5">
        <b-field label="* Zip Code"
          :type="error['data.attributes.zip'] ? 'is-danger' : undefined"
          :message="error['data.attributes.zip']">
          <b-input v-model="winner.zip" type="tel" placeholder="XXXXX"></b-input>
        </b-field>
      </div>
      <div class="column">
        <b-field label="* State"
          :type="error['data.relationships.state.data.id'] ? 'is-danger' : undefined"
          :message="error['data.relationships.state.data.id']">
          <b-select v-model="state" placeholder="Select state">
            <option v-for="(state, id) in states" :value="id" :key="id">
              ({{ state.abbreviation }}) {{ state.name }}
            </option>
          </b-select>
        </b-field>
      </div>
    </div>

    <b-field class="affidavit-download" horizontal label="Affidavit">
      <a class="control" :href="winner.application.id + '/affidavit.pdf'" download="affidavit.pdf">
        <button class="button download">
          <icon-download fill="#4F4F4F" class="icon-download"/>
          <span>Download</span>
        </button>
        <span class="title">Download the doc. Sign it and send it to us</span>
      </a>
    </b-field>

    <b-field class="affidavit-upload"
      :type="error['data.relationships.affidavit.data'] ? 'is-danger':undefined"
      :message="error['data.relationships.affidavit.data'] || undefined">
      <div class="columns">
        <div class="column is-5">
          <b-upload v-model="affidavitFile"
            multiple
            drag-drop>
            <div class="content has-text-centered">
              <p>
                <icon-upload class="icon-upload" fill="#4F4F4F"/>
              </p>
              <p>Drop your files here or click to upload</p>
            </div>
          </b-upload>
        </div>
        <div class="column tags is-7">
          <span v-for="(file, index) in affidavitFile" :key="index" class="tag is-primary" >
            <span>{{ file.name }}</span>
            <button class="delete is-small" type="button" @click="deleteAffidavitFile(index)" />
          </span>
        </div>
      </div>
    </b-field>

    <b-notification v-if="generalError" class="general-error" type="is-danger" has-icon :closable="false">
      <h4 class="title is-4">Whoops, something wrong.</h4>
      <p>Please try send the form later if it is not working please contact us via email
        <a :href="`mailto:${contactEmail}`">{{ contactEmail }}</a>.
      </p>
    </b-notification>

    <div class="payment-method">
      <b-tabs type="is-toggle" position="is-centered" v-model="paymentMethod">
        <b-tab-item>
          <template slot="header">
            <div class="has-text-centered">
              <paypal :active="paymentMethod === 0" />
              <div class="amount-text">
                <b-checkbox disabled="disabled" :value="paymentMethod === 0">Receive ${{ winner.scholarship.amount }} with PayPal</b-checkbox>
              </div>
            </div>
          </template>
          <b-field label="Paypal Email Account"
            :type="error['data.attributes.paypal'] ? 'is-danger':undefined"
            :message="error['data.attributes.paypal'] || undefined">
            <b-input type="text" v-model="winner.paypal" placeholder="email@example.com"/>
          </b-field>
        </b-tab-item>
        <b-tab-item>
          <template slot="header">
            <div class="has-text-centered">
              <icon-bank :active="paymentMethod === 1"/><span class="text-bank-account">Bank Account</span>
              <div class="amount-text">
                <b-checkbox disabled="disabled" :value="paymentMethod === 1">Receive ${{ winner.scholarship.amount }} with Bank Account</b-checkbox>
              </div>
            </div>
          </template>
          <b-field label="Bank Name"
            :type="error['data.attributes.bankName'] ? 'is-danger':undefined"
            :message="error['data.attributes.bankName'] || undefined">
            <b-input type="text" v-model="winner.bankName" />
          </b-field>
          <b-field label="Name of the Account"
            :type="error['data.attributes.nameOfAccount'] ? 'is-danger':undefined"
            :message="error['data.attributes.nameOfAccount'] || undefined">
            <b-input type="text" v-model="winner.nameOfAccount" />
          </b-field>
          <b-field label="Account Number"
            :type="error['data.attributes.accountNumber'] ? 'is-danger':undefined"
            :message="error['data.attributes.accountNumber'] || undefined">
            <b-input type="text" v-model="winner.accountNumber" />
          </b-field>
          <b-field label="Routing Number"
            :type="error['data.attributes.routingNumber'] ? 'is-danger':undefined"
            :message="error['data.attributes.routingNumber'] || undefined">
            <b-input type="text" v-model="winner.routingNumber" />
          </b-field>
        </b-tab-item>
      </b-tabs>

      <b-field class="has-text-centered">
        <a class="button is-warning is-large" :class="loading ? 'is-loading':''" type="submit" @click="submit">SEND</a>
      </b-field>
    </div>
  </div>
</template>
<script>
import Vue from 'vue';
import states from '../../states.json';
import axios from 'axios';

import IconUpload from './icon-upload.vue';
import IconDownload from './icon-download.vue';
import IconInformation from './icon-information.vue';
import IconBank from './icon-bank.vue';
import Paypal from './paypal.vue';
import Datepicker from './datepicker.vue';

import MaskedInput from 'vue-masked-input/src/MaskedInput';

export default {
  name: 'WinnerForm',
  components: {
    IconUpload,
    IconDownload,
    Datepicker,
    MaskedInput,
    IconInformation,
    IconBank,
    Paypal
  },
  props: {
    winner: Object
  },
  data: function() {
    const winner = this.winner;
    const photoFile = [];
    let affidavitFile = [];
    let dateOfBirth = null;

    if (winner.photo) {
      photoFile[0] = winner.photo;
    }
    if (winner.affidavit && winner.affidavit.length) {
      affidavitFile = winner.affidavit;
    }
    if (winner.dateOfBirth) {
      dateOfBirth = new Date(Date.parse(winner.dateOfBirth));
    }

    return {
      error: {},
      loading: false,
      generalError: false,
      paymentMethod: winner.paypal ? 0 : 1,
      showTestimonialHelper: false,
      states,
      state: winner.state.id,
      phone: winner.phone,
      dateOfBirth,
      photoFile,
      affidavitFile,
    }
  },
  computed: {
    contactEmail: ({ winner }) => winner.scholarship.website.meta.contacts.email,
    photoError: ({ error }) => {
      return error['data.relationships.photo.data'] ?
        error['data.relationships.photo.data'].join(' ') : '';
    },
    affidavitError: ({ error }) => {
      return error['data.relationships.affidavit.data'] ?
        error['data.relationships.affidavit.data'].join(' ') : '';
    },
  },
  methods: {
    toggleTestimonialHelper() {
      this.showTestimonialHelper = !this.showTestimonialHelper;
    },
    deleteAffidavitFile(index) {
      this.affidavitFile.splice(index, 1);
    },
    submit() {
      const w = this.winner;
      this.error = {};
      this.generalError = false;
      this.loading = true;

      /* Format date into string 'YYYY-MM-DD' */
      const formatYYYYMMDD = (date) => {
        const year = date.getFullYear()
        const month = date.getMonth() + 1
        const day = date.getDate()
        return year + '-' +
            ((month < 10 ? '0' : '') + month) + '-' +
            ((day < 10 ? '0' : '') + day)
      };

      var form = new FormData();
      form.append('data[attributes][name]', w.name);
      form.append('data[attributes][email]', w.email);
      form.append('data[attributes][phone]', w.phone);
      form.append('data[attributes][dateOfBirth]', this.dateOfBirth ? formatYYYYMMDD(this.dateOfBirth) : null);
      if (w.city) {
        form.append('data[attributes][city]', w.city);
      }
      if (w.address) {
        form.append('data[attributes][address]', w.address);
        form.append('data[attributes][address2]', w.address2 || '');
      }
      if (w.zip) {
        form.append('data[attributes][zip]', w.zip);
      }

      if (w.testimonial) {
        form.append('data[attributes][testimonial]', w.testimonial);
      }

      form.append('data[relationships][state][data][id]', this.state);
      form.append('data[relationships][state][data][type]', 'state');

      if (w.bankName || w.nameOfAccount || w.accountNumber || w.routingNumber) {
        form.append('data[attributes][bankName]', w.bankName);
        form.append('data[attributes][nameOfAccount]', w.nameOfAccount);
        form.append('data[attributes][accountNumber]', w.accountNumber);
        form.append('data[attributes][routingNumber]', w.routingNumber);
      }

      if (typeof w.paypal === 'string') {
        form.append('data[attributes][paypal]', w.paypal);
      }

      if (w.swiftCode) {
        form.append('data[attributes][swiftCode]', w.swiftCode);
      }

      if (this.photoFile && this.photoFile.length) {
        if (this.photoFile[0].id && this.photoFile[0]._type) {
          form.append('data[relationships][photo][data][id]', this.photoFile[0].id);
          form.append('data[relationships][photo][data][type]', this.photoFile[0]._type);
        } else {
          form.append('data[relationships][photo][data]', this.photoFile[0]);
        }
      }

      if (this.affidavitFile && this.affidavitFile.length) {
        this.affidavitFile.forEach((file, index) => {
          if (file.id && file._type) {
            form.append('data[relationships][affidavit][data]['+index+'][id]', file.id);
            form.append('data[relationships][affidavit][data]['+index+'][type]', file._type);
          } else {
            form.append('data[relationships][affidavit][data]['+index+']', file);
          }
        });
        // if (this.affidavitFile[0].id && this.affidavitFile[0]._type) {
        //   form.append('data[relationships][affidavit][data][id]', this.affidavitFile[0].id);
        //   form.append('data[relationships][affidavit][data][type]', this.affidavitFile[0]._type);
        // } else {
        //   form.append('data[relationships][affidavit][data]', this.affidavitFile[0]);
        // }
      }

      this.$store.dispatch('winnerInformation/save', form)
        .then((winner) => {
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          const response = error.response;
          if (response && response.status === 422) {
            if (response.data && response.data.errors) {
              response.data.errors.forEach((err) => {
                if (err.source && err.source.pointer) {
                  Vue.set(this.error, err.source.pointer, err.detail);
                }
              })
              this.$scrollTo('#winner-basic-info');
            }

            return;
          }

          this.generalError = true;
          throw error;
        })
    }
  }
}
</script>
<style lang="scss">
@import "../../scss/winner-information/variables.scss";

.input {
  padding: 8px 14px;
}

.control.has-icons-right {
  .icon {
    // height: 42px;
  }
  .textarea + .icon.is-right {
    right: 8px;
  }
}

.field.affidavit-download {
  .button.download {
    background-color: #F8F8F8;
    margin-right: 12px;
    .icon-download {
      margin-right: 8px;
    }
    &:hover {
      background-color: #E6E6E6;
    }
  }
  .field-label {
    text-align: left;
    margin-right: 0px;
  }
  .title {
    font-size: 14px;
    font-weight: normal;
    line-height: 38px;
  }
}
.field.affidavit-upload {
  .upload-draggable {
    border: 1px dashed #C4C4C4;
    box-sizing: border-box;
    border-radius: 0px;
    width: 215px;
    &:hover {
      background: #FBFBFB;
      border: 1px dashed #C4C4C4;
    }
  }
  .content {
    padding: 10px 40px;
    font-size: 12px;
  }
  .tags {
    display: block;
    .tag {
      font-size: 13px;
      > span {
        max-width: 255px;
        text-overflow: ellipsis;
        overflow: hidden;
      }
    }
  }
}

.control {
  .select {
    width: 100%;
    select {
      width: 100%;
    }
  }
}

.label {
  font-weight: normal;
  font-size: 16px;
}

.field.file {
  font-size: 14px;
  label {
    margin-bottom: 0;
    line-height: 38px;
  }
  .file-name {
    height: 39px;
    line-height: 16px;
    padding: 10px;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    max-width: none;
  }
  .button {
    height: 39px;
    padding: 10px;
    margin-left: 20px;
    .icon-upload {
      margin: 0 10px;
    }
    .icon-download {
      margin: 0 10px;
    }
  }
  &.has-addons .control .button {
    border-top-left-radius: $radius;
    border-bottom-left-radius: $radius;
  }
}


.field-testimonial {
  position: relative;
  .icon-information {
    cursor: pointer;
    position: absolute;
    right: 16px;
    top: -30px;
  }
  .information-text {
    background-color: #F9F9F9;
    position: absolute;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 14px;
    width: 250px;
    left: 60px;
    top: -30px;
    .triangle {
      top: 20px;
      left: -25px;
      width: 0;
    	height: 0;
      position: absolute;
    	border-top: 20px solid transparent;
    	border-right: 25px solid #F9F9F9 ;
    	border-bottom: 20px solid transparent;
    }
  }
}

.general-error {
  margin-top: 50px;
  > .media > .media-content > p {
    font-size: 14px;
  }
}

.payment-method {
  margin: 50px 0;
  background-color: #f8f8f8;
  border: #f8f8f8;
  border-radius: 8px;
  padding: 30px 0px;
  .b-checkbox.checkbox[disabled] {
    border-color: $grey-light;
    background: none;
    opacity: 1;
  }
  .tab-content {
    padding: 0 16px;
  }
  .tabs.is-toggle {
    padding: 0;
    margin-bottom: 14px;
    ul {
      display: flex;
      justify-content: space-around;
      margin: 0;
      border: 0;
      li {
        margin: 0;
        .amount-text {
          font-size: 13px;
        }
        .text-bank-account {
          position: relative;
          top: -10px;
          left: 10px;
        }
        a, a:hover, a:visited, a:active {
          background: none;
          color: $grey-darker;
          border-radius: $radius;
          padding: 15px;
          margin: 5px 7px;
        };
        &.is-active {
          a, a:hover, a:visited, a:active {
            border: $primary;
            box-shadow: 0px 0px 8px $primary;
            background: $white;
          };
        }
      }
    }
  }
  .button {
    padding: 0 103px;
    color: $white;
    font-weight: bold;
    font-size: 20px;
    &.is-loading:after {
      border-color: transparent transparent white white !important;
    }
    &:hover {
      background: $yellow-darker;
      color: $white;
    }
  }
}
</style>
