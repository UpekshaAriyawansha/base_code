import { getRole, deleteRole } from "../../services/roles.js";
import { showToast } from "../../utils/toast.js";
import { isLoggedIn, getUser } from "../../services/session.js";

export default async function ViewRole() {

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

  const canUpdate =
    permissions.includes("roles.update") ||
    permissions.some(p => p.name === "roles.update");

  const canDelete =
    permissions.includes("roles.delete") ||
    permissions.some(p => p.name === "roles.delete");

  const id = location.hash.split("/")[3];

  console.log("Role ID:", id);

  const response = await getRole(id);

  console.log("API Response:", response);

  const role = response.data || response;

  console.log("Role Data:", role);
  console.log("Permissions:", role.permissions);
  console.log("Permission Count:", role.permissions?.length);

  container.innerHTML = `

    <div class="container-fluid profile-card pt-4 pb-4 px-4">

      <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>Role Details</strong>

          <button class="btn btn-sm btn-outline-secondary col-2" id="backBtn">
            Back
          </button>
        </div>

        <div class="card-body">

          <table class="table table-bordered">

            <tr>
              <th width="250">ID</th>
              <td>${role.id || "-"}</td>
            </tr>

            <tr>
              <th>Name</th>
              <td>${role.name || "-"}</td>
            </tr>

            <tr>
              <th>Slug</th>
              <td>${role.slug || "-"}</td>
            </tr>

            <tr>
              <th>Description</th>
              <td>${role.description || "-"}</td>
            </tr>

            <tr>
              <th>Permission Count</th>
              <td>${role.permissions?.length || 0}</td>
            </tr>

            <tr>
              <th>Permissions</th>
              <td>
                ${
                  role.permissions?.length
                    ? role.permissions.map(permission => `
                        <span class="badge bg-secondary me-1 mb-1 px-1 py-1">
                          ${permission}
                        </span>
                      `).join("")
                    : "-"
                }
              </td>
            </tr>

          </table>

          <!-- ACTION BUTTONS -->
          <div class="d-flex gap-2 mt-3">

            <!-- EDIT -->
            <button
              class="btn btn-sm btn-warning col-2"
              id="editBtn"
              ${!canUpdate ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
            >
              Edit
            </button>

            <!-- DELETE -->
            <button
              class="btn btn-sm btn-danger col-2"
              id="deleteBtn"
              ${!canDelete ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
            >
              Delete
            </button>

          </div>

        </div>

      </div>

    </div>
  `;

  // ---------------------------
  // EDIT ROLE
  // ---------------------------
  container.querySelector("#editBtn")?.addEventListener("click", () => {
    if (!canUpdate) return;
    location.hash = `#/roles/edit/${role.id}`;
  });

  // ---------------------------
  // DELETE ROLE
  // ---------------------------
  container.querySelector("#deleteBtn")?.addEventListener("click", async () => {

    if (!canDelete) return;

    if (!confirm("Delete this role?")) return;

    try {
      await deleteRole(role.id);

      showToast("Role deleted");

      location.hash = "#/roles";

    } catch (error) {
      console.error(error);
      showToast("Failed to delete role", "danger");
    }

  });

  // ---------------------------
  // BACK BUTTON
  // ---------------------------
  container.querySelector("#backBtn")?.addEventListener("click", () => {
    location.hash = "#/roles";
  });

  return container;
}