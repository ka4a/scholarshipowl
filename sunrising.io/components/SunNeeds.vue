<template>
  <section id="needs" class="needs-container">
    <div class="container is-fluid">
      <h2 class="has-text-centered medium-title">
        We Focus on Your Needs
      </h2>

      <div class="columns needs">
        <div
          v-for="(item, index) in items"
          :key="`item${index}`"
          class="column is-6-tablet is-3-desktop"
        >
          <sun-need-item
            :image="item.image"
            :title="item.title"
            :desc="item.desc"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import SunNeedItem from './partial/SunNeedItem';

export default {
  name: 'SunNeeds',

  components: {
    SunNeedItem,
  },

  data() {
    return {
      items: [
        {
          title: 'User Friendly',
          image: 'monitor',
          desc:
            'Simple and easy-to-use interface makes managing scholarships fun!',
        },

        {
          title: 'Audience',
          image: 'user',
          desc:
            'Expand your brand presence and reach your target audience more effectively',
        },

        {
          title: 'Security',
          image: 'lock',
          desc:
            'We shield all sensitive information so you and your applicants can feel secure',
        },

        {
          title: 'Affordability',
          image: 'free',
          desc: 'A professional quality tool at the best price — free!',
        },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
@import '../assets/css/_variables.scss';
@import '../assets/css/_mixins.scss';

.needs-container {
  h2 {
    margin-top: 55px;
    margin-bottom: 25px;

    @include sun-tablet {
      margin-top: 10px;
      margin-bottom: 35px;
    }

    @include sun-desktop {
      margin-top: 50px;
      margin-bottom: 45px;
    }

    @include sun-hd {
      margin-top: 110px;
      margin-bottom: 65px;
    }
  }

  .needs {
    @include only-sun-tablet {
      display: flex;
    }

    .column {
      @include only-sun-tablet {
        flex: none;
        width: 50%;
      }
    }
  }
}
</style>
