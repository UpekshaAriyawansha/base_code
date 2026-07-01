import { getUsers } from "../../services/users.js";
import Filter from "../../components/Filter.js";
import { isLoggedIn, getUser } from "../../services/session.js";
import * as bootstrap from "bootstrap";

export default async function Users() {
  const container = document.createElement("div");

  const loggedIn = isLoggedIn();
  const user = getUser();

  console.log("Login Response:", user);

  if (!loggedIn) {
    container.innerHTML = `
      <div class="container-fluid mt-3">
        <div class="alert alert-danger">
          You are not logged in. Please login first.
        </div>
      </div>
    `;
    return container;
  }

  let currentPage = 1;
  const perPage = 10;
  let allUsers = [];
  let filteredUsers = [];

  let visibleColumns = {
    id: true,
    name: true,
    email: true,
    role: true,
    status: true,
  };

  function applyFilter(query) {
    if (!query) {
      filteredUsers = allUsers;
    } else {
      filteredUsers = allUsers.filter(
        (u) =>
          `${u.first_name || ""} ${u.last_name || ""}`
            .toLowerCase()
            .includes(query.toLowerCase()) ||
          (u.email || "")
            .toLowerCase()
            .includes(query.toLowerCase())
      );
    }

    currentPage = 1;
    renderTable();
  }

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

  function renderTable() {
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;

    const users = filteredUsers.slice(
      start,
      end
    );

    const totalPages =
      Math.ceil(
        filteredUsers.length / perPage
      ) || 1;

    const hasCreatePermission =
      user?.permissions?.includes(
        "users.create"
      ) ||
      user?.permissions?.some(
        (p) => p.name === "users.create"
      );

    container.innerHTML = `
      <div id="filter-container" class="container-fluid card mb-4 profile-card"></div>

      <div class="container-fluid card vh-100 profile-card">

        <div class="card-header profile-card">
          <strong>Users</strong>
        </div>

        <div class="card-body">

          <div class="border rounded p-3 bg-light mb-3">
            Logged in as:
            ${user?.name || user?.email || "Unknown"}
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">

            <small class="text-muted">
              Showing ${users.length} of ${filteredUsers.length} users
            </small>

            <div class="d-flex gap-2">

              <button
                class="btn btn-outline-secondary"
                id="columnBtn"
              >
                Columns
              </button>

              <button
                class="btn btn-success"
                id="createUserBtn"
                ${
                  !hasCreatePermission
                    ? "disabled"
                    : ""
                }
              >
                Create User
              </button>

            </div>

          </div>

          <div class="card shadow-sm">

            <div class="table-responsive">

              <table class="table table-hover mb-0">

                <thead class="table-light">
                  <tr>
                    <th data-column="id">ID</th>
                    <th data-column="name">Name</th>
                    <th data-column="email">Email</th>
                    <th data-column="role">Role</th>
                    <th data-column="status">Status</th>
                  </tr>
                </thead>

                <tbody>

                  ${users
                    .map(
                      (u) => `
                    <tr
                      class="user-row"
                      data-id="${u.id}"
                      style="cursor:pointer"
                    >
                      <td data-column="id">
                        ${u.id}
                      </td>

                      <td data-column="name">
                        ${u.first_name || ""}
                        ${u.last_name || ""}
                      </td>

                      <td data-column="email">
                        ${u.email || "-"}
                      </td>

                      <td data-column="role">
                        ${u.role?.name || "-"}
                      </td>

                      <td data-column="status">
                        <span
                          class="badge ${
                            u.status === "active"
                              ? "bg-success"
                              : "bg-secondary"
                          }"
                        >
                          ${
                            u.status ||
                            "inactive"
                          }
                        </span>
                      </td>
                    </tr>
                  `
                    )
                    .join("")}

                </tbody>

              </table>

            </div>

          </div>

          <div class="d-flex justify-content-between align-items-center mt-3">

            <button
              class="btn btn-outline-primary"
              id="prevBtn"
              ${
                currentPage === 1
                  ? "disabled"
                  : ""
              }
            >
              Prev
            </button>

            <div>
              Page ${currentPage}
              of
              ${totalPages}
            </div>

            <button
              class="btn btn-outline-primary"
              id="nextBtn"
              ${
                currentPage >= totalPages
                  ? "disabled"
                  : ""
              }
            >
              Next
            </button>

          </div>

        </div>

      </div>

      <!-- Column Modal -->
      <div
        class="modal fade"
        id="columnModal"
        tabindex="-1"
      >
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">
                Customize Columns
              </h5>

              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
              ></button>
            </div>

            <div class="modal-body">

              ${Object.entries(
                visibleColumns
              )
                .map(
                  ([key, checked]) => `
                <div class="form-check mb-2">

                  <input
                    class="form-check-input column-checkbox"
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
                      key
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

            <div class="modal-footer">

              <button
                class="btn btn-primary"
                id="applyColumnsBtn"
              >
                Apply
              </button>

            </div>

          </div>
        </div>
      </div>
    `;

    container
      .querySelector(
        "#filter-container"
      )
      .appendChild(Filter());

    applyColumnVisibility();

    // Open modal manually
    const columnBtn =
      container.querySelector(
        "#columnBtn"
      );

    const modalElement =
      container.querySelector(
        "#columnModal"
      );

    let modal;

    columnBtn?.addEventListener(
      "click",
      () => {
        modal =
          bootstrap.Modal.getOrCreateInstance(
            modalElement
          );

        modal.show();
      }
    );

    // Apply columns
    container
      .querySelector(
        "#applyColumnsBtn"
      )
      ?.addEventListener(
        "click",
        () => {
          container
            .querySelectorAll(
              ".column-checkbox"
            )
            .forEach((cb) => {
              visibleColumns[
                cb.value
              ] = cb.checked;
            });

          applyColumnVisibility();

          modal?.hide();
        }
      );

    // Row click
    container
      .querySelectorAll(
        ".user-row"
      )
      .forEach((row) => {
        row.addEventListener(
          "click",
          () => {
            location.hash = `#/users/view/${row.dataset.id}`;
          }
        );
      });

    // Create button
    container
      .querySelector(
        "#createUserBtn"
      )
      ?.addEventListener(
        "click",
        (e) => {
          if (
            !hasCreatePermission
          ) {
            e.preventDefault();
            return;
          }

          location.hash =
            "#/users/add";
        }
      );

    // Pagination
    container
      .querySelector("#prevBtn")
      ?.addEventListener(
        "click",
        () => {
          currentPage--;
          renderTable();
        }
      );

    container
      .querySelector("#nextBtn")
      ?.addEventListener(
        "click",
        () => {
          currentPage++;
          renderTable();
        }
      );
  }

  try {
    const res = await getUsers();

    allUsers = Array.isArray(res)
      ? res
      : res.data || [];

    filteredUsers = allUsers;

    renderTable();
  } catch (err) {
    console.error(err);

    container.innerHTML = `
      <div class="alert alert-danger">
        Failed to load users
      </div>
    `;
  }

  window.addEventListener(
    "app:filter",
    (e) => {
      const {
        query,
        type,
      } = e.detail;

      if (type !== "users")
        return;

      applyFilter(query);
    }
  );

  return container;
}