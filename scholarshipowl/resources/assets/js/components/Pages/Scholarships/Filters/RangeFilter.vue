<template>
<range-slider @callback="data => setParam(data)"
  :formatter="(v) => v >= 5000 ? `${v}+` : `${v}`"
  :value="amount"
  :min="0" :max="5000"
  :interval="500"
  tooltip="always"
  tooltip-dir="top"
  :piecewise="false"
  :height="6"
  :dot-size="20"
  :bg-style="{backgroundColor: '#F2F7FE'}"
  :process-style="{backgroundColor: '#ACCAF6', borderRadius: '4px'}"/>
</template>

<script>
import { mapState, mapActions } from "vuex";
import RangeSlider from "vue-slider-component";

export default {
  components: {
    RangeSlider
  },
  computed: {
    ...mapState({
      amount: state => state.list.scholarships.filter.amount
    })
  },
  methods: {
    ...mapActions({
      setFilterParam: "list/setFilterParam"
    }),
    setParam(parameter) {
      this.setFilterParam({
        nameSpace: 'scholarships',
        filterBy: 'amount',
        parameter
      })

      this.$emit('filter');
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';

	.vue-slider-component {
		.vue-slider {
			&-dot {
				background: #FFFFFF;
				border: 0.5px solid #ACCAF6;
				box-sizing: border-box;
				box-shadow: 0px 2px 2px rgba(66, 74, 89, 0.27);
			}

			&-tooltip-top {
				top: -5px !important;
			}

			&-tooltip {
				font-size: 12px;
				color: white;
				text-align: center;

				box-sizing: border-box;
				border-radius: 2px;
				background-color: #708FE7;
				height: 18px; width: 38px;
				line-height: 18px;
				padding: 0;
				border: none;

				@include breakpoint($s) {
					font-size: 13px;
				}

				&:before {
					content: none;
				}
			}
		}
	}
</style>