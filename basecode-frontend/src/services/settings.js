import { apiRequest } from "./api.js";

export function getSettings() {
  return apiRequest("/settings/general");
}

export function saveSettings(data) {
  return apiRequest("/settings/general", {
    method: "POST",
    body: JSON.stringify(data)
  });
}


export async function uploadFile(file) {

  const formData =
    new FormData();

  formData.append(
    "file",
    file
  );

  const token =
    localStorage.getItem("token");

  const response =
    await fetch(
      "http://localhost:8000/api/upload",
      {
        method: "POST",
        headers: {
          Authorization:
            `Bearer ${token}`
        },
        body: formData
      }
    );

  return response.json();
}



// import { apiRequest } from "./api.js";

// export function getSettings() {
//   return apiRequest("/settings");
// }

// export function saveSettings(data) {
//   return apiRequest("/settings", {
//     method: "POST",
//     body: JSON.stringify(data)
//   });
// }

// export async function uploadFile(file) {
//   const formData = new FormData();
//   formData.append("file", file);

//   return apiRequest("/upload", {
//     method: "POST",
//     body: formData,
//     headers: {} // important: don't set Content-Type
//   });
// } 