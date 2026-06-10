import { getInsights } from "../../services/insights";

export default function DashboardPage() {
  const container = document.createElement("div");
  container.classList.add("page");

  container.innerHTML = `
    <div class="container-fluid card vh-100">

      <!-- HEADER -->
      <div class="card-header">
        <strong>Dashboard</strong>
      </div>

      <!-- STATS -->
      <div class="row g-4 mt-3">

        <div class="col-md-3">
          <div class="card shadow-sm p-3">
            <h6>Total Users</h6>
            <h2 id="usersCount">0</h2>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm p-3">
            <h6>Total Roles</h6>
            <h2 id="rolesCount">0</h2>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm p-3">
            <h6>Total Permissions</h6>
            <h2 id="permissionsCount">0</h2>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm p-3">
            <h6>Total Settings</h6>
            <h2 id="settingsCount">0</h2>
          </div>
        </div>

      </div>

      <!-- QUICK ACTIONS -->
      <div class="card mt-4">
        <div class="card-header">
          Quick Actions
        </div>

        <div class="card-body">
          <button
            class="btn btn-primary me-2"
            onclick="location.hash='#/users'"
          >
            View Users
          </button>

          <button
            class="btn btn-success"
            onclick="location.hash='#/roles'"
          >
            View Roles
          </button>
        </div>
      </div>

    </div>
  `;

  const usersCount = container.querySelector("#usersCount");
  const rolesCount = container.querySelector("#rolesCount");
  const permissionsCount = container.querySelector("#permissionsCount");
  const settingsCount = container.querySelector("#settingsCount");

  async function loadInsights() {
    try {
      const res = await getInsights();
      const data = res.data || {};

      usersCount.textContent = data.users_count || 0;
      rolesCount.textContent = data.roles_count || 0;
      permissionsCount.textContent = data.permissions_count || 0;
      settingsCount.textContent = data.settings_count || 0;
    } catch (err) {
      console.error("Failed to load insights", err);
    }
  }

  loadInsights();

  return container;
}