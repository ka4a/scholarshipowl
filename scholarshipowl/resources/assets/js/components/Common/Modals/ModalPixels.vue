<template>
  <span class="modal-pixel-container">
    <img src='//d.adroll.com/ipixel/K3IVFQQR6VGYBD4SUG2Y7J/Q5UPORP7KFHBRG4HXDH4N5?name=f3d189e8' width='1' height='1' />
    <img src='//d.adroll.com/fb/ipixel/K3IVFQQR6VGYBD4SUG2Y7J/Q5UPORP7KFHBRG4HXDH4N5?name=f3d189e8' width='1' height='1' />
    <iframe
      v-if="tracking.hasOffersTransactionId && tracking.isFreeTrial"
      id="goal-id40"
      :src="'https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=40&transaction_id='+ tracking.hasOffersTransactionId"
      scrolling="no"
      frameborder="0"
      width="1"
      height="1">
    </iframe>
    <iframe
      v-if="tracking.hasOffersTransactionId && !tracking.isFreeTrial && !tracking.isFreemium"
      id="goal-id32"
      :src="'https://scholarship.go2cloud.org/aff_l?offer_id=32&transaction_id='+ tracking.hasOffersTransactionId"
      scrolling="no"
      frameborder="0"
      width="1"
      height="1">
    </iframe>
  </span>
</template>

<script>
  export default {
    props: {
      tracking: {type: Object, required: true}
    }
  }
</script>

<style lang="scss">
  .modal-pixel-container {
    position: absolute;
  }
</style>