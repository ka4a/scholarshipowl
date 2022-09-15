<template>
  <ul class="cvv-clarification">
    <li>
      <span class="cvv-clarification__text"><b>Visa, MasterCard, Discover</b> — 3 digits on the back of your card.</span>
      <img style="margin-top: 7px" src="./CVVClarification/other.png"  alt="cvv clarification Visa, MasterCard, Discover">
    </li>
    <li style="margin-left: 17px">
      <span class="cvv-clarification__text"><b>American Express</b> — 4 digits on the front of your card.</span>
      <img style="margin-top: 24px" src="./CVVClarification/american.png" alt="cvv clarification American Express">
    </li>
  </ul>
</template>

<style lang="scss">
  .cvv-clarification {
    width: 288px;
    height: 189px;
    background-color: $white;
    border-radius: 5px;
    box-shadow: rgba(53, 76, 109, 0.1) 0 10px 24px;
    padding: 21px 22px 25px;
    box-sizing: border-box;

    display: flex;

    &:before {
      content: "";
      position: absolute;
      bottom: -12px;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 13px 10px 0 10px;
      border-color: #ffffff transparent transparent transparent;
      left: 41.5%;
    }

    &__text {
      color: $blue-bayoux;
      font-size: 12px;
      line-height: 17px;

      b {
        font-weight: 600;
      }
    }
  }
</style>