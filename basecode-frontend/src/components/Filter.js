export default function Filter() {

  const container = document.createElement("div");

  container.innerHTML = `
    <div class="container mt-4 page-content">

      <div class="row card">

        <div class="card shadow-sm mb-2">

          <!-- HEADER -->
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Filters</strong>

            <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
              Hide Filters
            </button>
          </div>

          <!-- BODY -->
          <div class="card-body pb-3" id="filterBody">

            <div class="row g-1 align-items-center">

              <!-- SEARCH -->
              <div class="col-md-8">
                <label class="form-label">Filter</label>

                <input
                  type="text"
                  class="form-control"
                  id="globalSearch"
                  placeholder="Search name or slug..."
                />
              </div>

              <!-- TYPE -->
              <div class="col-md-2">
                <label class="form-label">Type</label>

                <select class="form-select" id="filterType">
                  <option value="users">Users</option>
                  <option value="roles">Roles</option>
                </select>
              </div>

              <!-- ACTIONS -->
              <div class="col-md-2 d-flex gap-2 mt-4">

                <button class="btn btn-outline-secondary w-100" id="clearBtn">
                  Clear
                </button>

                <button class="btn btn-primary btn-filter text-white w-100" id="searchBtn">
                  Search
                </button>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>
  `;

  // ==============================
  // ELEMENTS
  // ==============================
  const toggleBtn = container.querySelector("#toggleFilters");
  const filterBody = container.querySelector("#filterBody");
  const searchBtn = container.querySelector("#searchBtn");
  const clearBtn = container.querySelector("#clearBtn");
  const searchInput = container.querySelector("#globalSearch");
  const typeSelect = container.querySelector("#filterType");

  // ==============================
  // PAGE TYPE (IMPORTANT FIX)
  // ==============================
  const pageType = window.currentPageType || "users";
  typeSelect.value = pageType;

  // ==============================
  // EMIT FILTER EVENT
  // ==============================
  function emitFilter() {

    window.dispatchEvent(new CustomEvent("app:filter", {
      detail: {
        query: searchInput.value.trim(),
        type: typeSelect.value
      }
    }));
  }

  // ==============================
  // TOGGLE FILTER VISIBILITY
  // ==============================
  toggleBtn.addEventListener("click", (e) => {

    const isHidden =
      window.getComputedStyle(filterBody).display === "none";

    filterBody.style.display = isHidden ? "block" : "none";

    e.target.textContent = isHidden ? "Hide Filters" : "Show Filters";
  });

  // ==============================
  // SEARCH
  // ==============================
  searchBtn.addEventListener("click", emitFilter);

  searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") emitFilter();
  });

  // ==============================
  // CLEAR (FIXED)
  // ==============================
  clearBtn.addEventListener("click", () => {

    searchInput.value = "";

    // IMPORTANT FIX:
    // keep page type instead of forcing "users"
    typeSelect.value = pageType;

    emitFilter();
  });

  return container;
}