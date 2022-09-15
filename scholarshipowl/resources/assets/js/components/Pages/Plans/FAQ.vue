<template>
  <section class="plans-faq">
    <div class="base-hor-indent">
      <h2 class="plans-faq__title">Quick answers to FAQs</h2>
      <Accordion v-if="isMobile"
       :items="questions.leftColumn.concat(questions.rightColumn)"
        controller-name="title"
        representation-name="text">
        <template v-slot:controller="{ctrl: question, isActive}">
          <Question :question="question">
            <Indicator class="plans-faq__indicator" v-slot :is-active="isActive" />
          </Question>
        </template>
        <template v-slot:representation="{repres: answer}">
          <Answer :answer="answer" />
        </template>
      </Accordion>
      <section class="plans-faq__section" v-else>
        <div v-for="column in questions">
          <div v-for="item in column">
            <Question :question="item.title" />
            <Answer :answer="item.text" />
          </div>
        </div>
      </section>
    </div>
  </section>
</template>

<script>
  import Accordion from "components/Pages/Plans/FAQ/Accordion.vue";
  import Question from "components/Pages/Plans/FAQ/Question.vue";
  import Answer from "components/Pages/Plans/FAQ/Answer.vue";
  import Indicator from "components/Common/Indicators/StateIndicatorCircle.vue";

  const leftColumn = [
    {
      title: "Do I have to pay for scholarships?",
      text: "ScholarshipOwl will never charge you for any scholarship. We provide a service that streamlines all the administration associated with scholarships, so you have more time on your hands."
    },
    {
      title: "What kind of service do I get with ScholarshipOwl?",
      text: "We match you to verified scholarships for which you can apply with a single application form. We re-enter you automatically when scholarships recur and we show you status updates on your submitted applications."
    },
    {
      title: "What is You Deserve It scholarship?",
      text: "This is our own scholarship contribution. Every month, a random student gets $1000. When you sign up for ScholarshipOwl, you are automatically entered into the draw for this scholarship. And it’s totally free."
    },
    {
      title: "Is ScholarshipOwl legit?",
      text: "We helped over 5 million students find scholarships. We built a platform to get you on the fastest route to scholarships. If that isn’t enough, there is always a free trial so you can see for yourself."
    }
  ];

  const rightColumn = [
    {
      title: "How does the free trial work?",
      text: "The free trial lasts 7 days, once it expires, only then is your subscription charged. Any scholarships you applied to during the trial period is valid and won’t be revoked if you cancel your trial."
    },
    {
      title: "Why do I need a credit card to activate a free trial?",
      text: "A credit card is important if you wish to continue your subscription. We only charge it once the trial period is over and if you wish to continue using our services. You can cancel your subscription at any point."
    },
    {
      title: "Is ScholarshipOwl giving away these scholarships?",
      text: "The scholarships offered come from independent scholarship providers which we comb through to match you specifically to the most relevant scholarships, with the best chance of winning."
    },
    {
      title: "How do I win a scholarship?",
      text: "Apply, apply, and apply! Getting results depends on the work that you are willing to put in! The more you apply for the greater the chance for you to win!"
    }
  ];

  export default {
    components: {
      Accordion,
      Question,
      Answer,
      Indicator
    },
    props: {
      isMobile: {type: Boolean, required: true}
    },
    data() {
      return {
        questions: {
          leftColumn,
          rightColumn
        }
      }
    }
  }
</script>

<style lang="scss">
  .plans-faq {
    background-color: white;
    padding-top: 1px;

    &__title {
      font-weight: bold;
      font-size: 20px;
      line-height: 1.6em;
      color: $mine-shaft;

      text-align: center;
      margin: 17px 0 13px 0;

      @include breakpoint($m) {
        font-size: 32px;
        margin: 30px 0 15px 0;
      }

      @include breakpoint($l) {
        margin: 60px 0 40px 0;
        text-align: left;
      }
    }

    &__section {
      @include breakpoint($l) {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-column-gap: 30px;

        & div div:last-child > p {
          border: none;
        }
      }

      @include breakpoint($xl) {
        grid-column-gap: 90px;
      }
    }

    &__indicator {
      margin-left: 10px;

      @include breakpoint($m) {
        min-width: 25px !important;
        min-height: 25px !important;
      }
    }
  }
</style>