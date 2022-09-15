<template>
  <div>
    <b-loading v-if="loading" active :is-full-page="false" />
    <div v-else>
      <p class="info-block">
        Scholarship legal documents content configurations.
      </p>
      <ul class="content-list">
        <li v-for="content in contentList" :key="content.type">
          <b-modal
            class="content-editor-modal"
            :active="editContentType === content.type"
            :can-cancel="[]">
            <div class="box">
              <i class="boxclose" @click="editContentType = null" />
              <content-edit
                :label="content.title"
                :content-id="editContentTypeId"
                :is-pdf="editContentType === 'affidavit'"
              />
            </div>
          </b-modal>
          <button class="button is-grey is-round is-pulled-right" @click="editContentType = content.type">
            <c-icon icon="pencil" />
          </button>
          <h3 class="title is-3">{{ content.title }}</h3>
          <h4 class="subtitle is-4">{{ content.description }}</h4>
        </li>
      </ul>
      <b-field class="is-pulled-right mt-20">
        <button v-if="!(scholarship.published && scholarship.published.id)"
           class="button is-primary is-rounded" @click="openOrganisationSetup">
          <span>Publish</span>
        </button>
      </b-field>
    </div>
  </div>
</template>
<script>
import queryString from 'qs';
import { prepareQueryString } from 'lib/store/utils';
import jsona from 'lib/jsona';

import ContentEdit from 'components/scholarship/ScholarshipTemplateContentEdit';

export default {
  name: 'ScholarshipSettingLegal',
  components: {
    ContentEdit
  },
  created() {
    const qs = queryString.stringify({ fields: { scholarship_template_content: 'type' }}, { arrayFormat: 'index' });
    this.$http.get(`/api/scholarship_template/${this.$route.params.id}/content?${qs}`)
      .then(({ data }) => this.contentRelationships = jsona.deserialize(data))
      .finally(() => this.loading = false);
  },
  data() {
    return {
      loading: true,
      contentRelationships: null,
      editContentType: null,

      contentList: [
        {
          type: 'termsOfUse',
          title: 'Terms Of Use',
          description: 'Scholarship terms of use and sweapstakes rules.'
        },
        {
          type: 'privacyPolicy',
          title: 'Privacy Policy',
          description: 'Scholarship privacy policy.'
        },
        {
          type: 'affidavit',
          title: 'Affidavit',
          description: 'Winner must sign the affidavit before receiving the award.'
        }
      ]
    }
  },
  methods: {
    openOrganisationSetup() {
      this.$store.dispatch('modals/open', 'organisationSetup')
        .then(() => {
          this.$store.dispatch('organisation/scholarshipSettings/publish')
            .then(scholarship => {
              this.$toast.open({ type: 'is-success', message: 'Scholarship was published' });
              this.$router.push({ name: 'scholarships.published.show', params: { id: scholarship.id } });
            });
        })
        .catch(e => e);
    },
  },
  computed: {
    loadingScholarship: ({ $store }) => $store.getters['organisation/scholarshipSettings/loading'],
    scholarship: ({ $store }) => $store.getters['organisation/scholarshipSettings/item'],
    editContentTypeId: ({ contentRelationships, editContentType }) => {
      if (!contentRelationships || !editContentType) {
        return null;
      }

      return contentRelationships.filter((c) => c.type === editContentType)[0].id;
    },
  }
}
</script>
<style lang="scss" scoped>
ul.content-list {
  > li {
    border: 1px solid #C8C8C8;
    border-radius: 5px;
    padding: 14px;
    &:not(:last-child) {
      margin-bottom: 20px;
    }
    > .title {
      font-size: 18px;
      padding-top: 3px;
      padding-left: 14px;
    }
    > .subtitle {
      font-size: 14px;
      padding-left: 14px;
    }
    > .button {
      width: 24px;
      height: 24px;
      /deep/ .icon svg {
        width: 10px;
        height: 10px;
      }
    }
  }
}
.content-editor-modal {
  .box {
    padding: 0;
    border-radius: 10px;
  }
  /deep/ .modal-content {
    width: auto;
    border-radius: 10px;
    .quill-editor {
      .ql-editor {
        height: calc(100vh - 420px);
      }
    }
  }
}
.urls-list {
  margin-top: 20px;
}
</style>
