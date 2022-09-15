<script>
import Header from "./Header.vue";
import MiniHeader from "./MiniHeader.vue";

export default {
  render(createElement) {
    return createElement(this.isMiniHeader ? MiniHeader : Header);
  },
  computed: {
    isMiniHeader() {
      const whereShouldShowMiniHeader = ["/register", "/register2", "/register3"];

      return whereShouldShowMiniHeader.includes(window.document.location.pathname);
    }
  }
};
</script>