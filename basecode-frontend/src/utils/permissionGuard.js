import { hasPermission } from "../services/currentUser_permission.js";

export function requirePermission(permission) {

  if (hasPermission(permission)) {
    return true;
  }

  const div = document.createElement("div");

  div.innerHTML = `
    <div class="alert alert-danger">
      Access Denied
    </div>
  `;

  return div;
}