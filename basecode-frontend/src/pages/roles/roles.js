import { getRoles } from "../../services/roles.js";
import Filter from "../../components/Filter.js";
import { isLoggedIn, getUser } from "../../services/session.js";

export default async function RolePage() {

  const container = document.createElement("div");

  const loggedIn = isLoggedIn();
  const currentUser = getUser();

  if (!loggedIn) {
    container.innerHTML = `
      <div class="container-fluid mt-3">
        <div class="alert alert-danger">
          You are not logged in. Please login to access roles.
        </div>
      </div>
    `;
    return container;
  }

  const permissions = currentUser?.permissions || [];

  const canCreate =
    permissions.includes("roles.create") ||
    permissions.some(p => p.name === "roles.create");

  let roles = [];
  let filteredRoles = [];

  // =============================
  // FILTER FUNCTION
  // =============================
  function applyFilter(query) {

    const q = (query || "").toLowerCase();

    if (!q) {
      filteredRoles = roles;
    } else {
      filteredRoles = roles.filter(role =>
        (role.name || "").toLowerCase().includes(q) ||
        (role.slug || "").toLowerCase().includes(q)
      );
    }

    render();
  }

  // =============================
  // LOAD ROLES
  // =============================
  async function loadRoles() {

    try {
      const res = await getRoles();

      if (!res || res.message === "Forbidden") {
        container.innerHTML = `
          <div class="container-fluid mt-3">
            <div class="alert alert-danger">
              You do not have permission to view roles.
            </div>
          </div>
        `;
        return;
      }

      roles = res.data ?? [];
      filteredRoles = roles;

      render();

    } catch (error) {

      console.error("Load roles error:", error);

      container.innerHTML = `
        <div class="container-fluid mt-3">
          <div class="alert alert-danger">
            Failed to load roles.
          </div>
        `;
    }
  }

  // =============================
  // CLEAN EVENT (IMPORTANT FIX)
  // =============================
  function bindFilterListener() {

    // remove old listener to prevent stacking bugs
    window.removeEventListener("app:filter", handleFilterEvent);

    window.addEventListener("app:filter", handleFilterEvent);
  }

  function handleFilterEvent(e) {

    const { query, type } = e.detail;

    if (type !== "roles") return;

    applyFilter(query);
  }

  // =============================
  // RENDER UI
  // =============================
  function render() {

    container.innerHTML = `
      <div id="filter-container" class="container-fluid card mb-4"></div>

      <div class="container-fluid card vh-100">

        <div class="card-header">
          <strong>Roles</strong>
        </div>

        <div class="card-body">

          <div class="border rounded p-3 bg-light mb-3">
            View, search and manage role permissions and access levels.
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">

            <small class="text-muted">
              Total Roles: ${filteredRoles.length}
            </small>

            <div class="d-flex gap-2">

              <button class="btn btn-outline-secondary" id="columnBtn">
                Columns
              </button>

              <button
                class="btn btn-success"
                id="addRoleBtn"
                ${!canCreate ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
              >
                Create Role
              </button>

            </div>

          </div>

          <div class="card shadow-sm">

            <div class="table-responsive">

              <table class="table table-hover mb-0">

                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Permission Count</th>
                  </tr>
                </thead>

                <tbody>
                  ${
                    filteredRoles.length
                      ? filteredRoles.map(role => `
                          <tr class="role-row" data-id="${role.id}" style="cursor:pointer;">
                            <td>${role.id}</td>
                            <td>${role.name}</td>
                            <td>${role.slug}</td>
                            <td>${role.description || "-"}</td>
                            <td>${role.permissions?.length || 0}</td>
                          </tr>
                        `).join("")
                      : `
                        <tr>
                          <td colspan="5" class="text-center">
                            No roles found
                          </td>
                        </tr>
                      `
                  }
                </tbody>

              </table>

            </div>

          </div>

        </div>

      </div>
    `;

    // =============================
    // FILTER COMPONENT
    // =============================
    container
      .querySelector("#filter-container")
      .appendChild(Filter());

    // =============================
    // BUTTONS
    // =============================
    container.querySelector("#addRoleBtn")?.addEventListener("click", () => {
      if (!canCreate) return;
      location.hash = "#/roles/add";
    });

    container.querySelectorAll(".role-row").forEach(row => {
      row.addEventListener("click", () => {
        location.hash = `#/roles/view/${row.dataset.id}`;
      });
    });
  }

  // =============================
  // INIT
  // =============================
  await loadRoles();

  // IMPORTANT FIX: bind AFTER setup safely
  bindFilterListener();

  return container;
}