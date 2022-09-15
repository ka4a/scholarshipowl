<template>
  <section class="plans-press">
    <h5 class="plans-press__title">As seen on</h5>
    <ul class="plans-press__list">
      <li v-for="post in presses" :class="['plans-press__post', kebabCase(post.name)]" :key="post.name">
        <a :href="post.link" target="_blank">
          <component :is="post.component" />
        </a>
      </li>
    </ul>
  </section>
</template>

<script>
  // 1. Dinamic set of press blocks
  // 2. Layzy loading
  // 3. Definition of dementions based on set sizes

  import Huffpost   from "./Press/Huffpost.vue";
  import TechCranch from "./Press/TechCranch.vue";
  import TNW        from "./Press/TNW.vue";
  import Gigaom     from "./Press/Gigaom.vue";
  import Forbes     from "./Press/Forbes.vue";

  const kebabCase = string => string.replace(/([a-z])([A-Z])/g, '$1-$2').replace(/\s+/g, '-').toLowerCase();

  const presses = [
    {
      name: 'HuffPost',
      link: 'https://www.huffpost.com/entry/how-to-attend-college-without-going-broke_b_58bedb0de4b0abcb02ce225b',
      component: Huffpost
    },
    {
      name: 'Tech Crunch',
      link: 'https://techcrunch.com/2016/06/10/scholarships-are-the-new-sweepstakes/',
      component: TechCranch
    },
    {
      name: 'TNW',
      link: 'https://thenextweb.com/insider/2015/07/22/scholarshipowl-automates-college-scholarships-for-students/',
      component: TNW
    },{
      name: 'GIGAOM',
      link: 'https://gigaom.com/2015/09/14/scholarshipowl-uses-big-data-machine-learning-to-fix-the-convoluted-scholarship-application-process/',
      component: Gigaom
    },
    {
      name: 'Forbs',
      link: 'https://www.forbes.com/sites/annefield/2015/08/30/applying-for-private-scholarships-no-longer-a-wild-goose-chase/#2b36ce357676',
      component: Forbes
    }
  ];

  export default {
    data() {
      return {
        presses
      }
    },
    methods: {
      kebabCase
    }
  }
</script>

<style lang="scss">
  $niagara: #0DBE98;
  $japanese-laurel: #0A9600;
  $flamingo: #F44040;
  $curious-blue: #18ADE5;

  .plans-press {
    padding: 25px 0;

    @include breakpoint($m) {
      padding: 35px 0;
    }

    @include breakpoint($m) {
      padding: 45px 0;
    }

    &__title {
      font-size: 14px;
      font-weight: 700;
      margin-bottom: 10px;

      @include breakpoint($m) {
        font-size: 17px;
        margin-bottom: 15px;
      }

      @include breakpoint($l) {
        font-size: 20px;
        margin-bottom: 20px;
      }
    }

    &__list {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    &__post {
      display: block;

      path {
        fill: $mine-shaft;
        transition: 200ms fill;
      }

      @include breakpoint(max-width $l - 1px) {
        a, svg {
          display: block;
          width: 100%;
        }
      }

      &:first-child,
      &:first-child + & {
        margin-bottom: 10px;
      }

      &.huff-post {
        width: 47%;

        &:hover {
          .color {
            fill: $niagara;
          }
        }
      }

      &.tech-crunch {
        width: 47%;

        &:hover {
          .color {
            fill: $japanese-laurel;
          }
        }
      }

      &.tnw {
        width: 20%;

        &:hover {
          .color {
            fill: $flamingo;
          }
        }
      }

      &.gigaom {
        width: 34%;

        &:hover {
          path {
            fill: $limed-spruce;
          }

          .color {
            fill: $curious-blue;
          }
        }
      }

      &.forbs {
        width: 26%;
      }

      @include breakpoint($m) {
        &:first-child,
        &:first-child + & {
          margin-bottom: 0;
        }

        &.huff-post,
        &.tech-crunch {
          width: 22%;
        }

        &.tnw {
          width: 9%;
        }

        &.gigaom {
          width: 16%;
        }

        &.forbs {
          width: 13%;
        }
      }
    }
  }
</style>