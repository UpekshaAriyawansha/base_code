import { apiRequest } from "./api";

export async function login(email, password) {

  const response = await apiRequest("/auth/login", {
    method: "POST",
    body: JSON.stringify({
      email,
      password
    })
  });

  localStorage.setItem(
    "token",
    response.token || ""
  );

  localStorage.setItem(
    "user",
    JSON.stringify(response.user || {})
  );

  localStorage.setItem(
    "permissions",
    JSON.stringify(response.permissions || [])
  );

  return response;
}

// export function logout() {

//   localStorage.removeItem("token");
//   localStorage.removeItem("user");
//   localStorage.removeItem("permissions");

//   location.hash = "#/login";
// }

export async function logout() {

  try {

    // call backend logout (for audit logging)
apiRequest("/api/auth/logout", {
  method: "POST"
});

  } catch (e) {
    console.warn("Logout API failed (ignored)", e);
  }

  // clear local session
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  localStorage.removeItem("permissions");

  location.hash = "#/login";
}

export function getCurrentUser() {

  return JSON.parse(
    localStorage.getItem("user") || "{}"
  );
}


// export async function backendLogout() {
//   return fetch("/api/auth/logout", {
//     method: "POST",
//     headers: {
//       "Authorization": `Bearer ${getToken()}`
//     }
//   });
// }

