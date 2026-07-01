import Sidebar from "../components/sidebar.js";
import Navbar from "../components/navbar.js";
import Breadcrumb from "../components/breadcrumb.js";

export default function DashboardLayout(content, pageTitle = "Dashboard") {
  const layout = document.createElement("div");
  layout.className = "layout";

  const sidebar = Sidebar(pageTitle);

  const main = document.createElement("main");
  main.className = "main-content";

  const navbar = Navbar();
  const breadcrumb = Breadcrumb(pageTitle);

  const pageContent = document.createElement("div");
  pageContent.className = "page-content";

  if (content instanceof HTMLElement) {
    pageContent.appendChild(content);
  } else {
    pageContent.innerHTML = content || "";
  }

  main.appendChild(navbar);
  main.appendChild(breadcrumb);
  main.appendChild(pageContent);

  layout.appendChild(sidebar);
  layout.appendChild(main);

  requestAnimationFrame(() => {
    const sidebarEl = layout.querySelector("#sidebar");
    const toggleBtn = layout.querySelector("[data-toggle='sidebar']");

    if (toggleBtn && sidebarEl) {
      toggleBtn.addEventListener("click", () => {
        sidebarEl.classList.toggle("collapsed");
      });
    }
  });

  return layout;
}