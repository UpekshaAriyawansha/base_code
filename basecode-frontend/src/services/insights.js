import { apiRequest } from "./api.js";

export function getInsights() {
  return apiRequest("/insights");
}