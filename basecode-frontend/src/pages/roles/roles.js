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
    permissions.some((p) => p.name === "roles.create");

  let roles = [];
  let filteredRoles = [];

  // Column visibility state
  let visibleColumns = {
    id: true,
    name: true,
    slug: true,
    description: true,
    permissions: true,
  };

  // =============================
  // FILTER FUNCTION
  // =============================
  function applyFilter(query) {
    const q = (query || "").toLowerCase();

    if (!q) {
      filteredRoles = roles;
    } else {
      filteredRoles = roles.filter(
        (role) =>
          (role.name || "").toLowerCase().includes(q) ||
          (role.slug || "").toLowerCase().includes(q)
      );
    }

    render();
  }

  // =============================
  // APPLY COLUMN VISIBILITY
  // =============================
  function applyColumnVisibility() {
    Object.entries(visibleColumns).forEach(
      ([column, visible]) => {
        container
          .querySelectorAll(
            `[data-column="${column}"]`
          )
          .forEach((el) => {
            el.style.display = visible
              ? ""
              : "none";
          });
      }
    );
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
      console.error(
        "Load roles error:",
        error
      );

      container.innerHTML = `
        <div class="container-fluid mt-3">
          <div class="alert alert-danger">
            Failed to load roles.
          </div>
        </div>
      `;
    }
  }

  // =============================
  // FILTER LISTENER
  // =============================
  function bindFilterListener() {
    window.removeEventListener(
      "app:filter",
      handleFilterEvent
    );

    window.addEventListener(
      "app:filter",
      handleFilterEvent
    );
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
      <div id="filter-container"
           class="container-fluid card mb-4 profile-card">
      </div>

      <div class="container-fluid card vh-100 profile-card">

        <div class="card-header">
          <strong>Roles</strong>
        </div>

        <div class="card-body">

          <div class="border rounded p-3 bg-light mb-3">
            View, search and manage role permissions
            and access levels.
          </div>

          <div
            class="d-flex justify-content-between
                   align-items-center mb-3">

            <small class="text-muted">
              Total Roles:
              ${filteredRoles.length}
            </small>

            <div class="d-flex gap-2">

              <!-- Columns Button -->
              <div class="position-relative">

                <button
                  class="btn btn-outline-secondary"
                  id="columnBtn"
                >
                  Columns
                </button>

                <div
                  id="columnPopup"
                  class="card shadow p-3
                         position-absolute end-0 mt-2"
                  style="
                    width:230px;
                    display:none;
                    z-index:1000;
                  "
                >

                  <div class="fw-bold mb-2">
                    Customize Columns
                  </div>

                  ${Object.entries(
                    visibleColumns
                  )
                    .map(
                      ([key, checked]) => `
                    <div class="form-check">

                      <input
                        class="form-check-input
                               column-checkbox"
                        type="checkbox"
                        value="${key}"
                        id="col-${key}"
                        ${
                          checked
                            ? "checked"
                            : ""
                        }
                      >

                      <label
                        class="form-check-label"
                        for="col-${key}"
                      >
                        ${
                          key ===
                          "permissions"
                            ? "Permission Count"
                            : key
                                .charAt(0)
                                .toUpperCase() +
                              key.slice(1)
                        }
                      </label>

                    </div>
                  `
                    )
                    .join("")}

                </div>

              </div>

              <button
                class="btn btn-success"
                id="addRoleBtn"
                ${
                  !canCreate
                    ? `disabled
                       style="
                         opacity:0.5;
                         cursor:not-allowed;
                       "`
                    : ""
                }
              >
                Create Role
              </button>

            </div>

          </div>

          <div class="card shadow-sm">

            <div class="table-responsive">

              <table
                class="table table-hover mb-0"
              >

                <thead class="table-light">
                  <tr>
                    <th data-column="id">
                      ID
                    </th>

                    <th data-column="name">
                      Name
                    </th>

                    <th data-column="slug">
                      Slug
                    </th>

                    <th
                      data-column="description"
                    >
                      Description
                    </th>

                    <th
                      data-column="permissions"
                    >
                      Permission Count
                    </th>
                  </tr>
                </thead>

                <tbody>

                  ${
                    filteredRoles.length
                      ? filteredRoles
                          .map(
                            (role) => `
                      <tr
                        class="role-row"
                        data-id="${role.id}"
                        style="cursor:pointer;"
                      >

                        <td data-column="id">
                          ${role.id}
                        </td>

                        <td data-column="name">
                          ${role.name}
                        </td>

                        <td data-column="slug">
                          ${role.slug}
                        </td>

                        <td
                          data-column="description"
                        >
                          ${
                            role.description ||
                            "-"
                          }
                        </td>

                        <td
                          data-column="permissions"
                        >
                          ${
                            role.permissions
                              ?.length || 0
                          }
                        </td>

                      </tr>
                    `
                          )
                          .join("")
                      : `
                        <tr>
                          <td
                            colspan="5"
                            class="text-center"
                          >
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
      .querySelector(
        "#filter-container"
      )
      .appendChild(Filter());

    // Apply saved column visibility
    applyColumnVisibility();

    // =============================
    // COLUMN POPUP
    // =============================
    const columnBtn =
      container.querySelector(
        "#columnBtn"
      );

    const popup =
      container.querySelector(
        "#columnPopup"
      );

    columnBtn?.addEventListener(
      "click",
      (e) => {
        e.stopPropagation();

        popup.style.display =
          popup.style.display === "block"
            ? "none"
            : "block";
      }
    );

    container
      .querySelectorAll(
        ".column-checkbox"
      )
      .forEach((checkbox) => {
        checkbox.addEventListener(
          "change",
          () => {
            visibleColumns[
              checkbox.value
            ] = checkbox.checked;

            applyColumnVisibility();
          }
        );
      });

    document.onclick = (e) => {
      if (
        popup &&
        !popup.contains(e.target) &&
        e.target !== columnBtn
      ) {
        popup.style.display = "none";
      }
    };

    // =============================
    // BUTTONS
    // =============================
    container
      .querySelector("#addRoleBtn")
      ?.addEventListener(
        "click",
        () => {
          if (!canCreate) return;

          location.hash =
            "#/roles/add";
        }
      );

    container
      .querySelectorAll(".role-row")
      .forEach((row) => {
        row.addEventListener(
          "click",
          () => {
            location.hash = `#/roles/view/${row.dataset.id}`;
          }
        );
      });
  }

  // =============================
  // INIT
  // =============================
  await loadRoles();

  bindFilterListener();

  return container;
}
