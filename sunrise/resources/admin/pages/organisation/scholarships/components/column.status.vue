<template>
  <div class="column-status">
    <strong>
      <i class="column-status_dot" v-if="statusDot" :class="'column-status_dot__' + statusDot" />
      <span>{{ label }} ({{ applications.length }})</span>
    </strong>
    <draggable class="column-status__box"
      :options="dragOptions"
      :move="onMove"
      @change="onChange"
      @start="onStart"
      @end="onEnd"
      v-model="list">
      <article class="media"
        v-for="application in applications"
        :key="application.id"
        @click="$router.push({ name: 'scholarships.published.review.application', params: { application: application.id, id: $route.params.id }})">
        <!-- <figure class="media-left">
          <p class="avatar">
            <span>{{ application.data.name[0] }}{{ application.data.name[1] }}</span>
          </p>
        </figure> -->
        <div class="media-content">
          <div class="content">
            <div class="name"><strong>{{ application.data.name }}</strong></div>
            <div class="date">
              <c-icon icon="clock" />
              <span>{{ application.createdAt | moment('ll') }}</span>
            </div>
          </div>
        </div>
      </article>
    </draggable>
  </div>
</template>
<script>
import Draggable from 'vuedraggable';

export default {
  components: {
    Draggable
  },
  props: {
    label: String,
    statusDot: String,
    applications: Array,
  },
  methods: {
    onMove({ draggedContext, relatedContext }) {
    },
    onStart({ oldIndex }) {
    },
    onEnd($event) {
      if (this.applications[$event.oldIndex]) {
        this.$emit('moved', {
          application: this.applications[$event.oldIndex],
          to: $event.to
        })
      }
    },
    onChange(a,b,c) {
    },
  },
  data: function() {
    return {
      list: this.applications.slice(0),
      dragOptions: {
        animation: 1,
        group: 'application',
      }
    }
  }
}
</script>
<style lang="scss">
@import "~scss/variables.scss";
.column-status {
  > strong {
    display: inline-block;
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
    color: rgba(27, 41, 66, 0.41);
    margin: 7px 0;
  }
  &__box {
    background: #F5F7FA;
    border-radius: 5px;
    border: none;
    padding: 18px 14px;
    height: 100%;

    > .media {
      background: #FFFFFF;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.03);
      border-radius: 5px;
      padding: 12px 10px;
      cursor: pointer;


      .media-left {
        .avatar {
          display: flex;
          align-items: center;
          width: 40px;
          height: 40px;
          background: #C4C4C4;
          border-radius: 50%;
          > span {
            width: 100%;
            text-transform: uppercase;
            text-align: center;
            font-size: 14px;
            color: #000000;
          }
        }
      }
      .content {
        .name {
          font-size: 15px;
          font-weight: 600;
        }
        .date {
          color: $grey;
          font-size: 13px;
        }
      }

      + .media {
        border-top: none;
      }
      &:hover {
        background: rgba(255, 255, 255, 0.6);
      }
    }
  }
  &_dot {
    width: 15px;
    height: 15px;
    display: inline-block;
    border-radius: 50%;
    margin-right: 10px;
    &__review {
      background: radial-gradient(7.50px at 50% 50%, #CAD3E0 0%, #B6BECA 100%), #B6BECA;
    }
    &__accepted {
      background: radial-gradient(7.50px at 50% 50%, #9DC58B 0%, #96B787 100%);
    }
    &__rejected {
      background: radial-gradient(7.50px at 50% 50%, #EE4B60 0%, #D9374C 100%);
    }
  }
  &:not(:last-child) {
    margin-right: 17px !important;
  }
}
</style>
