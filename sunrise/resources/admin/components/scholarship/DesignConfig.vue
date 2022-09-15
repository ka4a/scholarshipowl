<template>
  <section class="scholarship-design">
    <p class="info-block">
      We will provide a web page for you so applicant could easily apply to the scholarship.
    </p>

    <h3 class="title has-barline">
      Domain
    </h3>

    <c-field
      horizontal
      label="Domain"
      class="field-domain"
      :type="errors.has('domain') ? 'is-danger' : null"
      :message="errors.first('domain')">
      <p>
        <b-input
          name="domain"
          v-model="website.domain"
          v-validate="'required|max:255'"
        />
        <strong>.scholarship.app</strong>
      </p>
    </c-field>

    <h3 class="title has-barline">
      Template
    </h3>

    <div class="columns columns-template">

      <section class="column is-8">

        <p class="help is-danger" v-if="errors.has('layout') || errors.has('variant')">
          Please select landing page layout.
        </p>

        <ul class="templates-list">
          <li class="media-template" v-for="(template, index) in templates"
            :class="{ 'is-selected': index === selected }">
            <div class="media-header">
              <label>{{ template.title }}</label>
            </div>
            <div class="media-body has-text-centered">
              <figure class="image is-138x132" @click="previewImage = template.imageBig">
                <img :src="template.imageSmall" />
                <div class="colors">
                  <div class="color" :style="{'background-color': template.colors[0]}" ></div>
                  <div class="color" :style="{'background-color': template.colors[1]}" ></div>
                  <div class="color" :style="{'background-color': template.colors[2]}" ></div>
                </div>
              </figure>
              <button class="button" @click="selectTemplate(index)">
                {{ selected === index ? 'Selected' : 'Select' }}
                <IconSelected v-if="selected === index" class="icon"/>
              </button>
            </div>
            <b-modal :active="previewImage" @close="previewImage = null">
              <figure class="image">
                <img :src="previewImage" />
              </figure>
            </b-modal>
          </li>
        </ul>

      </section>

      <aside class="column">
        <h3 class="title">Page content</h3>
        <field-switch v-model="switcher.companyName" title="Company name or logo">
          <b-field :type="errors.has('companyName') ? 'is-danger' : null" :message="errors.first('companyName')">
            <template v-if="logoUpload">
              <div v-if="(website.logo && website.logo.links) || logoPreview" class="logo-preview">
                <img v-if="logoPreview" :src="logoPreview" width="200" height="50" />
                <img v-else :src="website.logo.links.url" width="200" height="50" />
                <!-- <button class="button is-light-blue is-round is-pencil" @click="editLogo">
                  <c-icon icon="pencil" />
                </button> -->
                <button class="button is-primary is-round is-trash" @click="clearPreview">
                  <c-icon icon="trash" />
                </button>
              </div>
              <b-upload
                v-else
                v-model="logoFile"
                @input="loadPreview"
                class="is-default"
                v-validate="'required'"
                data-vv-as="logo file"
                data-vv-name="companyName"
                drag-drop
              >
                <c-icon icon="add" />
                <span>Upload logo</span>
              </b-upload>
            </template>
            <b-input
              v-else
              name="companyName"
              placeholder="Company name"
              v-model="website.companyName"
              v-validate="'required|max:255'"
              data-vv-as="company name"
            />
          </b-field>
          <a class="link is-bottom-dotted" v-if="logoUpload" @click="logoUpload = false">Don't have logo?</a>
          <a class="link is-bottom-dotted" v-else @click="logoUpload = true">Add logo</a>
        </field-switch>

        <field-switch v-model="switcher.title"
          :message="errors.first('title')"
          title="Headline"
          >
          <b-input
            name="title"
            v-model="website.title"
            v-validate="'required|max:255'"
          />
        </field-switch>

        <field-switch v-model="switcher.intro"
          :message="errors.first('intro')"
          title="Intro"
          >
          <quill-editor
            v-model="website.intro"
            v-validate="'required|max:255'"
            data-vv-name="intro"
            :options="editorOptions" />
        </field-switch>

        <b-field class="is-pulled-right mt-20">
          <template v-if="$route.params.isNewScholarship">
            <button class="button is-rounded is-primary" @click="save">
              <span>Save & Continue</span>
              <c-icon icon="arrow-right" :class="{ 'is-loading': loading }" />
            </button>
          </template>
          <template v-else>
            <button class="button is-rounded is-primary" @click="save">
              <c-icon icon="check-circle" />
              <span>Save</span>
            </button>
          </template>
        </b-field>

      </aside>
    </div>
  </section>
</template>
<script>
import IconSelected from 'icon/selected.vue';
import FieldSwitch from 'components/common/field-switch';

export default {
  components: {
    IconSelected,
    FieldSwitch,
  },
  data() {
    return {

      previewImage: null,
      selected: null,

      switcher: {
        companyName: false,
        title: false,
        intro: false,
      },

      editorOptions: {
        modules: {
          toolbar: [
            [{ header: [2, 3, 4, 5, false]}],
            ['bold', 'italic', 'underline'],
          ]
        }
      },

      /**
       * Does user wants to upload company logo instead of text
       */
      logoUpload: false,
      logoPreview: null,
      logoFile: null,

    }
  },
  computed: {
    loading: ({ $store }) => $store.getters['organisation/scholarshipSettings/website/loading'],
    website: ({ $store }) => $store.getters['organisation/scholarshipSettings/website/item'],
    templates: ({ $store }) => $store.state.templates.list,
  },
  methods: {
    clearPreview() {
      this.logoFile = null;
      this.logoPreview = null;
      this.website.logo = null;
    },
    loadPreview(logoFile) {
      if (logoFile && logoFile[0]) {
        const reader = new FileReader();
        reader.onload = () => this.logoPreview = reader.result;
        reader.readAsDataURL(logoFile[0]);
      }
    },
    selectTemplate(index) {
      const layout = this.templates[index].layout;
      const variant = this.templates[index].variant;

      this.selected = index;
      this.website.layout = layout;
      this.website.variant = variant;
    },
    save() {
      this.$validator.validateAll()
        .then(result => {
          if (result) {
            const exclude = Object.keys(this.switcher).filter(field => !this.switcher[field]);

            let form = new FormData();
            Object.keys(this.website.getAttributes()).forEach((attribute) => {
              if (exclude.indexOf(attribute) === -1 && this.website[attribute] !== null) {
                form.append(`data[attributes][${attribute}]`, this.website[attribute]);
              } else {
                form.append(`data[attributes][${attribute}]`, '');
              }
            })
            Object.keys(this.website.getRelationships()).forEach((relation) => {
              const data = this.website[relation];
              if (data && data.id && data._type) {
                form.append(`data[relationships][${relation}][data][id]`, data.id);
                form.append(`data[relationships][${relation}][data][type]`, data._type);
              }

              if (relation === 'logo') {
                if (this.switcher['companyName']) {
                  if (this.logoUpload && this.logoFile && this.logoFile.length) {
                    form.append(`data[relationships][logo][data]`, this.logoFile[0]);
                  } else {
                    form.append(`data[relationships][logo][data]`, '');
                  }
                } else {
                  form.append(`data[relationships][logo][data]`, '');
                }
              }
            })

            this.$store.dispatch('organisation/scholarshipSettings/website/updateConfig', {
              loadUrl: `/api/scholarship_template/${this.$route.params.id}/website`,
            });
            this.$store.dispatch('organisation/scholarshipSettings/website/save', { form })
              .then((website) => {
                const scholarship = this.$store.state.organisation.scholarshipSettings.item;
                scholarship.website = website;
                this.logoPreview = null;
                this.$store.dispatch('organisation/scholarshipSettings/setItem', scholarship);
                this.$toast.open({ type: 'is-success', message: 'Scholarship design updated.' });
                this.$emit('saved', website);
              })
              .catch((rsp) => {
                this.$scrollTo(document.querySelector('.help.is-danger'));
                if (rsp && rsp.status === 422) {
                  this.JSONAPIparseErrors(rsp.data, this.$validator);
                }
              });
          }
        })
    }
  },
  watch: {
    website: {
      immediate: true,
      handler: function(website) {
        if (website) {
          Object.keys(this.switcher).forEach(field => {
            this.switcher[field] = !!website[field];
          })

          if (website.logo) {
            this.switcher['companyName'] = true;
            this.logoUpload = true;
          }

          this.templates.forEach((t, index) => {
            if (t.layout === website.layout && t.variant === website.variant) {
              this.selected = index;
            }
          });
        }
      }
    }
  },
}
</script>
<style lang="scss">
@import "~scss/variables.scss";

.scholarship-design {
  font-size: 16px;

  h3 {
    margin-bottom: 25px;
  }

  .title {
    font-size: 18px;
    font-weight: normal;
  }

  .templates-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .logo-preview {
    position: relative;
    border: 1px solid #CCD6E6;
    display: flex;
    box-sizing: border-box;
    border-radius: 5px;
    padding: 2px;
    width: 100%;
    height: 100px;

    img {
      width: 200px;
      height: 50px;
      margin: auto;
    }
    .button {
      position: absolute;
      z-index: 2;
      top: 10px;
      &.is-pencil {
        right: 45px;
      }
      &.is-trash {
        right: 10px;
      }
    }
  }

  .media-template {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    margin-bottom: 20px;

    .media-header {
      width: 100%;
      text-align: center;
      padding: 7px 10px;
      label {
        color: $grey-light;
      }
    }
    .media-body {
      border: 1px solid transparent;
      background-color: $grey-background;
      padding: 22px;

      .button {
        margin-top: 22.5px;
        background: #FFFFFF;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        border-radius: 18px;

        font-size: 14px;
        text-align: center;
        color: #656565;

        .icon {
          margin: 4px;
          width: 14px;
          height: 14px;
        }
      }
    }

    .image.is-138x132 {
      position: relative;
      width: 138px;
      height: 132px;
      cursor: pointer;

      img {
        width: 138px;
        height: 132px;
      }

      .colors {
        position: absolute;
        bottom: -10px;
        left: 44px;
        z-index: 1;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 50px;
        height: 20px;
        border-radius: 10px;
        background-color: $white;
        .color {
          border-radius: 50%;
          background-color: red;
          width: 8px;
          height: 8px;
          margin: 0 2.5px;
        }
      }
    }

    &.is-selected {
      .media-header {
        label {
          font-weight: bold;
        }
      }
      .media-body {
        background: #F8DEDE;
      }
    }
  }

  .field-domain {
    .control {
      display: inline-block;
      width: 300px;
    }
    strong {
      position: absolute;
      margin-left: 10px;
      font-size: 16px;
      line-height: $control-height;
    }
  }

  .columns-template {
    margin: 0;
    // margin-top: 23px;
    // border-top: 1px solid #c4c4c4;

    section.column {
      padding: 20px 20px 20px 0;
    }

    aside.column {
      // border-left: 1px solid #c4c4c4;
      padding: 20px 0 20px 20px;
    }
  }
}
</style>
