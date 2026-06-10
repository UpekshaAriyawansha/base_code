const API_URL = "http://localhost:8000/api";

export async function apiRequest(endpoint, options = {}) {
  const token = localStorage.getItem("token");

  const headers = {
    ...(options.body instanceof FormData
      ? {}
      : { "Content-Type": "application/json" }),

    ...(options.headers || {})
  };

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  const response = await fetch(`${API_URL}${endpoint}`, {
    ...options,
    headers
  });

  // =========================
  // HANDLE NON-OK RESPONSES
  // =========================
  if (!response.ok) {
    const errorText = await response.text();

    try {
      const errorJson = JSON.parse(errorText);
      throw new Error(errorJson.message || `HTTP ${response.status}`);
    } catch {
      throw new Error(errorText || `HTTP ${response.status}`);
    }
  }

  // =========================
  // HANDLE BLOB (CSV EXPORT FIX)
  // =========================
  const contentType = response.headers.get("content-type");

  if (
    contentType?.includes("text/csv") ||
    options.responseType === "blob"
  ) {
    return await response.blob();
  }

  // =========================
  // DEFAULT JSON RESPONSE
  // =========================
  const text = await response.text();

  return text ? JSON.parse(text) : {};
}




// const API_URL =
//   "http://localhost:8000/api";

// export async function apiRequest(
//   endpoint,
//   options = {}
// ) {

//   const token =
//     localStorage.getItem("token");

//   const headers = {
//     ...(options.body instanceof FormData
//       ? {}
//       : {
//           "Content-Type":
//             "application/json"
//         }),
//     ...(options.headers || {})
//   };

//   if (token) {
//     headers.Authorization =
//       `Bearer ${token}`;
//   }

//   const response =
//     await fetch(
//       `${API_URL}${endpoint}`,
//       {
//         ...options,
//         headers
//       }
//     );

//   const text =
//     await response.text();

//   let data = {};

//   try {
//     data = text
//       ? JSON.parse(text)
//       : {};
//   } catch {

//     throw new Error(
//       `Invalid JSON response: ${text}`
//     );
//   }

//   if (!response.ok) {

//     throw new Error(
//       data.message ||
//       `HTTP ${response.status}`
//     );
//   }

//   return data;
// }

