<template lang="html">
  <div class="scholarships-list-item">
    <badge-recurrent  v-if="item.isRecurrent" />
    <item-status :text="amount" :status="item.application.status + ''"/>
    <div class="scholarships-list-item__right">
      <h4 class="scholarships-list-item__title">
        <span class="title">{{ item.title }}</span>
        <favorite-controller class="scholarships-list-item__favorite" :is-favorite="item.isFavorite"  @click.stop.native="favorite(item)" />
      </h4>
      <item-attribute :class="['scholarships-list-item__attribute', {'deadline-notification' : deadlineSoon}]"
        title="deadline: " :text="expirationDate" />
      <div class="scholarships-list-item__attribute">
        <item-attribute class="scholarships-list-item__attribute" title="requirements: " :text="requirementsCount" />
        <i class="angle-bracket"></i>
      </div>
    </div>
  </div>
</template>

<script>
import BadgeRecurrent from "components/Pages/Scholarships/ScholarshipsList/Item/BadgeRecurrent.vue";
import FavoriteController from "components/Pages/Scholarships/ScholarshipsList/Item/FavoriteController.vue";
import ItemStatus from "components/Pages/Scholarships/ScholarshipsList/Item/ItemStatus.vue";
import ItemAttribute from "components/Pages/Scholarships/ScholarshipsList/Item/ItemAttribute.vue";

export default {
  components: {
    BadgeRecurrent,
    FavoriteController,
    ItemStatus,
    ItemAttribute
  },
  props: {
    item: {required: true, type: Object},
    amount: {required: true, type: String},
    deadlineSoon: {required: true, type: Boolean},
    favorite: {required: true, type: Function},
    expirationDate: {required: true, type: String},
    requirementsCount: {required: true, type: String}
  }
};
</script>