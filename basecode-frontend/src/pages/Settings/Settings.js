import {
  getSettings,
  saveSettings,
  uploadFile
} from "../../services/settings";

export default function Settings() {

  const container = document.createElement("div");

  container.classList.add("page");

  container.innerHTML = `
    <div class="container-fluid bg-white">

      <div class="card">

        <div class="card-header bg-white">
          <strong>Settings</strong>
        </div>

        <div class="card-body">

          <div id="messageBox"></div>

          <form id="settingsForm">

            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">
                  Application Name
                </label>

                <input
                  type="text"
                  id="appName"
                  class="form-control"
                />
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  Timezone
                </label>

                <select
                  id="timezone"
                  class="form-select"
                >
                  <option value="UTC">
                    UTC
                  </option>

                  <option value="Asia/Colombo">
                    Asia/Colombo
                  </option>

                  <option value="Europe/London">
                    Europe/London
                  </option>

                  <option value="America/New_York">
                    America/New_York
                  </option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  Branding Mode
                </label>

                <select
                  id="brandingMode"
                  class="form-select"
                >
                  <option value="text">
                    Text
                  </option>

                  <option value="logo">
                    Logo
                  </option>
                </select>
              </div>

              <div class="col-md-6">

                <label class="form-label">
                  Upload Logo
                </label>

                <input
                  type="file"
                  id="logo"
                  class="form-control"
                  accept="image/*"
                />

                <div class="mt-2">

                  <img
                    id="logoPreview"
                    alt="Logo Preview"
                    style="
                      display:none;
                      max-height:100px;
                      border:1px solid #ddd;
                      padding:5px;
                      border-radius:4px;
                    "
                  />

                </div>

              </div>

            </div>

            <div class="mt-4 text-end">

              <button
                type="submit"
                class="btn btn-success"
              >
                Save Settings
              </button>

            </div>

          </form>

          <hr />

          <h5>
            Saved Settings
          </h5>

          <table
            class="table table-bordered"
          >

            <thead>

              <tr>
                <th width="35%">
                  Key
                </th>

                <th>
                  Value
                </th>
              </tr>

            </thead>

            <tbody id="settingsTableBody"></tbody>

          </table>

        </div>

      </div>

    </div>
  `;

  const form =
    container.querySelector(
      "#settingsForm"
    );

  const appName =
    container.querySelector(
      "#appName"
    );

  const timezone =
    container.querySelector(
      "#timezone"
    );

  const brandingMode =
    container.querySelector(
      "#brandingMode"
    );

  const logoInput =
    container.querySelector(
      "#logo"
    );

  const logoPreview =
    container.querySelector(
      "#logoPreview"
    );

  const tableBody =
    container.querySelector(
      "#settingsTableBody"
    );

  const messageBox =
    container.querySelector(
      "#messageBox"
    );

  let currentLogo = "";

  function showMessage(
    type,
    message
  ) {

    messageBox.innerHTML = `
      <div class="alert alert-${type}">
        ${message}
      </div>
    `;

    setTimeout(() => {
      messageBox.innerHTML = "";
    }, 3000);
  }

  function renderTable(data) {

    tableBody.innerHTML = "";

    Object.entries(data)
      .forEach(([key, value]) => {

        const row =
          document.createElement("tr");

        let displayValue =
          value || "";

        if (
          key === "branding.logo" &&
          value
        ) {

          displayValue = `
            <img
              src="http://localhost:8000${value}"
              style="
                max-height:250px;
              "
            />
          `;
        }

        row.innerHTML = `
          <td>${key}</td>
          <td>${displayValue}</td>
        `;

        tableBody.appendChild(row);
      });
  }

  logoInput.addEventListener(
    "change",
    () => {

      const file =
        logoInput.files?.[0];

      if (!file) {
        return;
      }

      const reader =
        new FileReader();

      reader.onload = e => {

        logoPreview.src =
          e.target.result;

        logoPreview.style.display =
          "block";
      };

      reader.readAsDataURL(file);
    }
  );

  async function loadSettings() {

  try {

    const response =
      await getSettings();

    console.log(
      "GET Settings Response:",
      response
    );

    const settings =
      response?.data || {};

    console.log(
      "Database Settings:",
      settings
    );

    appName.value =
      settings["branding.app_name"] || "";

    timezone.value =
      settings["app.timezone"] || "UTC";

    brandingMode.value =
      settings["branding.mode"] || "text";

    currentLogo =
      settings["branding.logo"] || "";

    if (currentLogo) {

logoPreview.src =
  "http://localhost:8000"
  + currentLogo;

      logoPreview.style.display =
        "block";
    }

    renderTable(settings);

  } catch (error) {

    console.error(
      "Load Settings Error:",
      error
    );

    showMessage(
      "danger",
      "Failed to load settings."
    );
  }
}

form.addEventListener(
  "submit",
  async (e) => {

    e.preventDefault();

    try {

      // Default existing logo
      let logoPath =
        currentLogo;

      const file =
        logoInput.files?.[0];

      // Upload new logo if selected
      if (file) {

        const uploadResponse =
          await uploadFile(file);

        console.log(
          "Upload Response:",
          uploadResponse
        );

        logoPath =
          uploadResponse?.data?.path || "";
      }

      const payload = {

        "branding.app_name":
          appName.value.trim(),

        "app.timezone":
          timezone.value,

        "branding.mode":
          brandingMode.value,

        "branding.logo":
          logoPath
      };

      console.log(
        "Submitting Payload:",
        payload
      );

      const response =
        await saveSettings(
          payload
        );

      console.log(
        "Save Response:",
        response
      );

      currentLogo =
        logoPath;

      showMessage(
        "success",
        "Settings saved successfully."
      );

      await loadSettings();

    } catch (error) {

      console.error(
        "Save Error:",
        error
      );

      showMessage(
        "danger",
        error?.message ||
        "Failed to save settings."
      );
    }
  }
);

  loadSettings();

  return container;
}