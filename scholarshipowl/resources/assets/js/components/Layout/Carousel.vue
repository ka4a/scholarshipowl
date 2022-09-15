<template lang="html">
  <section class="testimonials bp-l-wrp">
    <carousel :perPage="1" :autoplay="true" :autoplayHoverPause="true" paginationActiveColor="#4988f7">
      <slide v-for="(testimonial, index) in testimonials" :key="index">
        <div class="testimonials__user">
          <p :class="'testimonials__avatar testimonials__avatar_' + index"></p>
          <p class="testimonials__name">{{ testimonial.user }} {{ testimonial.place }}</p>
          <p v-if="testimonial.winner" class="testimonials__sub-text">
            <span>You Deserve It</span>
            <span>$1,000</span>
            <span>Winner</span>
          </p>
        </div>
        <div class="testimonials__text pull-quote" v-html="testimonial.opinion"></div>
      </slide>
    </carousel>
  </section>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";

export default {
  components: {
    Carousel,
    Slide
  },
  data() {
    return {
      testimonials: [
        {user: "Kasey W.", place: "", winner: true, opinion: "I am really grateful for this opportunity. This scholarship will enable me to pay off some of my loans and focus on my career goals."}
        ,{user: "Max, M.", place: "Arizona", winner: false, opinion: "So far it has been pretty great and a lot easier than individually having to find the scholarships! Easy and comfortable process, was not long at all! Very well set up over all, I would and have recommended it! Awesome job guys!"}
        ,{user: "Emily C.", place: "Texas", winner: false, opinion: "- How was Your overall experience with ScholarshipOwl? <br /> - I love it, thanks guys."}
        ,{user: "Angelo G.", place: "Michigan", winner: false, opinion: "I was applied to scholarships I didnt know I was even eligible for."}
        ,{user: "Liberatus R.", place: "Nevada", winner: false, opinion: "- How was Your overall experience with ScholarshipOwl? <br /> - Amazing, thanks guys for your help."}
        ,{user: "Jean J.", place: "Maryland", winner: false, opinion: "I didn't even know there are so many scholarships out there for me."}
        ,{user: "Raques D.", place: "Georgia", winner: false, opinion: "Didn't expect it to be so easy. I was applied to scholarships I didnt know I was even eligible for."}
        ,{user: "Nicole C.", place: "New Jersey", winner: false, opinion: "Being automatically applied to multiple scholarships is a huge advantage."}
        ,{user: "Madison S.", place: "Arizona", winner: false, opinion: "The service was self-explanatory, and very helpful."}
      ]
    };
  }
};
</script>

<style lang="css">
</style>
