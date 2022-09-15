<script>
const randomString = (n = 16) => {
  let text = '';
  const possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

  for(let i=0; i < n; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }

  return text;
};

let ownRandomString = randomString();

export default {
  render(createElement) {

    // set props object
    let src = (location.protocol=='https:'?'https://revive.scholarshipowl.com/delivery/ajs.php':'http://revive.scholarshipowl.com/delivery/ajs.php');

    if (!document.MAX_used) document.MAX_used = ',';

    src += '?zoneid=' + this.zoneId;
    src += '&amp;cb=' + ownRandomString;

    if (document.MAX_used != ',') {
      src += "&amp;exclude=" + document.MAX_used;
    }

    src += (document.charset ? '&amp;charset=' + document.charset : (document.characterSet ? '&amp;charset=' + document.characterSet : ''));
    src += "&amp;loc=" + escape(window.location);

    if (document.referrer) {
      src += "&amp;referer=" + escape(document.referrer);
    }

    if (document.context) {
      src += "&context=" + escape(document.context);
    }

    if (document.mmm_fo) {
      src += "&amp;mmm_fo=1";
    }

    let that = this;

    // init props object
    let props = {
      attrs: {
        width: that.width,
        height: that.height
      },
      ref: 'mount'
    }

    Vue.http.get(src).then(function(data) {
      if(data.ok && data.status === 200) {
        that.$refs.mount.innerHTML = eval(data.data.replace(/document\.write\(.*\);/g, ''));
      }
    })

    return createElement('div', props);
      // createElement('noscript', [
      //   createElement('a', {
      //     attrs: {
      //       target: '_blank',
      //       href:   'http://revive.scholarshipowl.com/delivery/ck.php?n=' + this.cb + '&amp;cb=' + ownRandomString
      //     }
      //   }, createElement('img', {
      //     attrs: {
      //       border: '0',
      //       alt: '',
      //       src: 'http://revive.scholarshipowl.com/delivery/avw.php?zoneid=' + this.zoneId + '&amp;cb=' + ownRandomString + '&amp;n=' + this.cb
      //     }
      //   }))
      // ])

  },
  props: {
    zoneId: { required: true, type: String },
    cb: { required: true, type: String },
    height: { required: true, type: String },
    width: { required: true, type: String }
  }
}
</script>

<style lang="css">
</style>
