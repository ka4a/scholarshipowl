<script>
import { mapMutations, mapState, mapGetters } from "vuex";
import { debounce } from "lodash";
import ResizeSensor  from "lib/ResizeSensor";
import { ROUTES } from "router.js";

import Item from "./Item.vue";
import ListBanners from "./../ListBanners.vue";

const scholarshipsFormater = function(createElement, context) {
  let masterList = [];

  for(let i = 0; i < context.scholarships.length; i += 1) {
    let scholarship = context.scholarships[i];

    let itemProps = {
      item: scholarship,
      selected: scholarship.scholarshipId === context.selected.scholarshipId
    };

    masterList.push(createElement(
      "item",
      {
        key: scholarship.scholarshipId,
        nativeOn: { click: function() { context.setScholarship(scholarship); }},
        on: { 'global': ev => context.$emit('global', {ev: 'item-state-change'}) },
        props: itemProps
      })
    );

    if (!/^((?!chrome|android).)*safari/i.test(navigator.userAgent) && context.freemium && i % 2 === 1) {
      masterList.push(createElement(
        ListBanners,
        { props: { width: context.parentMetric.width }}
      ));
    }
  }

  return masterList;
};

export default {
  render: function(createElement) {
    return createElement(
      "div",
      {"class": {"scholarships-list": true},
        ref : "list",
        on : { scroll: debounce(this.showBanners, 300)}},
      [createElement("div", scholarshipsFormater(createElement, this))]
    );
  },
  mounted() {
    this.parentMetric = this.$refs.list.getBoundingClientRect();
    new ResizeSensor(
      this.$refs.list,
      debounce(() => {
        if(this.$refs.list) {
          this.parentMetric = this.$refs.list.getBoundingClientRect();
        }
      }, 300)
    );
    this.showBanners();
  },
  data: function() {
    return {
      parentMetric: {
        width: 320 // initial size
      },
    };
  },
  props: {
    scholarships: { required: true, type: Array }
  },
  components: {
    Item,
    ListBanners,
  },
  computed: {
    ...mapState({
      selected: state => state.scholarships.selected
    }),
    ...mapGetters({
      freemium: "account/isFreemium"
    })
  },
  methods: {
    showBanners() {
      this.$children.forEach(item => {
        if(item.$el.classList.contains("master-list-banners")) {
          let itemMetric = item.$el.getBoundingClientRect();
          if(itemMetric.top > this.parentMetric.top &&
            itemMetric.top < (this.parentMetric.bottom + this.parentMetric.height / 2)) {
            item.inViewport = true;
          }
        }
      });
    },
    setScholarship(scholarship) {
      this.SET_SCHOLARSHIP(scholarship);
      this.$router.push(ROUTES.SCHOLARSHIPS);
      this.$emit("selected", scholarship);
    },
    ...mapMutations("scholarships", [
      "SET_SCHOLARSHIP"
    ])
  },
};
</script>

<style lang="scss">
@import 'main/meta/flex-box';

  .scholarships-list {
    @include flexbox();
    @include flex-direction(column);
    @include flex(1);
    overflow: auto;
    min-height: 0px;
    // -webkit-overflow-scrolling: touch;
    overflow-x: hidden;
  }
</style>
