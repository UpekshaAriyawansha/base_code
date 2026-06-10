import { createUser } from "../../services/users.js";
import { getRoles } from "../../services/roles.js";
import { showToast } from "../../utils/toast.js";

export default async function AddUser() {

  const container = document.createElement("div");

  container.innerHTML = `
    <div class="container-fluid card py-4">

            <!-- TITLE -->
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Create User</strong>

        <button class="btn btn-sm btn-outline-secondary col-2"
          onclick="location.hash='#/users'">
          Back
        </button>
      </div>        

      <div class="card p-3">
        <div id="formWrapper">Loading form...</div>
      </div>
    </div>
  `;

  try {

  // FETCH ROLES
const rolesRes = await getRoles();

console.log("ROLES RESPONSE:", rolesRes);

const roles = rolesRes?.data || [];

console.log("PARSED ROLES:", roles);

    // ❗ get form wrapper
    const formWrapper = container.querySelector("#formWrapper");

    // ✅ render form
    formWrapper.innerHTML = `
      <form id="addUserForm">

        <label for="name" class="form-label fw-semibold mt-2">
              First Name
        </label>
        <input class="form-control mb-2" id="first_name" placeholder="First Name" />

        <label for="name" class="form-label fw-semibold mt-2">
              Last Name
        </label>
        <input class="form-control mb-2" id="last_name" placeholder="Last Name" />

        <label for="name" class="form-label fw-semibold mt-2">
              Email
        </label>
        <input class="form-control mb-2" id="email" placeholder="Email" />

            <label for="name" class="form-label fw-semibold mt-2">
              Password
            </label>
        <input class="form-control mb-2" id="password" placeholder="Password" type="password" />

        <label for="name" class="form-label fw-semibold mt-2">
              Status
            </label>

        <select class="form-control mb-2" id="status">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>

        <label for="name" class="form-label fw-semibold mt-2">
              Select Role
        </label>

        <select class="form-control mb-2" id="role_id">
          <option value="">Select Role</option>
          ${roles.map(role => `
            <option value="${role.id}">
              ${role.name}
            </option>
          `).join("")}
        </select>

        <button class="btn btn-success mt-4 w-100">
          Create User
        </button>

      </form>
    `;

    // ✅ bind submit AFTER render
    container.querySelector("#addUserForm").addEventListener("submit", async (e) => {
      e.preventDefault();

      const data = {
        first_name: container.querySelector("#first_name").value,
        last_name: container.querySelector("#last_name").value,
        email: container.querySelector("#email").value,
        password: container.querySelector("#password").value,
        status: container.querySelector("#status").value,
        role_id: container.querySelector("#role_id").value
      };

      try {
        await createUser(data);

        showToast("User created successfully");

        location.hash = "#/users";

      } catch (err) {
        console.error(err);
        showToast("Failed to create user", "error");
      }
    });

  } catch (err) {

    console.error(err);

    container.innerHTML = `
      <div class="alert alert-danger">
        Failed to load roles / form
      </div>
    `;
  }

  return container;
}