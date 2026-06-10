import DashboardLayout from './layouts/dashboardLayout.js';
import { router } from './router/router.js';

document.getElementById('app').appendChild(DashboardLayout());

// handle route changes
window.addEventListener('hashchange', router);
window.addEventListener('load', router);