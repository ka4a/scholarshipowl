import { mount } from "@vue/test-utils";
import CheckBoxBasic from "components/Common/CheckBoxes/CheckBoxBasic.vue";

const baseSelectorName = "checkbox-basic";

const slotLabel = "<span>label slot</span>";

const options = {
  propsData: { name: "birdog"},
  slots: {
    label: slotLabel
  }
};

describe('CheckBoxBasic', () => {
  const wrapper = mount(CheckBoxBasic, options);

  it("render necessary interaction elements (input, label)", () => {
    expect(wrapper.contains('input')).toBe(true);
    expect(wrapper.contains('label')).toBe(true);
  })

  it("name prop passed like 'id' 'name' input attribute; like 'for' label attribute", () => {
    const inputWrapper = wrapper.find("input");

    expect(inputWrapper.element.id).toBe(wrapper.props("name"));
    expect(inputWrapper.element.name).toBe(wrapper.props("name"));
    expect(wrapper.find("label").element.getAttribute("for")).toBe(wrapper.props("name"));
  })

  it("render passed checkbox label description slot (name - label)", () => {
    expect(wrapper.find(`.${baseSelectorName}__text`).html()).toContain(slotLabel);
  })

  it("passed value prop changes input state to oposite (checked)", () => {
    expect(wrapper.props().value).toBe(false);

    const inputWrapper = wrapper.find("input");

    wrapper.setProps({ value: true });

    expect(inputWrapper.element.checked).toBe(true);

    wrapper.setProps({ value: false });

    expect(inputWrapper.element.checked).toBe(false);
  })

  it("click on checkbox component emit boolean type", () => {
    wrapper.find("label").trigger("click");

    expect(typeof wrapper.emitted("input")[0][0]).toBe('boolean');
  })

  it("click on checkbox component emit true value", () => {
    wrapper.setProps({ value: false });

    wrapper.find("label").trigger("click");

    expect(wrapper.emitted("input")[1][0]).toBe(true);
  })

  it("two time click on checkbox component emit false value", () => {
    wrapper.setProps({ value: false });

    wrapper.find("label").trigger("click");
    wrapper.find("label").trigger("click");

    expect(wrapper.emitted("input")[3][0]).toBe(false);
  })
})