import { getUsers } from "../../services/users.js";
import Filter from "../../components/Filter.js";
import { isLoggedIn, getUser } from "../../services/session.js";

export default async function Users() {

  const container = document.createElement("div");

  // ✅ LOGIN RESPONSE
  const loggedIn = isLoggedIn();
  const user = getUser();

  console.log("Login Response:", user);

  // ❌ NOT LOGGED IN
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

  function applyFilter(query) {

    if (!query) {
      filteredUsers = allUsers;
    } else {
      filteredUsers = allUsers.filter(user =>
        (user.first_name || "").toLowerCase().includes(query.toLowerCase()) ||
        (user.last_name || "").toLowerCase().includes(query.toLowerCase()) ||
        (user.email || "").toLowerCase().includes(query.toLowerCase())
      );
    }

    currentPage = 1;
    renderTable();
  }

  function renderTable() {

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;

    const users = filteredUsers.slice(start, end);
    const totalPages = Math.ceil(filteredUsers.length / perPage);

    // ✅ CHECK PERMISSION FROM LOGIN RESPONSE
    const hasCreatePermission =
      user?.permissions?.includes("users.create") ||
      user?.permissions?.some(p => p.name === "users.create");

    container.innerHTML = `
      <div id="filter-container" class="container-fluid card mb-4"></div>

      <div class="container-fluid card vh-100">

        <div class="card-header bg-white">
          <strong>Users</strong>
        </div>

        <div class="card-body">

          <div class="border rounded p-3 bg-light mb-3">
            Logged in as: ${user?.name || user?.email || "Unknown"}
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">

            <small class="text-muted">
              Showing ${users.length} of ${filteredUsers.length} users
            </small>

            <div class="d-flex gap-2">

              <button class="btn btn-outline-secondary" id="columnBtn">
                Columns
              </button>

              <!-- ✅ CREATE USER BUTTON (PERMISSION CONTROLLED) -->
              <button
                class="btn btn-success"
                id="createUserBtn"
                ${!hasCreatePermission ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
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
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                  </tr>
                </thead>

                <tbody>

                  ${users.map(user => `
                    <tr class="user-row" data-id="${user.id}" style="cursor:pointer;">
                      <td>${user.id}</td>
                      <td>${user.first_name || ""} ${user.last_name || ""}</td>
                      <td>${user.email || "-"}</td>
                      <td>${user.role?.name || "-"}</td>
                      <td>
                        <span class="badge ${
                          user.status === "active" ? "bg-success" : "bg-secondary"
                        }">
                          ${user.status || "inactive"}
                        </span>
                      </td>
                    </tr>
                  `).join("")}

                </tbody>

              </table>

            </div>

          </div>

          <div class="d-flex justify-content-between align-items-center mt-3">

            <button class="btn btn-outline-primary" id="prevBtn"
              ${currentPage === 1 ? "disabled" : ""}>
              Prev
            </button>

            <div>
              Page ${currentPage} of ${totalPages || 1}
            </div>

            <button class="btn btn-outline-primary" id="nextBtn"
              ${currentPage === totalPages || totalPages === 0 ? "disabled" : ""}>
              Next
            </button>

          </div>

        </div>
      </div>
    `;

    container.querySelector("#filter-container")
      .appendChild(Filter());

    // Row click
    container.querySelectorAll(".user-row").forEach(row => {
      row.addEventListener("click", () => {
        location.hash = `#/users/view/${row.dataset.id}`;
      });
    });

    // Prevent click if disabled
    container.querySelector("#createUserBtn")?.addEventListener("click", (e) => {
      if (!hasCreatePermission) {
        e.preventDefault();
        return;
      }
      location.hash = "#/users/add";
    });

    // Pagination
    container.querySelector("#prevBtn")?.addEventListener("click", () => {
      currentPage--;
      renderTable();
    });

    container.querySelector("#nextBtn")?.addEventListener("click", () => {
      currentPage++;
      renderTable();
    });
  }

  try {
    const res = await getUsers();

    allUsers = Array.isArray(res) ? res : (res.data || []);
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

  window.addEventListener("app:filter", (e) => {
    const { query, type } = e.detail;

    if (type !== "users") return;

    applyFilter(query);
  });

  return container;
}