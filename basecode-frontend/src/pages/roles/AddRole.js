import { createRole } from "../../services/roles.js";
import { getPermissions } from "../../services/permissions.js";
import { showToast } from "../../utils/toast.js";

export default async function AddRole() {

  const container = document.createElement("div");

  const res = await getPermissions();
  const permissions = res.data || [];

  container.innerHTML = `
    <div class="container-fluid card py-4">

       <!-- TITLE -->
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>Create Role</strong>

          <button class="btn btn-sm btn-outline-secondary col-2" id="backBtn"
          onclick="location.hash='#/roles'">
            Back
          </button>
     </div>

      <div class="card p-3">

        <form id="roleForm">

        
            <label for="name" class="form-label fw-semibold mt-2">
              Role Name
            </label>
          <input class="form-control mb-2" id="name" placeholder="Role Name" />

            <label for="name" class="form-label fw-semibold mt-2">
              Role Slug
            </label>
          <input class="form-control mb-2" id="slug" placeholder="Slug" />

            <label for="name" class="form-label fw-semibold mt-2">
              Role Description
            </label>
          <textarea class="form-control mb-2" id="description" placeholder="Description"></textarea>

          <label class="form-label fw-semibold">Permissions</label>

          <div class="border rounded p-2 mb-3" style="max-height: 220px; overflow-y: auto;">

            ${permissions.map(p => `
              <div class="form-check d-flex align-items-center gap-2 py-1">

                <input
                  class="form-check-input perm-check"
                  type="checkbox"
                  value="${p.id}"
                  id="perm_${p.id}"
                />

                <label class="form-check-label w-100" for="perm_${p.id}">
                  <span class="badge bg-secondary me-2">${p.slug}</span>
                  <small class="text-muted">${p.name || ""}</small>
                </label>

              </div>
            `).join("")}

          </div>

          <button class="btn btn-success w-100 mt-4">
            Create Role
          </button>

        </form>

      </div>

    </div>
  `;

  // ✅ FIXED SUBMIT LOGIC
  container.querySelector("#roleForm").addEventListener("submit", async (e) => {

    e.preventDefault();

    // 🔥 GET CHECKBOX VALUES (NOT select anymore)
    const selected = Array.from(
      container.querySelectorAll(".perm-check:checked")
    ).map(el => el.value);

    const data = {
      name: container.querySelector("#name").value,
      slug: container.querySelector("#slug").value,
      description: container.querySelector("#description").value,
      permissions: selected
    };

    try {
      await createRole(data);

      showToast("Role created successfully");

      location.hash = "#/roles";

    } catch (err) {
      console.error(err);
      showToast("Failed to create role", "error");
    }
  });

  return container;
}