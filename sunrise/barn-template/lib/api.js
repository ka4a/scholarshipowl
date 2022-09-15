import axios from 'axios';

export default axios.create({
  baseUrl: 'http://sunrise.local/api',
  headers: {
    'Authorization': 'Bearer 4F8UO3HuLwRVQyWkqoNQZmaD88OGapaqG2X9b36p4wFXU3f2Xyk6wr2DLQwL',
  }
});
