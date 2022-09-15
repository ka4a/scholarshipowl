<template>
  <div class="campusexplorer" id="campusexplorer1440171267240">
    <h2>Get Your Degree!</h2>
    <p>Find schools and get information on the program that&rsquo;s right for you.</p>
    <p>Powered by <a href="http://www.campusexplorer.com/?s=sa-283919C2" rel="nofollow">Campus Explorer</a></p>
    <script-injector id="ce-widget-js" source="//widget.campusexplorer.com/js/widget.js" :async="false" />
  </div>
</template>

<script>
  import ScriptInjector from "components/Common/ScriptInjector.vue";

  export default {
    components: {
      ScriptInjector
    },
    created() {
      window.CampusExplorerQ = window.CampusExplorerQ || [];

      window.CampusExplorerQ.push({
          element: "campusexplorer1440171267240",
          source: "sa-283919C2"
      });
    }
  }
</script>

<style lang="scss">
  .campusexplorer {
    background-color: #708fe7;

    h2 {
      font-size: 1.5em;
      margin-top: 0.83em;
      margin-bottom: 0.83em;
      font-weight: bold;
    }

    p {
      line-height: 1.25em;
    }
  }
</style>