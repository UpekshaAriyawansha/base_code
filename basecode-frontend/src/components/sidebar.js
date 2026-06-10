import { menuItems } from '../config/menu.js';

import {
  hasPermission
}
from "../services/currentUser_permission.js";

export default function Sidebar(activePage = 'dashboard') {

  const sidebar = document.createElement('aside');
  sidebar.className = 'sidebar';
  sidebar.id = 'sidebar';


  const menuHtml = menuItems.map(group => `
    <div class="menu-group">

      <div class="menu-title">
        ${group.section}
      </div>

      <ul class="sidebar-menu">

        ${group.items.map(item => `
          <li>
            <a
              href="${item.href}"
              data-page="${item.page}"
              class="sidebar-link ${activePage === item.page ? 'active' : ''}"
            >
              <i class="bi ${item.icon}"></i>
              <span>${item.title}</span>
            </a>
          </li>
        `).join('')}

      </ul>

    </div>
  `).join('');

  sidebar.innerHTML = `
    <div class="sidebar-header d-flex justify-content-between align-items-center">

      <div class="logo">
        <h5 class="mb-0">Base Code</h5>
      </div>

      <button
        class="sidebar-toggle btn btn-sm btn-light"
        data-toggle="sidebar"
      >
        <i class="bi bi-chevron-left"></i>
      </button>

    </div>

    <div class="navigation-label mt-2">
      Navigation
    </div>

    ${menuHtml}
  `;

  return sidebar;
}


