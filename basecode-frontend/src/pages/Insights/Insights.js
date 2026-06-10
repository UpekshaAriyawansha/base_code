import { getAuditLogs, exportAuditLogs } from "../../services/auditLogs.js";

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
            <option value="USER_CREATED">USER_CREATED</option>
            <option value="ROLE_ASSIGNED">ROLE_ASSIGNED</option>
          </select>

          <input id="module" class="form-control form-control-sm" placeholder="Module">

          <button id="exportBtn" class="btn btn-sm btn-success col-3">
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

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <button id="prevBtn" class="btn btn-sm btn-secondary">
            Prev
          </button>

          <span id="pageInfo"></span>

          <button id="nextBtn" class="btn btn-sm btn-secondary">
            Next
          </button>
        </div>

      </div>
    </div>
  `;

  const rows = container.querySelector("#rows");
  const eventType = container.querySelector("#eventType");
  const module = container.querySelector("#module");

  const prevBtn = container.querySelector("#prevBtn");
  const nextBtn = container.querySelector("#nextBtn");
  const pageInfo = container.querySelector("#pageInfo");
  const exportBtn = container.querySelector("#exportBtn");

  let page = 1;
  let totalPages = 1;

  async function load() {
    rows.innerHTML = `<tr><td colspan="6">Loading...</td></tr>`;

    const res = await getAuditLogs({
      page,
      per_page: 10,
      event_type: eventType.value,
      module: module.value
    });

    const data = res?.data?.data || [];
    const pagination = res?.data?.pagination || {};

    totalPages = pagination.total_pages || 1;

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

    pageInfo.textContent = `Page ${page} of ${totalPages}`;

    prevBtn.disabled = page <= 1;
    nextBtn.disabled = page >= totalPages;
  }

  // -------------------------
  // Pagination actions
  // -------------------------

  prevBtn.onclick = () => {
    if (page > 1) {
      page--;
      load();
    }
  };

  nextBtn.onclick = () => {
    if (page < totalPages) {
      page++;
      load();
    }
  };

  // -------------------------
  // Filters reset pagination
  // -------------------------

  eventType.onchange = () => {
    page = 1;
    load();
  };

  module.oninput = () => {
    page = 1;
    load();
  };

  // -------------------------
  // Export CSV
  // -------------------------

  exportBtn.onclick = async () => {
    const url = exportAuditLogs({
      event_type: eventType.value,
      module: module.value
    });

    const response = await fetch(url);

    const blob = await response.blob();

    const downloadUrl = window.URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = downloadUrl;
    a.download = "audit_logs.csv";
    document.body.appendChild(a);
    a.click();
    a.remove();

    window.URL.revokeObjectURL(downloadUrl);
  };

  load();

  return container;
}