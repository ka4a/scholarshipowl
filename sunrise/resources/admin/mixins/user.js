export default {
  computed: {
    me: ({ $store }) => $store.state.user.me,
  }
}
