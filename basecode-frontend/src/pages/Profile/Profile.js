import { isLoggedIn, getUser } from "../../services/session.js";
import { navigate } from "../../services/navigate.js";

export default function Profile() {
const container = document.createElement("div");
container.className = "container-fluid py-4";

if (!isLoggedIn()) {
navigate("#/login");
return container;
}

const user = getUser();

const initials = `${user.first_name?.[0] || ""}${
    user.last_name?.[0] || ""
  }`.toUpperCase();

container.innerHTML = ` <div class="row justify-content-center ">

<div class="card profile-card ">
    <div class="card-header profile-card">
      <strong>Profile</strong>
    </div>

  <div class="col-lg-12 pt-5 pb-5 px-5">

    <!-- Profile Header -->
    <div class="card border-0 shadow-lg overflow-hidden mb-4">

      <div
        class="p-5"

      >
        <div class="d-flex flex-column flex-md-row align-items-center">

          <div
            class=" 
              rounded-circle
              bg-white
              text-primary
              d-flex
              justify-content-center
              align-items-center
              fw-bold
              shadow
            "
            style="
              width:90px;
              height:90px;
              font-size:2rem;
            "
          >
            ${initials}
          </div>

          <div class="ms-md-4 mt-3 mt-md-0">

            <h2 class="mb-1">
              ${user.first_name} ${user.last_name}
            </h2>

            <p class="mb-2 opacity-75">
              <i class="bi bi-envelope"></i>
              ${user.email}
            </p>

            <span class="badge bg-light text-dark px-3 py-2">
              <i class="bi bi-shield-check"></i>
              ${user.role.toUpperCase()}
            </span>

          </div>

        </div>
      </div>

    </div>

    <!-- Information Cards -->
    <div class="row g-4 ">

      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center">
            <i
              class="bi bi-person-badge text-primary fs-1"
            ></i>

            <h6 class="mt-3 text-muted">
              User ID
            </h6>

            <h4>
              #${user.id}
            </h4>
          </div>
        </div>
      </div>

      <div class="col-md-4 ">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center">
            <i
              class="bi bi-person text-success fs-1"
            ></i>

            <h6 class="mt-3 text-muted">
              Full Name
            </h6>

            <h5>
              ${user.first_name}
              ${user.last_name}
            </h5>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center">
            <i
              class="bi bi-shield-lock text-warning fs-1"
            ></i>

            <h6 class="mt-3 text-muted">
              Permissions
            </h6>

            <h4>
              ${user.permissions?.length || 0}
            </h4>
          </div>
        </div>
      </div>

    </div>

    <!-- Account Information -->
    <div class="card border-0 shadow-sm mt-4">
      <div class="card-header py-3">
        <h5 class="mb-0">
          <i class="bi bi-person-vcard me-2"></i>
          Account Information
        </h5>
      </div>

      <div class="card-body">

        <div class="row mb-3">
          <div class="col-md-3 fw-semibold">
            Email
          </div>

          <div class="col-md-9">
            ${user.email}
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3 fw-semibold">
            Role
          </div>

          <div class="col-md-9">
            <span class="badge bg-success">
              ${user.role}
            </span>
          </div>
        </div>

      </div>
    </div>

    <!-- Permissions -->
    <div class="card border-0 shadow-sm mt-4">
      <div class="card-header py-3">
        <h5 class="mb-0">
          <i class="bi bi-key-fill me-2"></i>
          Assigned Permissions
        </h5>
      </div>

      <div class="card-body">

        ${
          user.permissions?.length
            ? `
              <div
                class="
                  d-flex
                  flex-wrap
                  gap-2
                "
              >
                ${user.permissions
                  .map(
                    permission => `
                      <span
                        class="
                          badge
                          rounded-pill
                          bg-primary
                          px-3
                          py-2
                        "
                      >
                        <i class="bi bi-check2-circle me-1"></i>
                        ${permission}
                      </span>
                    `
                  )
                  .join("")}
              </div>
            `
            : `
              <div class="text-muted">
                No permissions assigned.
              </div>
            `
        }

      </div>
    </div>

  </div>

</div>
</div>

`;

return container;
}
