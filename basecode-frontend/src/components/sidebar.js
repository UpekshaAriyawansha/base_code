import { getSettings } from "../services/settings.js";
import { menuItems } from "../config/menu.js";
import { hasPermission } from "../services/currentUser_permission.js";

export default function Sidebar(activePage = "dashboard") {
  const sidebar = document.createElement("aside");
  sidebar.className = "sidebar";
  sidebar.id = "sidebar";

  // Build menu FIRST (fixes undefined issues)
  const menuHtml = menuItems
    .map((group) => {
      return `
        <div class="menu-group">

          <div class="menu-title">
            ${group.section}
          </div>

          <ul class="sidebar-menu">

            ${group.items
              .filter((item) => {
                if (!item.permission) return true;
                return hasPermission(item.permission);
              })
              .map(
                (item) => `
                  <li>
                    <a
                      href="${item.href}"
                      data-page="${item.page}"
                      class="sidebar-link ${
                        activePage === item.page ? "active" : ""
                      }"
                    >
                      <i class="bi ${item.icon}"></i>
                      <span>${item.title}</span>
                    </a>
                  </li>
                `
              )
              .join("")}

          </ul>

        </div>
      `;
    })
    .join("");

  sidebar.innerHTML = `
    <div class="slider-shadow sidebar-header d-flex justify-content-between align-items-center">

      <div class="logo" id="sidebarLogo">
        <h5 class="mb-0" id="sidebarBranding">Base Code</h5>
      </div>

      <button class="sidebar-toggle btn btn-sm btn-light" data-toggle="sidebar">
        <i class="bi bi-chevron-left"></i>
      </button>

    </div>

    <div class="navigation-label mt-2">
      Navigation
    </div>

    ${menuHtml}
  `;

  loadBranding();

  async function loadBranding() {
    try {
      const response = await getSettings();
      const settings = response?.data || {};

      const brandingMode = settings["branding.mode"] || "text";
      const appName = settings["branding.app_name"] || "Base Code";
      const logo = settings["branding.logo"] || "";

      const logoContainer = sidebar.querySelector("#sidebarLogo");

      if (!logoContainer) return;

      if (brandingMode === "logo" && logo) {
        logoContainer.innerHTML = `
          <img
            src="http://localhost:8000${logo}"
            alt="logo"
            style="max-height:50px; max-width:180px;"
          />
        `;
      } else {
        logoContainer.innerHTML = `
          <h5 class="mb-0">${appName}</h5>
        `;
      }
    } catch (error) {
      console.error("Sidebar branding error:", error);
    }
  }

  return sidebar;
}