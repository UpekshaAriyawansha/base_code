import { apiRequest } from "./api.js";

// GET ALL ROLES
export function getRoles() {
  return apiRequest("/roles");
}

// GET SINGLE ROLE
export function getRole(id) {
  return apiRequest(`/roles/${id}`);
}

// CREATE ROLE
export function createRole(data) {
  return apiRequest("/roles", {
    method: "POST",
    body: JSON.stringify(data)
  });
}

// UPDATE ROLE
export function updateRole(id, data) {
  return apiRequest(`/roles/${id}`, {
    method: "PUT",
    body: JSON.stringify(data)
  });
}

// DELETE ROLE
export function deleteRole(id) {
  return apiRequest(`/roles/${id}`, {
    method: "DELETE"
  });
}