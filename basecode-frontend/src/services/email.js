import { apiRequest } from "./api";

export function getEmailSettings() {
  return apiRequest(
    "/email-setup"
  );
}

export function saveEmailSettings(
  data
) {
  return apiRequest(
    "/email-setup",
    {
      method: "POST",
      body: JSON.stringify(data)
    }
  );
}

export function sendTestEmail(data) {
  return apiRequest("/email-setup/test", {
    method: "POST",
    body: JSON.stringify(data)
  });
}

export function getEmailLogs() {
  return apiRequest("/email-logs");
}