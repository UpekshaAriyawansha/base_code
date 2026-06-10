import Sidebar from '../components/sidebar.js';
import Navbar from '../components/navbar.js';
import Breadcrumb from '../components/breadcrumb.js';
import Filter from '../components/Filter.js';

export default function DashboardLayout(content, pageTitle = 'Dashboard') {

  const layout = document.createElement('div');
  layout.className = 'layout';

  // SIDEBAR
  const sidebar = Sidebar(pageTitle);

  // MAIN AREA
  const main = document.createElement('main');
  main.className = 'main-content';

  const navbar = Navbar();
  const breadcrumb = Breadcrumb(pageTitle);

  const pageContent = document.createElement('div');
  pageContent.className = 'page-content';

  // CONTENT HANDLING (safe)
  if (content instanceof HTMLElement) {
    pageContent.appendChild(content);
  } else {
    pageContent.innerHTML = content || '';
  }

  main.appendChild(navbar);
  main.appendChild(breadcrumb);

  // 🔥 FILTER BAR (GLOBAL)
// const filterBar = Filter();
// main.appendChild(filterBar);

  main.appendChild(pageContent);

  layout.appendChild(sidebar);
  layout.appendChild(main);

  // EVENTS (safe binding)
  requestAnimationFrame(() => {

setTimeout(() => {

  const sidebarEl = layout.querySelector('#sidebar');
  const toggleBtn = layout.querySelector('[data-toggle="sidebar"]');

  if (toggleBtn && sidebarEl) {
    toggleBtn.addEventListener('click', () => {
      sidebarEl.classList.toggle('collapsed');
    });
  }

}, 0);

    // Theme persistence
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-mode');
    }

  });

  return layout;
}