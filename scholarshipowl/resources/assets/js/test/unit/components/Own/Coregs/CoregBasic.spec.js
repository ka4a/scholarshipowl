import { mount } from "@vue/test-utils";
import CheckBoxBasic from "components/Common/CheckBoxes/CheckBoxBasic.vue";
import CoregBasic from "components/Common/Coregs/CoregBasic.vue";

const initCoregName = "christianconnector",
      id = 21,
      text = "label text for coreg checkbox";

const externalSourcePath = "//some.source.com/external-src";

const paramsHiddenInput = {
  ref:  "leadid_token",
  id:   "leadid_token",
  name: "universal_leadid",
  type: "hidden"
}

const extraProps = [{
  name: "offer_id",
  value: 1235063
}, {
  name: "oid",
  value: 9096
}];

const smsProp = {
  name: "sms",
  text: `By providing your phone number and checking this (optional) box
  you agree to receive SMS messages from the US Navy and its third-party
  recruiter partners using automated dialing systems. <a target='_blank'
  href='https://www.navy.com/privacy-policy'>Terms</a>`
};

describe("CoregBasic", () => {
  const wrapperCoreg = mount(CoregBasic, {
    propsData: {
      id,
      name: initCoregName,
      text
    }
  });

  it("coreg name prop should be passed like name prop to CheckBoxBasic.vue component", () => {
    expect(wrapperCoreg.find(CheckBoxBasic).props("name"))
      .toBe(wrapperCoreg.props("name"));
  })

  it("hidden coreg should be in the DOM in hidden state (display: none, visibility: hidden)", () => {
    expect(wrapperCoreg.isVisible()).toBe(true);

    wrapperCoreg.setProps({ hidden: true });

    expect(wrapperCoreg.isVisible()).toBe(false);
  })

  it("pass 'noscript' param should create 'img' tag with 'noscript' param src value", () => {
    expect(wrapperCoreg.contains("noscript")).toBe(false);

    wrapperCoreg.setProps({
      noscript: externalSourcePath
    })

    const noscriptEl = wrapperCoreg.find("noscript");

    expect(noscriptEl.contains("img"));
    expect(noscriptEl.find("img").element.getAttribute("src")).toBe(externalSourcePath);
  })

  it("pass 'js' param should create hidden input and 'script' with 'js' src", () => {
    expect(wrapperCoreg.contains("script")).toBe(false);
    expect(wrapperCoreg.contains("input[type='hidden']")).toBe(false);

    wrapperCoreg.setProps({
      js: externalSourcePath
    })

    expect(wrapperCoreg.contains("script")).toBe(true);
    expect(wrapperCoreg.contains("input[type='hidden']")).toBe(true);

    const input = wrapperCoreg.find("input[type='hidden']").element;

    expect(input.getAttribute("type")).toBe(paramsHiddenInput.type);
    expect(input.getAttribute("id")).toBe(paramsHiddenInput.id);
    expect(input.getAttribute("name")).toBe(paramsHiddenInput.name);

    expect(wrapperCoreg.vm.$refs.hasOwnProperty(paramsHiddenInput.ref)).toBe(true);
  })

  it("CheckBoxBasic component 'input' event should trigger 'coreg' event emit", () => {
    wrapperCoreg.find(CheckBoxBasic).vm.$emit("input");
    expect(!!wrapperCoreg.emitted("coreg")[0].length).toBe(true);
  })

  it("CheckBoxBasic 'input' event should trigger 'coreg' event with 'name' playload", () => {
    wrapperCoreg.find(CheckBoxBasic).vm.$emit("input");
    expect(wrapperCoreg.emitted("coreg")[1][0])
      .toBe(wrapperCoreg.props("name"));
  })

  it("CheckBoxBasic 'input' event 'false' playload should trigger 'coreg' event with null playload", () => {
    wrapperCoreg.find(CheckBoxBasic).vm.$emit("input", false);
    expect(wrapperCoreg.emitted("coreg")[2][1])
      .toBe(null);
  })

  it("CheckBoxBasic 'input' event 'true' should trigger 'coreg' event with 'checked': 1 in second playload", () => {
    wrapperCoreg.find(CheckBoxBasic).vm.$emit("input", true);
    expect(wrapperCoreg.emitted("coreg")[4][1].checked).toBe(1);
  })

  it("second playload should emit passed 'extra' props array in second playload param in extra: {name:value} format", () => {
    wrapperCoreg.setProps({
      extra: extraProps
    })

    wrapperCoreg.find(CheckBoxBasic).vm.$emit("input", true);

    const emitedExtraProps = wrapperCoreg.emitted("coreg")[5][1].extra,
          passedExtraProps = wrapperCoreg.props("extra");

    expect(passedExtraProps[0].name).toBe(Object.keys(emitedExtraProps)[0]);
    expect(passedExtraProps[1].name).toBe(Object.keys(emitedExtraProps)[1]);

    expect(passedExtraProps[0].value).toBe(emitedExtraProps[passedExtraProps[0].name])
    expect(passedExtraProps[1].value).toBe(emitedExtraProps[passedExtraProps[1].name])
  })

  it("passed 'sms' prop should init render second CheckBoxBasic component", () => {
    expect(wrapperCoreg.findAll(CheckBoxBasic).length).toBe(1);

    wrapperCoreg.setProps({
      sms: smsProp
    })

    expect(wrapperCoreg.findAll(CheckBoxBasic).length).toBe(2);
  })

  it("CheckBoxBasic 'input' event with checked/unchecked 'sms' checkbox shold emit 'sms' porop 1/0 in 'extra' object", () => {
    const checkboxes = wrapperCoreg.findAll(CheckBoxBasic),
          coregCheckbox = checkboxes.at(0),
          smsCheckbox = checkboxes.at(1);

    smsCheckbox.vm.$emit("input", true);
    coregCheckbox.vm.$emit("input", true);

    expect(wrapperCoreg.emitted("coreg")[6][1].extra.sms).toBe(1);

    smsCheckbox.vm.$emit("input", false);
    coregCheckbox.vm.$emit("input", true);

    expect(wrapperCoreg.emitted("coreg")[7][1].extra.sms).toBe(0);
  })

  it("show 'cappex' coreg logo if coreg name 'cappex'", () => {
    expect(wrapperCoreg.find(".coreg__cappex-logo").exists()).toBe(false);

    wrapperCoreg.setProps({
      name: "cappex"
    })

    expect(wrapperCoreg.find(".coreg__cappex-logo").exists()).toBe(true);
  })
})