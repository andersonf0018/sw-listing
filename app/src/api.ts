import axios from "axios";
import { environment } from "./environment";

export const apiUrl = environment.apiUrl;

export const api = axios.create({
  baseURL: apiUrl,
});
