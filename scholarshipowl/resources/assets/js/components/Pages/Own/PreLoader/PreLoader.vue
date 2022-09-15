<template lang="html">
  <div class="scholarships-loader">
    <div class="scholarships-loader__loader"></div>
  </div>
</template>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

// scholarship preloader
$blue: #5998EF;
$white: white;

.scholarships-loader {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;

  &__loader {
    animation: rotate 1s infinite;
    height: 50px;
    width: 50px;

    &:before,
    &:after {
      border-radius: 50%;
      content: '';
      display: block;
      height: 20px;
      width: 20px;
    }

    &:before {
      animation: ball1 1s infinite;
      background-color: #597CE1;
      box-shadow: 30px 0 0 #f8b334;
      margin-bottom: 10px;
    }

    &:after {
      animation: ball2 1s infinite;
      background-color: #F87B4A;
      box-shadow: 30px 0 0 #97bf0d;
    }
  }
}

@keyframes rotate {
 0% {
   -webkit-transform: rotate(0deg) scale(0.8);
   -moz-transform: rotate(0deg) scale(0.8);
 }
 50% {
   -webkit-transform: rotate(360deg) scale(1.2);
   -moz-transform: rotate(360deg) scale(1.2);
 }
 100% {
   -webkit-transform: rotate(720deg) scale(0.8);
   -moz-transform: rotate(720deg) scale(0.8);
 }
}

@keyframes ball1 {
 0% {
   box-shadow: 30px 0 0 #F87B4A;
 }
 50% {
   box-shadow: 0 0 0 #F87B4A;
   margin-bottom: 0;
   -webkit-transform: translate(15px,15px);
   -moz-transform: translate(15px, 15px);
 }
 100% {
   box-shadow: 30px 0 0 #CEE2FF;
   margin-bottom: 10px;
 }
}

@keyframes ball2 {
 0% {
   box-shadow: 30px 0 0 #597CE1;
 }
 50% {
   box-shadow: 0 0 0 #F87B4A;
   margin-top: -20px;
   -webkit-transform: translate(15px,15px);
   -moz-transform: translate(15px, 15px);
 }
 100% {
   box-shadow: 30px 0 0 #F34857;
   margin-top: 0;
 }
}
</style>
