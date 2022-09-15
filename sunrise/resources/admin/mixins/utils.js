import { parseErrors } from 'lib/utils';

export default {
  filters: {
    ordinal_suffix: (i) => {
      var j = i % 10,
          k = i % 100;
      if (j == 1 && k != 11) {
          return i + "st";
      }
      if (j == 2 && k != 12) {
          return i + "nd";
      }
      if (j == 3 && k != 13) {
          return i + "rd";
      }
      return i + "th";
    }
  },
  methods: {
    JSONAPIparseErrors(data) {
      parseErrors(data, this.$validator);
    },
    UTCDate(value) {
      const parsed = new Date(Date.parse(value));
      return parsed ? new Date(Date.UTC(
        parsed.getUTCFullYear(),
        parsed.getUTCMonth(),
        parsed.getUTCDate()
      )) : null
    },
    loadApplicationFile(id) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/application_file/${id}/file`, { responseType: 'blob' })
          .then(({ data }) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.readAsDataURL(data);
          })
          .catch(reject);
      })
    },
    downloadApplicationFile(id, filename) {
      if (id) {
        axios({
          method: 'GET',
          url: '/api/application_file/' + id + '/download',
          responseType: 'blob'
        })
          .then((response) => {
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(new Blob([response.data]));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
          })
      }
    },
  }
}
