import { getAuditLogs } from "../../services/auditLogs.js";
import { apiRequest } from "../../services/api.js";

export default function AuditLogs() {
  const container = document.createElement("div");

  container.innerHTML = `
    <div class="container-fluid card vh-100">

      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Audit Logs</strong>

        <div class="d-flex gap-2">

          <select id="eventType" class="form-select form-select-sm">
            <option value="">All Events</option>
            <option value="LOGIN">LOGIN</option>
            <option value="USER_CREATED">USER CREATED</option>
            <option value="ROLE_ASSIGNED">ROLE ASSIGNED</option>
          </select>

          <input id="module" class="form-control form-control-sm" placeholder="Module">

          <button id="exportBtn" class="btn btn-sm btn-success">
            Export CSV
          </button>

        </div>
      </div>

      <div class="card-body">

        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Event</th>
              <th>Module</th>
              <th>User</th>
              <th>Description</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody id="rows">
            <tr>
              <td colspan="6" class="text-center">Loading...</td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  `;

  let page = 1;

  const rows = container.querySelector("#rows");
  const eventType = container.querySelector("#eventType");
  const module = container.querySelector("#module");

  async function load() {
    rows.innerHTML = `<tr><td colspan="6">Loading...</td></tr>`;

    const res = await getAuditLogs({
      page,
      event_type: eventType.value,
      module: module.value
    });

    const data = res?.data?.data || [];

    rows.innerHTML = data.length
      ? data.map(log => `
          <tr>
            <td>${log.id ?? ""}</td>
            <td>${log.event_type ?? ""}</td>
            <td>${log.module ?? ""}</td>
            <td>${log.user_id ?? ""}</td>
            <td>${log.description ?? ""}</td>
            <td>${log.created_at ?? ""}</td>
          </tr>
        `).join("")
      : `<tr><td colspan="6">No audit logs found</td></tr>`;
  }

  // =========================
  // FIXED EXPORT FUNCTION
  // =========================
  container.querySelector("#exportBtn").onclick = async () => {

    const query = new URLSearchParams({
      event_type: eventType.value,
      module: module.value
    }).toString();

    const blob = await apiRequest(
      `/audit-logs/export?${query}`,
      {
        method: "GET",
        responseType: "blob" // IMPORTANT
      }
    );

    const url = window.URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = "audit_logs.csv";
    document.body.appendChild(a);
    a.click();
    a.remove();

    window.URL.revokeObjectURL(url);
  };

  load();

  return container;
}