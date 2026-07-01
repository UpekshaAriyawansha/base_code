import DashboardLayout from "../layouts/dashboardLayout.js";

import Users from "../pages/users/users.js";
import AddUser from "../pages/users/AddUser.js";
import EditUser from "../pages/users/EditUser.js";
import ViewUser from "../pages/users/ViewUser.js";

import { hasPermission }
from "../services/currentUser_permission.js";


import Dashboard from "../pages/dashboard/dashboard.js";

import Configuration from "../pages/configuration/configuration.js";

import Login from "../pages/login/login.js";

import Roles from "../pages/roles/roles.js";   // 🔥 ADD THIS
import AddRole from "../pages/roles/AddRole.js";   // 🔥 ADD THIS
import EditRole from "../pages/roles/EditRole.js";   // 🔥 ADD THIS
import ViewRole from "../pages/roles/ViewRole.js";

import Insights from "../pages/Insights/Insights.js";   
import Settings from "../pages/Settings/Settings.js";   
import EmailSetup from "../pages/EmailSetup/EmailSetup.js";   

import Profile from "../pages/Profile/Profile.js";


const routes = {


  "/dashboard": { component: Dashboard, title: "Dashboard", layout: true },

  "/users": { component: Users, title: "Users", layout: true },
  "/users/add": { component: AddUser, title: "Add User", layout: true },
  "/users/edit": { component: EditUser, title: "Edit User", layout: true },
    "/users/view": { component: ViewUser, title: "ViewUser", layout: true },


  "/configuration": { component: Configuration, title: "Configuration", layout: true },

  "/login": { component: Login, title: "Login", layout: false },

   // 🔥 ROLES ROUTE
  "/roles": { component: Roles, title: "Roles", layout: true },
  "/roles/add": { component: AddRole, title: "Add Role", layout: true },
  "/roles/edit": { component: EditRole, title: "Edit Role", layout: true },
  "/roles/view": { component: ViewRole,  title: "Role Details",  layout: true
},

  "/insights": {component: Insights,title: "Insights",layout: true },

  "/settings": {component: Settings,title: "Settings", layout: true },

  "/email": {component: EmailSetup,title: "Email Setup", layout: true },

    "/profile": {component: Profile,title: "Profile",layout: true },

  
};

function getPath() {

  const hash = location.hash || "#/login";
  const path = hash.replace("#", "");

  const parts = path.split("/");

  if (parts.length > 3) {
    return `/${parts[1]}/${parts[2]}`;
  }

  if (parts.length === 3) {
    return `/${parts[1]}/${parts[2]}`;
  }

  return path;
}

export async function router() {
  const path = getPath();

  console.log("ROUTER:", path);

  const route = routes[path];

  const app = document.getElementById("app");
  app.innerHTML = "";

  if (!route) {
    app.innerHTML = `<h1 class="p-4">404 - Page Not Found</h1>`;
    return;
  }

  const page = await route.component();

  if (!route.layout) {
    app.appendChild(page);
    return;
  }

  app.appendChild(DashboardLayout(page, route.title));
}

