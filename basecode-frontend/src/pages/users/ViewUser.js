import { getUser as getUserById, deleteUser } from "../../services/users.js";
import { getRoles } from "../../services/roles.js";
import { hasPermission } from "../../services/currentUser_permission.js";
import { isLoggedIn, getUser } from "../../services/session.js";

export default async function ViewUser() {

  const container = document.createElement("div");
  container.className = "container-fluid card vh-100 p-3";

  // ---------------------------
  // LOGIN CHECK
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

  const id = location.hash.split("/")[3];
  container.innerHTML = `<div class="text-muted">Loading user...</div>`;

  try {

    const [userRes, rolesRes] = await Promise.all([
      getUserById(id),
      getRoles()
    ]);

    // ---------------------------
    // USER DATA
    // ---------------------------
    const user = userRes.user || userRes.data || userRes;

    const roles = Array.isArray(rolesRes.data)
      ? rolesRes.data
      : rolesRes || [];

    const roleName =
      user.role?.name ||
      roles.find(r => r.id == user.role?.id)?.name ||
      "-";

    // ---------------------------
    // LOGIN RESPONSE PERMISSIONS
    // ---------------------------
    const permissions = currentUser?.permissions || [];

    const hasUpdatePermission =
      permissions.includes("users.update") ||
      permissions.some(p => p.name === "users.update");

    const hasDeletePermission =
      permissions.includes("users.delete") ||
      permissions.some(p => p.name === "users.delete");

    console.log("users.update:", hasUpdatePermission);
    console.log("users.delete:", hasDeletePermission);

    container.innerHTML = `
      <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>User Details</strong>

          <button class="btn btn-sm btn-outline-secondary col-2"
            onclick="location.hash='#/users'">
            Back
          </button>
        </div>

        <!-- BODY -->
        <div class="card-body">

          <div class="row mb-2">
            <div class="col-md-3 text-muted">ID</div>
            <div class="col-md-9">${user.id}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-3 text-muted">Name</div>
            <div class="col-md-9">
              ${user.first_name || ""} ${user.last_name || ""}
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-3 text-muted">Email</div>
            <div class="col-md-9">${user.email || "-"}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-3 text-muted">Role</div>
            <div class="col-md-9">${roleName}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-3 text-muted">Status</div>
            <div class="col-md-9">
              <span class="badge ${
                user.status === "active"
                  ? "bg-success"
                  : "bg-secondary"
              }">
                ${user.status || "inactive"}
              </span>
            </div>
          </div>

          <!-- ACTION BUTTONS -->
          <div class="d-flex gap-2 mt-3">

            <!-- EDIT BUTTON -->
            <button
              class="btn btn-sm btn-warning col-2"
              id="editBtn"
              ${!hasUpdatePermission ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
            >
              Edit
            </button>

            <!-- DELETE BUTTON -->
            <button
              class="btn btn-sm btn-danger col-2"
              id="deleteBtn"
              ${!hasDeletePermission ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
            >
              Delete
            </button>

          </div>

        </div>
      </div>
    `;

    // ---------------------------
    // EDIT ACTION
    // ---------------------------
    container.querySelector("#editBtn")?.addEventListener("click", () => {
      if (!hasUpdatePermission) return;
      location.hash = `#/users/edit/${user.id}`;
    });

    // ---------------------------
    // DELETE ACTION
    // ---------------------------
    container.querySelector("#deleteBtn")?.addEventListener("click", async () => {

      if (!hasDeletePermission) return;

      if (!confirm(`Delete user "${user.first_name} ${user.last_name}"?`)) {
        return;
      }

      try {
        await deleteUser(user.id);

        alert("User deleted successfully.");
        location.hash = "#/users";

      } catch (error) {
        console.error(error);
        alert("Failed to delete user.");
      }

    });

  } catch (err) {
    console.error(err);

    container.innerHTML = `
      <div class="alert alert-danger">
        Failed to load user
      </div>
    `;
  }

  return container;
}