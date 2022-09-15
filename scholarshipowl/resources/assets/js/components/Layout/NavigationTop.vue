<template>
  <nav :class="{'nav-top': true, 'collapse-open': isOpen}">
    <ul class="nav-top__list">
      <li v-for="(navItem, index) in navTop" class="nav-top__item">
        <a @click="toggleNavigation(index)" class="nav-top__link uppercase">{{ navItem.text }}</a>
        <i v-if="navItem.links.length" :class="{'icon' : true, 'icon-arrow-drop-down': true, 'rotate-torned': openedElem === index}"></i>

        <ul v-if="navItem.links.length" :class="{'nav-top__inner-list': true, 'collapse-open': openedElem === index}">
          <li v-for="link in navItem.links">
            <a class="link-top-nav" :href="link.href">{{ link.text }}</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<style>

</style>

<script type="javascript">
export default {
  data () {
    return {
      navTop: [
        {text: "info",
          links: [
            {href: "/faq", text: "FAQ"},
            {href: "/hc/en-us", text: "Helpdesk (beta)"},
            {href: "/what-people-say-about-scholarshipowl", text: "Reviews"},
            {href: "/awards/scholarship-winners", text: "Scholarships winners"},
          ]},
        {text: "about us",
          links: [
            {href: "/about-us", text: "about us"}
          ]},
        {text: "services",
          links: [
            {href: "/additional-services", text: "additional services"},
            {href: "/ebook", text: "eBook"},
            {href: "/offer-wall", text: "featured scholarships"}
          ]},
        {text: "contact",
          links: [
            {href: "/contact", text: "contact us"},
            {href: "/list-your-scholarship", text: "list your scholarships"},
            {href: "/what-people-say-about-scholarshipowl", text: "Reviews"},
            {href: "/partners", text: "partners"}
          ]},
      ],
      openedElem: undefined
    };
  },
  methods: {
    toggleNavigation(itemIndex, el) {
      this.openedElem = (this.openedElem === itemIndex) ? undefined : itemIndex;
    }
  },
  props: ["isOpen"]
};
</script>
