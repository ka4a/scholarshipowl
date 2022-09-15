<template lang="html">
  <section class="fm-accordion">
    <slot>Please provide `AccordionItem`s.</slot>
  </section>
</template>

<script>
import { scroll } from "../../../lib/utils/dom";

export default {
  name: "Accordion",
  model: {
    prop: "active",
    event: "update:active",
  },
  props: {
    active: { required: true },
  },
  data () {
    return {
      items: {},
      transition: false,
    };
  },
  mounted () {
    let items = {};

    this.$slots.default.forEach((vnode) => {
      const item = vnode.componentInstance;

      if (!item || !item.accordionName || items[item.accordionName]) return;

      items[item.accordionName] = item;

      item.$data.active = (item.accordionName === this.active);
      item.$on("click", () => {
        if (this.transition) return;
        if (this.active !== item.accordionName) {
          this.$emit("update:active", item.accordionName);
        }
      });
    });

    this.items = items;
  },
  watch: {
    active: function(value, old) {
      if (value === null) {
        Object.keys(this.items).forEach(v => {
          this.items[v].$data.active = false;
        });

        this.$emit("notselected");
        this.transition = false;

        return;
      }

      if (!this.items.hasOwnProperty(value))
        throw new Error(`Failed to find AccordionItem ${value}`);

      if (old) this.items[old].$data.active = false;

      this.transition = true;
      this.items[value].$data.active = true;

      scroll(this.items[value].$el, 800, 400, () => {
        this.transition = false;
      });
    },
    items: function(items) {
      this.$emit("update:items", items);
    }
  }
};
</script>
