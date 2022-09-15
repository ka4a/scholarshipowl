<script>
import Coreg       from "components/Common/Coregs/CoregBasic.vue";
import Berecruited from "components/Common/Coregs/CoregBerecruited.vue";

const propsByCoreg = (name, options) => {
  let { text, id, extra } = options;
  let sms = {
      name: "sms",
      text: `By providing your phone number and checking this (optional) box you agree to receive SMS messages from the US Navy and its third-party recruiter partners using automated dialing systems. <a target='_blank' href='https://www.navy.com/privacy-policy'>Terms</a>`
    };

  const specificProps = {
    "zuusa": null,
    "loan": null,
    "danemedia": null,
    "academix": {
      thirdPartMutator: "//create.lidstatic.com/campaign/d174a714-e2c5-396b-11fe-a831c3ffc102.js?snippet_version=2",
      noscript: "//create.leadid.com/noscript.gif?lac=fe5f409d-bc58-55b7-582b-5b7be103dea1&lck=d174a714-e2c5-396b-11fe-a831c3ffc102&snippet_version=2"
    },
    "doublepositive": {
      thirdPartMutator: "//create.lidstatic.com/campaign/fe5f409d-feed-beef-cafe-5b7be103dea1.js?snippet_version=2",
      noscript: "//create.leadid.com/noscript.gif?lac=fe5f409d-bc58-55b7-582b-5b7be103dea1&lck=fe5f409d-feed-beef-cafe-5b7be103dea1&snippet_version=2",
      hidden: true
    },
    "birddogaa": { extra, sms },
    "birddogasian": { extra, sms },
    "birddogfemale": { extra, sms },
    "birddoghispanic": { extra, sms },
    "birddognupoc": { extra, sms },
    "birddogmale": { extra, sms },
    "birddoggenofficer": { extra, sms },
    "birddogarmyreserve": { extra }
  }

  return {name, text, id, ...specificProps[name]}
}

const markSelected = (name, props, savedCoregs) => {
  if(!savedCoregs || !savedCoregs[name]) return props;

  props["checked"] = true

  if(savedCoregs[name].extra
    && savedCoregs[name].extra.sms) {
    props["sms"]["checked"] = true;
  }

  return props;
}

export default {
  render: function(createElement) {
    return createElement(
      "div",
      { "class": { "coreg-input": !!this.coregs.length }},
      (() => {
        let coregs = [];

        for (let i = 0; i < this.coregs.length; i += 1) {
          let coreg = this.coregs[i],
              name = coreg.name.toLowerCase();

          if(name === "zuusa" || name === "loan" || name === "danemedia") continue;

          if(name === "berecruited") {
            let props = {
              id: coreg.id,
            }

            if(this.savedCoregs && this.savedCoregs['berecruited']) {
              const berProps = this.savedCoregs['berecruited'];

              props = {
                ...props,
                extra: berProps.extra,
                checked: !!berProps.checked
              }
            }

            coregs.push(createElement(Berecruited, {
              on : {
                coreg: this.handleCoregEmit,
                berecruited: val => this.$emit("berecruited", val)
              },
              props
            }));

            continue;
          }

          coregs.push(createElement(Coreg, {
            on: { coreg: this.handleCoregEmit },
            props: markSelected(name, propsByCoreg(name, coreg), this.savedCoregs)
          }));
        }

        return coregs;
      })()
    );
  },
  props: {
    submiting:   { type: Boolean, default: false },
    coregs:      { type: Array, required: true },
    savedCoregs: { type: Object }
  },
  data() {
    return {
      data: {}
    }
  },
  methods: {
    handleCoregEmit(coregName, coregData) {
      if(!coregData) {
        delete this.data[coregName]
      } else {
        this.data[coregName] = coregData;
      }

      this.$emit("coreg", this.data);
    }
  }
};
</script>
