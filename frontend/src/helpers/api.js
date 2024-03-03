import axios from "axios";

const api = axios.create({
  baseURL: "https://d624-103-80-116-210.ngrok-free.app"
});

export default api;
