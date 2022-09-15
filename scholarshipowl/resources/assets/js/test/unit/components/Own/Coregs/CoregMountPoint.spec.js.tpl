import { mount, shallowMount } from "@vue/test-utils";
import { Validator } from "vee-validate";
import CoregBasic from "components/Common/Coregs/CoregBasic.vue";
import CoregMountPoint from "components/Common/Coregs/CoregMountPoint.vue";

const coregsStub = [{
  extra:[],
  html:{},
  id:21,
  isVisible: true,
  js:{},
  monthlyCap: null,
  name: "Christianconnector",
  position: "coreg6",
  text: "label text for coreg checkbox"
}]

const coregPropsStub = {
  name: "Christianconnector",
  id: 21,
  text: "label text for coreg checkbox"
}

const $validator = new Validator();

describe("CoregMountPoint.vue", () => {
  const wrapperMountPoint = mount(CoregMountPoint, {
    propsData: {
      coregs: coregsStub
    },
    provide: () => ({ $validator })
  });

  const wrapperCoregBasic = mount(CoregBasic, {
    propsData: coregPropsStub
  });
})