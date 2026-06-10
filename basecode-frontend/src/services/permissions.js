// src/services/permissions.js

import { apiRequest } from "./api.js";

export async function getPermissions() {
  return apiRequest("/permissions");
}

