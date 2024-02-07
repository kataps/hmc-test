import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = process.env.VUE_APP_API_URL;
