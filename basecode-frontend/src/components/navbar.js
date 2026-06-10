import {
  isLoggedIn,
  logout,
  getUser,
  getExpiry
} from "../services/session.js";

import { navigate } from "../services/navigate.js";

export default function Navbar() {

  const nav = document.createElement("nav");
  nav.className =
    "top-navbar d-flex justify-content-between align-items-center p-2";

  let timerInterval = null;

  function getRemainingTime() {

    const expiry = getExpiry();

    if (!expiry) return "Expired";

    const diff = expiry - Date.now();

    if (diff <= 0) {
      return "Expired";
    }

    const minutes = Math.floor(diff / 1000 / 60);
    const seconds = Math.floor((diff / 1000) % 60);

    return `${minutes}m ${seconds}s`;
  }

  function updateTimer() {

    const timerEl = nav.querySelector("#expTime");

    if (!timerEl) return;

    timerEl.textContent = getRemainingTime();

    if (getRemainingTime() === "Expired") {

      logout();
      navigate("#/login");

      clearInterval(timerInterval);
    }
  }

  function render() {

    const loggedIn = isLoggedIn();
    const user = getUser();

    nav.innerHTML = `
      <div class="pt-3 pb-3">

        <h6 class="mb-0 text-white">
          ${
            loggedIn && user?.email
              ? `Welcome, ${user.email}`
              : "Dashboard"
          }
        </h6>

      </div>

      <div class="d-flex align-items-center gap-3">

        ${
          loggedIn
            ? `
              <div class="text-white small">
                Exp:
                <span id="expTime">${getRemainingTime()}</span>
              </div>
            `
            : ""
        }

        <button
          id="darkModeBtn"
          class="btn btn-secondary"
        >
          🌙 Theme
        </button>

        <button
          id="authBtn"
          class="btn ${
            loggedIn
              ? "btn-danger"
              : "btn-primary"
          }"
        >
          ${
            loggedIn
              ? "Logout"
              : "Login"
          }
        </button>

      </div>
    `;

    const darkBtn = nav.querySelector("#darkModeBtn");
    const authBtn = nav.querySelector("#authBtn");

    darkBtn?.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
    });

    authBtn?.addEventListener("click", async () => {

  if (isLoggedIn()) {

    await logout();   // ✅ IMPORTANT

    navigate("#/login");

  } else {

    navigate("#/login");
  }

  render();
});

    clearInterval(timerInterval);

    if (loggedIn) {
      timerInterval = setInterval(updateTimer, 1000);
    }
  }

  render();

  return nav;
}
