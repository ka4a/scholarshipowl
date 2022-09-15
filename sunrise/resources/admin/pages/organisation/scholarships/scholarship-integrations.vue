<template>
  <div class="page">
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <section class="container">
      <div class="block">
        <h2 class="title">Use &lt;iframe&gt;</h2>
        <b-loading :is-full-page="false" :active="loading" />
        <c-field label="Create iframe ID" />
        <b-field class="is-generator">
          <b-input :value="iframe ? iframe.id : null" type="text" placeholder="Click 'Generate' to get HTML code..." readonly />
          <button class="button is-primary is-rounded" @click="save">
            <span v-if="iframe && iframe.id">Update</span>
            <span v-else>Generate</span>
          </button>
        </b-field>
        <b-field label="<iframe> width and height" />
        <b-field>
          <b-radio v-model="changeSizes" native-value="0">
            <span>Leave automatic (iframe resized automaticaly to fit form size)</span>
          </b-radio>
        </b-field>
        <b-field>
          <b-radio v-model="changeSizes" native-value="1">
            <span>Set specific sizes</span>
          </b-radio>
        </b-field>
        <div v-if="changeSizes === '1'" class="sizes-container">
          <c-field tip="You can put any CSS size here. Example: 100%, 100px." label="Width" >
            <b-input v-model="iframe.width" type="text" />
          </c-field>
          <c-field tip="You can leave with empty so it will be automaticaly updated." label="Height" >
            <b-input v-model="iframe.height" type="text" />
          </c-field>
        </div>
        <b-field label="<iframe> code" />
        <p>Insert this code into your website HTML in place where you want to see application form</p>
        <p v-if="iframe && iframe.id">
          <pre class="ifrm-code" v-text="buildCode()" />
        </p>
        <p v-else>
          <pre class="no-code">
            <svg width="51" height="39" viewBox="0 0 51 39" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M31.7989 0.883098L30.0706 0.409282C29.8481 0.335096 29.629 0.358426 29.4159 0.479076C29.2021 0.599824 29.0577 0.781093 28.9835 1.02259L18.5883 37.0017C18.5141 37.2433 18.5373 37.4712 18.6581 37.6845C18.7788 37.8983 18.9597 38.0423 19.2016 38.1165L20.9294 38.5905C21.1524 38.6653 21.3707 38.6418 21.5844 38.521C21.7982 38.3997 21.9423 38.219 22.0164 37.9779L32.4119 1.99814C32.486 1.75664 32.463 1.52881 32.342 1.31494C32.2212 1.10117 32.0405 0.957187 31.7989 0.883098Z" fill="#CCD6E6"/>
              <path d="M16.2202 7.90646C16.2202 7.66496 16.1271 7.45109 15.9414 7.26543L14.5477 5.8719C14.362 5.68614 14.1482 5.59302 13.9067 5.59302C13.6652 5.59302 13.4514 5.68624 13.2657 5.8719L0.278785 18.8588C0.0927329 19.0445 0 19.2583 0 19.4998C0 19.7413 0.0930258 19.9551 0.278785 20.1407L13.2656 33.1276C13.4513 33.3137 13.6647 33.4062 13.9066 33.4062C14.1485 33.4062 14.362 33.3134 14.5477 33.1276L15.9413 31.7349C16.127 31.5492 16.2201 31.3353 16.2201 31.0936C16.2201 30.8523 16.127 30.6386 15.9413 30.4529L4.98874 19.4998L15.9414 8.54748C16.1274 8.36182 16.2202 8.14795 16.2202 7.90646Z" fill="#CCD6E6"/>
              <path d="M50.7215 18.8585L37.7343 5.87166C37.5486 5.68599 37.3348 5.59277 37.0937 5.59277C36.8518 5.59277 36.6387 5.68599 36.4523 5.87166L35.0593 7.26509C34.8736 7.45084 34.781 7.66433 34.781 7.90611C34.781 8.1479 34.8735 8.36148 35.0593 8.54714L46.0121 19.4998L35.0593 30.453C34.8736 30.6386 34.781 30.8525 34.781 31.0936C34.781 31.3355 34.8735 31.5493 35.0593 31.7349L36.4523 33.1277C36.6387 33.3137 36.8519 33.4063 37.0937 33.4063C37.3349 33.4063 37.5486 33.3134 37.7343 33.1277L50.7215 20.1409C50.9073 19.9552 51.0001 19.7411 51.0001 19.4996C51.0001 19.258 50.9073 19.0442 50.7215 18.8585Z" fill="#CCD6E6"/>
            </svg>
            <h3 class="no-code--title">Code is not generated yet</h3>
            <h4 class="no-code--subtitle">Please click on "Generate" to get the iframe code.</h4>
          </pre>
        </p>
      </div>
    </section>
  </div>
</template>
<script>
import IframeDetails from 'components/scholarship/IframeDetails';
import { emptyIframe } from 'store/scholarships/integrations';

export default {
  name: 'ScholarshipIntegration',
  components: {
    IframeDetails,
  },
  created() {
    // Load scholarships iframes
    this.$store.dispatch('scholarships/integrations/load', this.$route.params.id)
      .then((template) => {
        if (template.iframes.length === 0) {
          template.iframes.push(emptyIframe(template.id));
        }
      });
  },
  watch: {
    iframe: {
      deep: true,
      immediate: true,
      handler(iframe) {
        if (iframe && iframe.width !== '100%' && iframe.height !== '') {
          this.changeSizes = '1';
        }
      }
    }
  },
  computed: {
    breadcrumbs() {
     return {
        'Scholarships': { name: 'scholarships' },
        [this.template.title]: {
           name: 'scholarships.show',
           params: { id: this.$route.params.id }
        },
        'Integrations': {
           name: 'scholarships.integrations',
           params: { id: this.$route.params.id }
        },
      }
    },
    template() {
      return this.$store.getters['scholarships/integrations/item'];
    },
    iframe() {
      return this.template.iframes && this.template.iframes.length ? this.template.iframes[0] : null;
    },
    loading() {
      return this.$store.getters['scholarships/integrations/loading'] ||
        this.$store.getters['organisation/scholarshipSettings/iframes/loading'];
    }
  },
  data() {
    return {
      changeSizes: '0',
    }
  },
  methods: {
    buildCode() {
      return this.iframe && this.iframe.links ?
        `<script id='${this.iframe.id}' src='${this.iframe.links.src}'><\/script>` : null;
    },
    save() {
      const action = this.iframe && this.iframe.id ? 'update' : 'create';
      this.$store.dispatch(`organisation/scholarshipSettings/iframes/${action}`, this.iframe)
        .then(() => {
          this.$toast.open({ message: action === 'update' ? 'Iframe updated' : 'Iframe created', type: 'is-success' });
          this.$store.dispatch('scholarships/integrations/load', this.$route.params.id);
        })
        .catch((err) => {
          if (err.response && err.response.status === 422) {
            this.JSONAPIparseErrors(err.response.data, this.$validator);
          }
          this.$nextTick(() => this.$scrollTo(document.querySelector('.help.is-danger')));
        });
    },
  }
}
</script>
<style lang="scss" scoped>
/deep/.field.is-generator {
  justify-content: space-between;
  .control {
    width: 100%;
  }
  .button {
    width: 160px;
    margin-left: -15px;
    z-index: 5;
  }
}
.sizes-container {
  display: flex;
  padding-left: 30px;
  /deep/ .field + .field {
    margin-left: 15px;
  }
}
.ifrm-code {
  font-family: 'PT Mono', monospace;
  white-space: pre-wrap;
}
.no-code {
  font-family: 'PT Mono', monospace;
  white-space: pre-line;
  text-align: center;
  &--title {
    font-size: 16px;
    font-weight: bold;
    line-height: 22px;
    color: #8E97A4;
  }
  &--subtitle {
    font-size: 13px;
    line-height: 18px;
    color: #8E97A4;
  }
}
</style>
