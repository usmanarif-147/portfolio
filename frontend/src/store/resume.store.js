import { defineStore } from "pinia";
import axios from "axios";

const apiUrl = import.meta.env.VITE_API_URL;

export const useResumeStore = defineStore("resumeStore", {
  state: () => ({
    data: null,
  }),
  actions: {
    async getPortfolio() {
      await axios.get(`${apiUrl}/v1/get-portfolio`).then((response) => {
        this.data = response.data.data;
        console.log(this.data.profile);
      });
    },
  },
});
