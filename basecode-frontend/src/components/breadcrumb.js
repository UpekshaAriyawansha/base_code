export default function Breadcrumb(pageName = 'Dashboard') {

  const nav = document.createElement('nav');
  nav.className = 'breadcrumb-wrapper';

  nav.innerHTML = `

  <div class="card shadow-sm mb-1 pt-2 pb-2">
        <div class="card-body py-2">
                <ol class="breadcrumb mb-0">

      <li class="breadcrumb-item">
        <a href="#/dashboard">Dashboard</a>
      </li>

      <li class="breadcrumb-item active text-primary">
        ${pageName}
      </li>

    </ol>
        </div>
  </div>

  `;

  return nav;
}