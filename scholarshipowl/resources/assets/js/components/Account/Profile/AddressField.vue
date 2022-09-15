<template>
  <div>
    <input v-validate="'required'"
         maxLength="551"
         v-model="address"
         class="best"
         :class="{ 'interaction-item__input' : true, 'interaction-item__input_error' : errors.has('address') }"
         id="register3-form-address"
         name="address"
         type="text"
         placeholder="Street address, P.O. box, company name"/>

    <input class="interaction-item__input"
           id="register3-form-address2"
           name="address2"
           type="text"
           placeholder="Apartment, suite, unit, building, floor, etc."/>
  </div>
</template>
<style>
</style>
<script type="javascript">
export default {
}
</script>
