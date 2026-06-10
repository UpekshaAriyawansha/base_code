import { getUser as getUserById, updateUser } from "../../services/users.js";
import { getRoles } from "../../services/roles.js";
import { isLoggedIn, getUser } from "../../services/session.js";

export default async function EditUser() {

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

  // users.update permission
  const canUpdate =
    permissions.includes("users.update") ||
    permissions.some(p => p.name === "users.update");

  // roles.assign permission (NEW)
  const canAssignRole =
    permissions.includes("roles.assign") ||
    permissions.some(p => p.name === "roles.assign");

  const id = location.hash.split("/")[3];

  const [userRes, rolesRes] = await Promise.all([
    getUserById(id),
    getRoles()
  ]);

  const user = userRes.user || userRes.data || userRes;
  const roles = Array.isArray(rolesRes.data) ? rolesRes.data : [];

  console.log("User Data:", user);
  console.log("Roles Data:", roles);
  console.log("User Role ID:", user.role_id);

  container.innerHTML = `
    <div class="container-fluid card py-4">

      <!-- HEADER -->
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Edit User</strong>

        <button class="btn btn-sm btn-outline-secondary col-2"
          onclick="location.hash='#/users'">
          Back
        </button>
      </div>

      <div class="card p-3">

        <form id="editUserForm">

          <!-- FIRST NAME -->
          <label class="form-label fw-semibold mt-2">First Name</label>
          <input class="form-control mb-2" id="first_name"
            value="${user.first_name || ""}"
            ${!canUpdate ? "disabled" : ""} />

          <!-- LAST NAME -->
          <label class="form-label fw-semibold mt-2">Last Name</label>
          <input class="form-control mb-2" id="last_name"
            value="${user.last_name || ""}"
            ${!canUpdate ? "disabled" : ""} />

          <!-- EMAIL -->
          <label class="form-label fw-semibold mt-2">Email</label>
          <input class="form-control mb-2" id="email"
            value="${user.email || ""}"
            ${!canUpdate ? "disabled" : ""} />

          <!-- PASSWORD -->
          <label class="form-label fw-semibold mt-2">New Password</label>
          <input type="password" class="form-control mb-2" id="password"
            placeholder="Leave blank to keep current password"
            ${!canUpdate ? "disabled" : ""} />

          <!-- STATUS -->
          <label class="form-label fw-semibold mt-2">Status</label>
          <select class="form-control mb-2" id="status"
            ${!canUpdate ? "disabled" : ""}>
            <option value="active" ${user.status === "active" ? "selected" : ""}>Active</option>
            <option value="inactive" ${user.status === "inactive" ? "selected" : ""}>Inactive</option>
          </select>

          <!-- ROLE -->
          <label class="form-label fw-semibold mt-2">Select Role</label>
          <select class="form-control mb-2" id="role_id"
            ${!canUpdate || !canAssignRole ? "disabled" : ""}>

            <option value="">Select Role</option>

            ${roles.map(role => `
              <option
                value="${role.id}"
                ${Number(user.role?.id) === Number(role.id) ? "selected" : ""}
              >
                ${role.name}
              </option>
            `).join("")}

          </select>

          <!-- SUBMIT -->
          <button
            class="btn btn-success w-100 mt-4"
            ${!canUpdate ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
          >
            Update User
          </button>

          ${!canUpdate ? `
            <div class="alert alert-warning mt-3">
              You do not have permission to update users.
            </div>
          ` : ""}

        </form>

      </div>

    </div>
  `;

  // ---------------------------
  // FORM SUBMIT
  // ---------------------------
  container.querySelector("#editUserForm")
    .addEventListener("submit", async (e) => {

      e.preventDefault();

      if (!canUpdate) return;

      const data = {
        first_name: container.querySelector("#first_name").value,
        last_name: container.querySelector("#last_name").value,
        email: container.querySelector("#email").value,
        status: container.querySelector("#status").value
      };

      // only include role if allowed
      if (canAssignRole) {
        data.role_id = container.querySelector("#role_id").value;
      }

      const password = container.querySelector("#password").value.trim();

      if (password) {
        data.password = password;
      }

      try {
        await updateUser(id, data);
        location.hash = "#/users";
      } catch (error) {
        console.error(error);
        alert("Failed to update user");
      }
    });

  return container;
}