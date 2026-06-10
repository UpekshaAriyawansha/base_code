// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap-icons/font/bootstrap-icons.css';
// import './assets/css/main.css';

// import { router } from './router/router.js';

// import DashboardLayout from './layouts/dashboardLayout.js';
// import DashboardPage from './pages/dashboard/dashboard.js';

// const app = document.getElementById('app');

// app.appendChild(
//   DashboardLayout(DashboardPage(), 'Dashboard')
// );

// // listen to route changes
// window.addEventListener('hashchange', router);
// window.addEventListener('load', router);

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './assets/css/main.css';

import { router } from './router/router.js';

window.addEventListener('hashchange', router);
window.addEventListener('load', router);