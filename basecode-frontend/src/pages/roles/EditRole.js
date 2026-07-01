import { getRole, updateRole } from "../../services/roles.js";
import { getPermissions } from "../../services/permissions.js";
import { showToast } from "../../utils/toast.js";
import { isLoggedIn, getUser } from "../../services/session.js";

export default async function EditRole() {

  const container = document.createElement("div");

  // ---------------------------
  // LOGIN RESPONSE
  // ---------------------------
  const loggedIn = isLoggedIn();
  const currentUser = getUser();

  console.log("Login User:", currentUser);

  if (!loggedIn) {
    container.innerHTML = `
      <div class="alert alert-danger">
        You are not logged in. Please login first.
      </div>
    `;
    return container;
  }

  const permissions = currentUser?.permissions || [];

  const canAssignPermissions =
    permissions.includes("permissions.assign") ||
    permissions.some(p => p.name === "permissions.assign");

  const id = location.hash.split("/")[3];

  // 🔥 FETCH ROLE
  const roleRes = await getRole(id);
  const role = roleRes.data || roleRes;

  // 🔥 FETCH ALL PERMISSIONS
  const permRes = await getPermissions();
  const allPermissions = permRes.data || [];

  container.innerHTML = `
    <div class="container-fluid card py-4 profile-card pt-4 pb-4 px-4">

      <!-- TITLE -->

      <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>Edit Role</strong>

          <button class="btn btn-sm btn-outline-secondary col-2" id="backBtn"
          onclick="location.hash='#/roles'">
            Back
          </button>
     </div>



      <div class="card p-3">

        <form id="roleForm">

          <!-- ROLE NAME -->
          <label class="form-label fw-semibold mt-2">Role Name</label>
          <input class="form-control mb-2" id="name"
            value="${role.name || ""}" />

          <!-- ROLE SLUG -->
          <label class="form-label fw-semibold mt-2">Role Slug</label>
          <input class="form-control mb-2" id="slug"
            value="${role.slug || ""}" />

          <!-- ROLE DESCRIPTION -->
          <label class="form-label fw-semibold mt-2">Role Description</label>
          <textarea class="form-control mb-2" id="description">${role.description || ""}</textarea>

          <!-- 🔥 PERMISSIONS SECTION -->
          <label class="form-label fw-semibold mt-2">Permissions</label>

          <div class="border rounded p-2 mb-3" style="max-height: 220px; overflow-y: auto;">

            ${allPermissions.map(p => `

              <div class="form-check d-flex align-items-center gap-2 py-1">

                <input
                  class="form-check-input perm-check"
                  type="checkbox"
                  value="${p.id}"
                  id="perm_${p.id}"
                  ${role.permissions?.includes(p.slug) ? "checked" : ""}
                  ${!canAssignPermissions ? "disabled" : ""}
                />

                <label class="form-check-label w-100" for="perm_${p.id}">
                  <span class="badge bg-secondary me-2">${p.slug}</span>
                  <small class="text-muted">${p.name || ""}</small>
                </label>

              </div>

            `).join("")}

          </div>

          <button
            class="btn btn-success w-100 mt-4"
            ${!canAssignPermissions ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
          >
            Update Role
          </button>

          ${!canAssignPermissions ? `
            <div class="alert alert-warning mt-3">
              You do not have permission to assign permissions.
            </div>
          ` : ""}

        </form>

      </div>

    </div>
  `;

  // ---------------------------
  // SUBMIT HANDLER
  // ---------------------------
  container.querySelector("#roleForm")
    .addEventListener("submit", async (e) => {

      e.preventDefault();

      if (!canAssignPermissions) return;

      const selectedPermissions = Array.from(
        container.querySelectorAll(".perm-check:checked")
      ).map(el => el.value);

      const data = {
        name: container.querySelector("#name").value,
        slug: container.querySelector("#slug").value,
        description: container.querySelector("#description").value,
        permissions: selectedPermissions
      };

      try {
        await updateRole(id, data);

        showToast("Role updated successfully");

        location.hash = "#/roles";

      } catch (err) {
        console.error(err);
        showToast("Update failed", "error");
      }
    });

  return container;
}