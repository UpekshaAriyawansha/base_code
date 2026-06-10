import { isLoggedIn, getUser } from "../../services/session.js";

export default function Configuration() {

  const container = document.createElement("div");
  container.classList.add("page");

  // =========================
  // LOGIN DEBUG
  // =========================
  const loggedIn = isLoggedIn();
  const currentUser = getUser();

  console.log("🔐 Login Status:", loggedIn);
  console.log("👤 Login User:", currentUser);

  // =========================
  // SAFE PERMISSIONS
  // =========================
  const permissions = Array.isArray(currentUser?.permissions)
    ? currentUser.permissions
    : [];

  console.log("🔑 Permissions:", permissions);

  // =========================
  // CHECK PERMISSION (ROBUST)
  // =========================
  const hasPermission = (key) => {
    return permissions.some(p =>
      typeof p === "string"
        ? p === key
        : p?.name === key || p?.permission === key
    );
  };

  const canViewSettings = hasPermission("setting.view");

  container.innerHTML = `
    <div class="container-fluid">

      <div class="card vh-100">

        <div class="card-header">
          <strong>Configuration</strong>
        </div>

        <div class="card-body">

          <div class="border rounded p-3 bg-light mb-4 text-dark">
            Choose a section below to manage system behavior,
            security insights, and communication preferences.
          </div>

          <div class="row g-3">

            <!-- INSIGHTS -->
            <div class="col-md-6">
              <div class="config-card">

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0 text-dark">Insights</h6>
                  <span class="badge bg-light text-primary">Analytics</span>
                </div>

                <p>Review role and user activity insights and audit trends.</p>

                <button
                  class="btn btn-primary"
                  data-link="#/insights"
                  ${!canViewSettings ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
                >
                  Open Insights
                </button>

              </div>
            </div>

            <!-- SETTINGS -->
            <div class="col-md-6">
              <div class="config-card">

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0 text-dark">Settings</h6>
                  <span class="badge bg-light text-primary">Platform</span>
                </div>

                <p>Manage system-level settings and branding preferences.</p>

                <button
                  class="btn btn-primary"
                  data-link="#/settings"
                  ${!canViewSettings ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
                >
                  Open Settings
                </button>

              </div>
            </div>

            <!-- ROLES -->
            <div class="col-md-6">
              <div class="config-card">

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0 text-dark">Roles</h6>
                  <span class="badge bg-light text-primary">Access Control</span>
                </div>

                <p>Create roles and manage role permissions across the system.</p>

                <button class="btn btn-primary" data-link="#/roles">
                  Open Roles
                </button>

              </div>
            </div>

            <!-- EMAIL -->
            <div class="col-md-6">
              <div class="config-card">

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0 text-dark">Email Setup</h6>
                  <span class="badge bg-light text-primary">Communications</span>
                </div>

                <p>Configure SMTP transport and test outbound emails.</p>

                <button
                  class="btn btn-primary"
                  data-link="#/email"
                  ${!canViewSettings ? "disabled style='opacity:0.5;cursor:not-allowed;'" : ""}
                >
                  Open Email Setup
                </button>

              </div>
            </div>

          </div>

        </div>
      </div>
    </div>
  `;

  // =========================
  // NAVIGATION SAFETY
  // =========================
  container.querySelectorAll("button[data-link]").forEach(btn => {
    btn.addEventListener("click", () => {
      if (btn.disabled) return;
      location.hash = btn.dataset.link;
    });
  });

  return container;
}