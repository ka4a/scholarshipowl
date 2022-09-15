<template>
  <article class="sb" v-if="isOpen">
      <header class="sb__header"></header>

      <div class="sb__account user-bar">
        <div class="user-bar__photo"></div>
        <div class="user-bar__name">ARNOLD <span class="t-bold">SCHWARZENEGGER</span></div>
        <div class="user-bar__account-status">FREE ACCOUNT</div>
      </div>

      <ul class="sb__navigation sb-nav">
        <li class="sb-nav__item">
          <i class="sb-nav__icon icon icon-scholarship"></i>
          <span class="sb-nav__text">Scholarships</span>
        </li>
        <li class="sb-nav__item">
          <i class="sb-nav__icon icon icon-mailbox"></i>
          <span class="sb-nav__text">Mailbox</span>
        </li>
        <li class="sb-nav__item">
          <i class="sb-nav__icon icon icon-profile"></i>
          <span class="sb-nav__text">My Profile</span>
        </li>
        <li class="sb-nav__item">
          <i class="sb-nav__icon icon icon-smile"></i>
          <span class="sb-nav__text">Refer a Friend</span>
        </li>
      </ul>

      <footer class="sb__footer">
        <address class="phone-number"><i class="icon-phone icon"></i><span class="phone-number__value">+1 800 494 49 08</span></address>
      </footer>
  </article>
</template>

<style>

</style>

<script>
export default {
  props: ["isOpen"],
};
</script>
