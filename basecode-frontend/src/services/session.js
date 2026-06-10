export function setSession(token, user) {

  // 1 hour expiry
  const expiresAt = Date.now() + (60 * 60 * 1000);

  localStorage.setItem("token", token);
  localStorage.setItem("user", JSON.stringify(user));
  localStorage.setItem("expires_at", expiresAt);
}

export function getToken() {
  return localStorage.getItem("token");
}

export function getUser() {
  return JSON.parse(localStorage.getItem("user") || "null");
}

export function getExpiry() {
  return Number(localStorage.getItem("expires_at") || 0);
}

export function isLoggedIn() {

  const token = getToken();
  const expiry = getExpiry();

  if (!token || Date.now() > expiry) {
    logout();
    return false;
  }

  return true;
}

export function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  localStorage.removeItem("expires_at");
}






// const TOKEN_KEY = "token";
// const USER_KEY = "user";

// export function setSession(token, user) {
//   localStorage.setItem(TOKEN_KEY, token);

//   if (user) {
//     localStorage.setItem(USER_KEY, JSON.stringify(user));
//   } else {
//     localStorage.removeItem(USER_KEY);
//   }
// }

// export function getToken() {
//   const token = localStorage.getItem(TOKEN_KEY);
//   return token && token !== "undefined" ? token : null;
// }

// export function getUser() {
//   try {
//     const user = localStorage.getItem(USER_KEY);

//     if (!user || user === "undefined") {
//       return null;
//     }

//     return JSON.parse(user);
//   } catch (e) {
//     console.warn("Invalid user JSON in storage:", e);
//     localStorage.removeItem(USER_KEY);
//     return null;
//   }
// }

// export function isLoggedIn() {
//   return !!getToken();
// }

// export function logout() {
//   localStorage.removeItem(TOKEN_KEY);
//   localStorage.removeItem(USER_KEY);
// }