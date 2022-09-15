<template lang="html">
  <section class="jobs jobs-wide">
    <h1 class="h2 title_15fz top-30 bottom-25">ScholarshipOwl Job Board</h1>
    <section ref="container" class="jobs-left">
      <div id="zipsearch_container"></div>
    </section>
    <section class="jobs-right top-15">
      <div class="jobs-banner">
        <jobs-banner1 />
      </div>
      <div class="jobs-banner">
        <jobs-banner2 />
      </div>
    </section>
  </section>
</template>

<script>
import { mapGetters }       from "vuex";
import JobsBanner1 from "banners/JobsBanner1.vue";
import JobsBanner2 from "banners/JobsBanner1.vue";

export default {
  components: {
    JobsBanner1,
    JobsBanner2,
  },
  mounted () {

    const city = (this.profile && this.profile.city) ? this.profile.city : "";

    const options = {
      jobs_per_page  : 4,
      container      : "zipsearch_container",
      alerts_api_key : "aumu4y7jkacgrk365hckc7wt2sidyr4y",
      location       : city,
      search         : "Student jobs"
    };

    const script = document.createElement("script");
    script.className = "zip-search-script";
    script.type = "text/javascript";
    script.src  = "https://www.ziprecruiter.com/jobs-widget/pro/v1/53vjrqcrz6iyi37dbdiypnkbsp9kvpy3";
    script.onload = () => {
      window.zipsearch.init(options);

      // prevent form enter submit
      let modalForm = document.querySelector("#zs_modal form input");

      modalForm && modalForm.addEventListener("keypress", function(e) {
        if(e.which === 13) {
          e.preventDefault();

          document.querySelector("#zs_create_alert").click();
        }
      });

      const zsLocation = document.getElementById("zs_location");

      if (zsLocation && !zsLocation.value) {
        this.$http.get("https://freegeoip.net/json/").then((response) => {
          if (response.status === 200 && response.data && response.data.city) {
            zsLocation.value = response.data.city;
          }
        });
      }
    };

    this.$refs["container"].appendChild(script);
  },
  computed: {
    ...mapGetters({
      profile: "account/profile"
    })
  }
};
</script>

<style lang="css">
</style>
