<script>
import FooterLayout from "./Footer.vue";
import MiniFooterLayout from "./MiniFooter.vue";

export default {
  render(createElement) {
    return createElement(this.isMiniFooter ? MiniFooterLayout : FooterLayout);
  },
  computed: {
    isMiniFooter() {
      const whereShouldShowMiniFooter = ["/select", "/my-applications", "/mailbox", "/secure-upgrade", "/upgrade-mobile"];

      return whereShouldShowMiniFooter.includes(window.document.location.pathname);
    }
  }
};
</script>