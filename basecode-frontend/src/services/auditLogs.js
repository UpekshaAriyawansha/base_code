import { apiRequest } from "./api.js";

export function getAuditLogs(params = {}) {
  const query = new URLSearchParams(params).toString();

  return apiRequest(
    `/audit-logs${query ? `?${query}` : ""}`
  );
}

export function exportAuditLogs(params = {}) {
  const query = new URLSearchParams(params).toString();

  return `${window.location.origin}/api/audit-logs/export${
    query ? `?${query}` : ""
  }`;
}




// import { apiRequest } from "./api.js";

// export function getAuditLogs(params = {}) {
//   const query = new URLSearchParams(params).toString();

//   return apiRequest(`/audit-logs${query ? `?${query}` : ""}`);
// }

// export function exportAuditLogs(params = {}) {
//   const query = new URLSearchParams(params).toString();

//   // IMPORTANT: export should NOT use apiRequest if it returns JSON wrapper
//   return `${window.location.origin}/api/audit-logs/export${query ? `?${query}` : ""}`;
// }

