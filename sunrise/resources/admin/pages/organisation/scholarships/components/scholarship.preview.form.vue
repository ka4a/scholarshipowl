<template>
  <form v-show="false" ref="form" method="post" action="/preview" target="preview-website">

    <input name="id" :value="scholarship.id" />
    <input name="title" :value="scholarship.title" />
    <input name="description" :value="scholarship.description" />
    <input name="start" :value="scholarship.start" />
    <input name="deadline" :value="scholarship.deadline" />
    <input name="timezone" :value="scholarship.timezone" />
    <input name="amount" :value="scholarship.amount" />
    <input name="awards" :value="scholarship.awards" />
    <input name="recurringType" :value="scholarship.recurringType" />
    <input name="recurringValue" :value="scholarship.recurringValue" />
    <input name="expiredAt" :value="scholarship.expiredAt" />
    <input name="next" :value="scholarship.deadline"/>
    <input name="type" value="scholarship" />

    <input name="content[id]" value="" />
    <input name="content[updatedAt]" value="" />
    <input name="content[createdAt]" value="" />
    <input name="content[privacyPolicy]" value="" />
    <input name="content[rules]" value="" />
    <input name="content[termsOfUse]" value="" />
    <input name="content[type]" value="scholarship_content" />

    <!-- <input name="website[id]" value="2" />
    <input name="website[type]" value="scholarship_website" /> -->
    <input name="website[domain]" value="website.domain" />
    <input name="website[gtm]" :value="website.gtm" />
    <input name="website[title]" :value="website.title" />
    <input name="website[companyName]" :value="website.companyName" />
    <input name="website[intro]" :value="website.intro" />
    <input name="website[layout]" :value="website.layout" />
    <input name="website[variant]" :value="website.variant" />

  </form>
</template>
<script>
export default {
  name: 'FormPreview',
  props: {
    scholarship: {
      type: Object,
      required: true,
    },
  },
  computed: {
    website: ({ $store }) => $store.state.organisation.scholarshipSettings.website.item,
  },
  methods: {
    open() {
      this.$refs.form.submit();
    }
  }
}
</script>
