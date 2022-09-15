<template>
  <div class="winner">

    <winner-header :winner="winner">
      <template slot="left">
        <b-upload v-if="edit" v-model="photoFile" @input="previewPhoto">
          <figure class="photo-upload image is-80x80">
            <img v-if="photoPreview" :src="photoPreview" />
            <icon-upload v-else class="icon icon-upload" />
          </figure>
        </b-upload>
        <a v-else @click="winner.photo ? downloadApplicationFile(winner.photo.id, winner.photo.name) : null">
          <winner-photo :photo="winner.photoSmall || winner.photo"/>
        </a>
      </template>
    </winner-header>

    <div class="winner-info">

        <div class="winner-info_group winner-details">
          <h3 class="subtitle">Advanced Details</h3>

          <b-field label="Name" horizontal
            :type="error['data.attributes.name'] ? 'is-danger':undefined"
            :message="error['data.attributes.name'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.name" type="text" />
          </b-field>

          <b-field label="Email" horizontal
            :type="error['data.attributes.email'] ? 'is-danger':undefined"
            :message="error['data.attributes.email'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.email" type="text" />
          </b-field>

          <b-field label="Phone" horizontal
            :type="error['data.attributes.phone'] ? 'is-danger':undefined"
            :message="error['data.attributes.phone'] || undefined">
            <div class="control is-small" :class="{ 'has-icons-right': error['data.attributes.phone'] }">
              <masked-input
                class="input"
                :class="{ 'is-danger': error['data.attributes.phone'] }"
                v-model="phone"
                @input="winner.phone = arguments[1]"
                mask="\+\1 (111) 111-1111"
                placeholder="+1 (XXX) XXX-XXXX"
                :readonly="!edit"
                type="text" />
              <b-icon class="icon is-right has-text-danger" v-if="error['data.attributes.phone']" icon="exclamation-circle" />
            </div>
          </b-field>

          <b-field label="Date Of Birth" horizontal
            :type="error['data.attributes.dateOfBirth'] ? 'is-danger':undefined"
            :message="error['data.attributes.dateOfBirth'] || undefined">
            <datepicker
              size="is-small"
              v-model="dateOfBirth"
              placeholder="Click to select..."
              :mask="true"
              :error="!!error['data.attributes.dateOfBirth']"
              :disabled="!edit"
              :readonly="!edit" />
          </b-field>

          </br>

          <b-field label="City" horizontal
            :type="error['data.relationships.city'] ? 'is-danger':undefined"
            :message="error['data.relationships.city'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.city" type="text" />
          </b-field>

          <b-field label="State" horizontal
            :type="error['data.relationships.state.data'] ? 'is-danger':undefined"
            :message="error['data.relationships.state.data'] || undefined">
            <b-select class="is-small" :class="{ disabled: !edit }" :disabled="!edit" v-model="state" placeholder="Select state">
              <option v-for="(state, id) in states" :value="id" :key="id">
                ({{ state.abbreviation }}) {{ state.name }}
              </option>
            </b-select>
          </b-field>

          <b-field label="Zip code" horizontal
            :type="error['data.attributes.zip'] ? 'is-danger':undefined"
            :message="error['data.attributes.zip'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.zip" type="text" />
          </b-field>

          <b-field label="Address" horizontal
            :type="error['data.attributes.address'] ? 'is-danger':undefined"
            :message="error['data.attributes.address'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.address" type="text" />
          </b-field>
          <b-field horizontal
            :type="error['data.attributes.address2'] ? 'is-danger':undefined"
            :message="error['data.attributes.address2'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.address2" type="text" />
          </b-field>

        </div>

        <div class="winner-info_group payment-details is-clearfix">
          <h3 class="subtitle">Payments</h3>

          <b-field label="Paypal" horizontal
            :type="error['data.attributes.paypal'] ? 'is-danger':undefined"
            :message="error['data.attributes.paypal'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.paypal" type="text" />
          </b-field>

          <b-field label="Bank name" horizontal
            :type="error['data.attributes.bankName'] ? 'is-danger':undefined"
            :message="error['data.attributes.bankName'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.bankName" type="text" />
          </b-field>

          <b-field label="Name of account" horizontal
            :type="error['data.attributes.nameOfAccount'] ? 'is-danger':undefined"
            :message="error['data.attributes.nameOfAccount'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.nameOfAccount" type="text" />
          </b-field>

          <b-field label="Routing number" horizontal
            :type="error['data.attributes.routingNumber'] ? 'is-danger':undefined"
            :message="error['data.attributes.routingNumber'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.routingNumber" type="text" />
          </b-field>

          <b-field label="Account number" horizontal
            :type="error['data.attributes.accountNumber'] ? 'is-danger':undefined"
            :message="error['data.attributes.accountNumber'] || undefined">
            <b-input :readonly="!edit" size="is-small" v-model="winner.accountNumber" type="text" />
          </b-field>
        </div>

        <div class="winner-info_group">
          <h3 class="subtitle">Affidavit</h3>
          <div class="affidavit-files">
            <div class="tag is-link" v-for="(file, index) in affidavitFile" :key="file.id">
              <a @click="downloadApplicationFile(file.id, file.name)">
                <icon-file />
                <span>{{ file.name }}</span>
              </a>
              <button v-if="edit"  class="delete is-small" @click="affidavitFile.splice(index, 1)"></button>
            </div>
          </div>
          <b-field
            :type="error['data.relationships.affidavit.data'] ? 'is-danger':undefined"
            :message="error['data.relationships.affidavit.data'] || undefined">
            <b-upload v-if="edit" v-model="affidavitFile" multiple drag-drop>
              <section class="section">
                <div class="content has-text-centered">
                    <p>
                        <icon-plus />
                    </p>
                    <p>Drop your files here or click to upload</p>
                </div>
              </section>
            </b-upload>
          </b-field>
        </div>

    </div>

    <div class="winner-info">
      <div class="winner-info_group winner-testimonial is-clearfix">
        <h3 class="subtitle">Testimonial</h3>
        <b-field
            :type="error['data.attributes.testimonial'] ? 'is-danger':undefined"
            :message="error['data.attributes.testimonial'] || undefined">
          <b-input :readonly="!edit" :disabled="!edit" v-model="winner.testimonial" type="textarea" />
        </b-field>
      </div>
    </div>

    <b-field class="winner-actions">
      <button v-if="winner.meta.filled" class="button" @click="publishModal = true">
        <span v-if="!winner.scholarship_winner">Publish</span>
        <span v-else>Published</span>
      </button>

      <a class="button" :href="'/winner-information/' + winner.application.id" target="_blank">
        Details Page
      </a>

      <template v-if="!filled && !edit">
        <button v-if="!winner.paused" class="button tooltip"
          @click="pause(true)"
          data-tooltip="Paused winner will not get notification as well will not be disqualified">
          <b-icon icon="pause-circle" />
          <span>Pause</span>
        </button>
        <button v-else @click="confirmUnpause" class="button is-warning">
          <b-icon icon="pause-circle-outline" />
          <span>Paused</span>
        </button>
      </template>

      <button v-if="!edit" class="button" @click="edit = true">
        <icon-edit class="icon" />
        <span>Edit</span>
      </button>
      <button v-else class="button" :disabled="loading" @click="saveWinner">
        <icon-save class="icon" />
        <span>Save</span>
      </button>
      <button v-if="edit" class="button" @click="cancelSave">
        <icon-delete class="icon"/>
        <span>Cancel</span>
      </button>

    </b-field>
    <div class="is-clearfix"></div>
    <b-modal :active.sync="publishModal">
      <div class="card">
        <header class="modal-card-head">
          <div class="modal-card-title">
            <p>Information to be published on website</p>
          </div>
        </header>
        <div class="modal-card-body">
          <scholarship-winner :winner="winner" @published="reload" />
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
import Vue from 'vue';

import states from 'states.json';

import WinnerHeader from './winner-header.vue';
import WinnerPhoto from './winner-photo.vue';
import ScholarshipWinner from './scholarship-winner.vue';
import IconFile from 'icon/file.vue';
import IconUpload from 'icon/upload.vue';
import IconDownload from 'icon/download.vue';
import IconEdit from 'icon/edit.vue';
import IconSave from 'icon/save.vue';
import IconPlus from 'icon/plus.vue';
import IconCalendar from 'icon/calendar.vue';
import IconDelete from 'icon/delete.vue';
import Datepicker from 'components/datepicker.vue';
import MaskedInput from 'vue-masked-input/src/MaskedInput';

export default {
  props: {
    winner: Object
  },
  components: {
    ScholarshipWinner,
    WinnerHeader,
    WinnerPhoto,
    IconFile,
    IconEdit,
    IconSave,
    IconPlus,
    IconUpload,
    IconDownload,
    IconCalendar,
    IconDelete,
    Datepicker,
    MaskedInput
  },
  data: function() {
    const w = this.winner;

    return {
      states,

      state: null,
      dateOfBirth: null,
      affidavitFile: [],
      photoFile: [],
      photoPreview: null,
      phone: null,

      publishModal: false,
      edit: false,
      loading: false,
      error: {},
    }
  },
  created() {
    if (this.winner) {
      this.initData(this.winner);
    }
  },
  computed: {
    filled: ({ $store }) => $store.state.winners.winnerPage.data.data.meta.filled
  },
  methods: {
    pause(paused) {
      const form = {
        data: {
          attributes: {
            paused
          }
        }
      };

      this.$store.dispatch('winners/winnerPage/save', { form });
    },
    confirmUnpause() {
      this.$dialog.confirm({
        title: 'Unpause winner notifications and disqualify algorithm.',
        message: 'Unpausing may disqualify winner if 72 hours passed after he picked as winner.',
        confirmText: 'Un-pause',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => this.pause(false)
      })
    },
    reload() {
      this.$store.dispatch('winners/winnerPage/load');
    },
    previewPhoto() {
      if (this.photoFile && this.photoFile.length) {
        const reader = new FileReader();
        reader.onload = () => this.photoPreview = reader.result;
        reader.readAsDataURL(this.photoFile[0]);
      }
    },
    initData(w) {
      if (w.dateOfBirth) {
        this.dateOfBirth = new Date(Date.parse(w.dateOfBirth));
      }

      if (w.state) {
        this.state = w.state.id;
      }

      if (w.affidavit && w.affidavit.length) {
        this.affidavitFile = w.affidavit.slice(0);
      }

      if (w.photo) {
        this.photoFile[0] = w.photo;
      }

      if (w.phone) {
        this.phone = w.phone;
      }
    },
    saveWinner() {
      const w = this.winner;
      const form = new FormData();

      this.error = {};
      /* Format date into string 'YYYY-MM-DD' */
      const formatYYYYMMDD = (date) => {
        const year = date.getFullYear()
        const month = date.getMonth() + 1
        const day = date.getDate()
        return year + '-' +
            ((month < 10 ? '0' : '') + month) + '-' +
            ((day < 10 ? '0' : '') + day)
      };


      if (w.name) {
        form.append('data[attributes][name]', w.name);
      }
      if (w.email) {
        form.append('data[attributes][email]', w.email);
      }
      if (w.phone) {
        form.append('data[attributes][phone]', w.phone);
      }

      if (this.dateOfBirth) {
        form.append('data[attributes][dateOfBirth]', formatYYYYMMDD(this.dateOfBirth));
      }

      if (this.state) {
        form.append('data[relationships][state][data][id]', this.state);
        form.append('data[relationships][state][data][type]', 'state');
      }

      if (w.zip) {
        form.append('data[attributes][zip]', w.zip);
      }

      if (w.address) {
        form.append('data[attributes][address]', w.address);
        if (w.address2) {
          form.append('data[attributes][address2]', w.address2);
        }
      }

      if (w.bankName) {
        form.append('data[attributes][bankName]', w.bankName);
      }
      if (w.nameOfAccount) {
        form.append('data[attributes][nameOfAccount]', w.nameOfAccount);
      }
      if (w.accountNumber) {
        form.append('data[attributes][accountNumber]', w.accountNumber);
      }
      if (w.routingNumber) {
        form.append('data[attributes][routingNumber]', w.routingNumber);
      }

      if (w.paypal) {
        form.append('data[attributes][paypal]', w.paypal);
      }

      if (w.testimonial) {
        form.append('data[attributes][testimonial]', w.testimonial);
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
      }

      this.loading = true;
      return this.$store.dispatch('winners/winnerPage/save', { form })
        .then((winner) => {
          this.edit = false;
          this.loading = false;
        })
        .catch((response) => {
          this.loading = false;
          if (response && response.status === 422) {
            if (response.data && response.data.errors) {
              response.data.errors.forEach((err) => {
                if (err.source && err.source.pointer) {
                  Vue.set(this.error, err.source.pointer, err.detail);
                }
              })
            }
          }
        })

    },
    cancelSave() {
      this.edit = false;
      this.$store.dispatch('winners/winnerPage/resetLoaded');
      this.initData(this.winner);
      this.error = {};
    }
  }
}
</script>
<style lang="scss">
.winner {
  h3.subtitle {
    color: #828282;
    margin-top: 20px;
    margin-bottom: 13px;
    font-size: 16px;
    font-weight: 600;
  }
  .winner-info {
    display: inline-block;
    width: 100%;
    margin: 0 -12px;
    &_group {
      display: inline-block;
      float: left;
      padding: 0 12px;
      // &:not(:first-child) {
      //   border-left: 1px solid #E8E8E8;
      //   padding-left: 24px;
      // }
    }
    .field {
      margin: 0 0 5px 0;
      .field-label {
        padding-top: 0;
        margin-right: 0;
        text-align: left;
        display: inline-block;
        .label {
          width: 100px;
          color: #121212;
          font-weight: 600;
          font-size: 14px;
        }
        &:empty {
          min-width: 100px;
        }
      }
      .field-body {
        .help {
          margin-bottom: 0.25rem;
          margin-top: -0.25rem;
        }
      }

      .control {
        .input,
        .textarea,
        .select > select,
        .dropdown .datepicker-input > .input {
          &[readonly], &:disabled, input[readonly] {
            border: 1px solid transparent;
            box-shadow: none;
            background: none;
          }
          &:disabled {
            cursor: text;
          }
        }
      }
    }
    .affidavit-files {
      .tag {
        background: none;
        display: block;
        margin: 8px 0;
        > a {
          > svg {
            float: left;
          }
          > span {
            float: left;
            padding: 3px 15px;
          }
        }
        button.delete {
          background: #575757;
          top: 4px;
        }
      }
    }
  }
  .winner-testimonial {
    max-width: 650px;
    width: 100%;
    .textarea {
      resize: none;
      width: 235px;
      padding: 6px 9px;
    }
  }
  .photo-upload {
    cursor: pointer;
    border-radius: 50%;
    border: 1px solid;
    .icon-upload {
      width: 40px;
      height: 40px;
      margin: 15px 20px 15px;
    }
  }
  .winner-actions {
    justify-content: flex-end;
    > .button {
      margin: 10px;
    }
  }
}
</style>
