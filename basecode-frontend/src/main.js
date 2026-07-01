// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap-icons/font/bootstrap-icons.css';
// import './assets/css/main.css';

// import { router } from './router/router.js';

// window.addEventListener('hashchange', router);
// window.addEventListener('load', router);

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './assets/css/main.css';

import { router } from './router/router.js';
import { applyTheme } from './services/theme.js';

window.addEventListener('load', async () => {
  await applyTheme();
  router();
});

window.addEventListener('hashchange', router);