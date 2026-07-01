import {
  getSettings,
  saveSettings,
  uploadFile
} from "../../services/settings";

export default function Settings() {
  const container = document.createElement("div");
  container.classList.add("page");

  container.innerHTML = `
    <div class="container-fluid card profile-card">

      <div class="">

        <div class="card-header mt-2 profile-card">
          <strong>Settings</strong>
        </div>

        <div class="card-body">

          <div id="messageBox"></div>

          <form id="settingsForm" class="card bg-white pt-4 pb-4 px-5 mb-4">

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
                  <option value="UTC">UTC</option>
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
                  <option value="text">Text</option>
                  <option value="logo">Logo</option>
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

            <strong class="form-label">Theme Colors</strong>

            <div class="row g-3 mt-2 ">

              <div class="col-md-6">
                <label class="form-label">
                  Primary Color
                </label>

                <div class="d-flex gap-2">
                  <input
                    type="color"
                    id="primaryColorPicker"
                    class="form-control form-control-color"
                  />

                  <input
                    type="text"
                    id="primaryColor"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  Secondary Color
                </label>

                <div class="d-flex gap-2">
                  <input
                    type="color"
                    id="secondaryColorPicker"
                    class="form-control form-control-color"
                  />

                  <input
                    type="text"
                    id="secondaryColor"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  Accent Color
                </label>

                <div class="d-flex gap-2">
                  <input
                    type="color"
                    id="accentColorPicker"
                    class="form-control form-control-color"
                  />

                  <input
                    type="text"
                    id="accentColor"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">
                  Text Color
                </label>

                <div class="d-flex gap-2">
                  <input
                    type="color"
                    id="textColorPicker"
                    class="form-control form-control-color"
                  />

                  <input
                    type="text"
                    id="textColor"
                    class="form-control"
                  />
                </div>
              </div>

            </div>

            <div class="mt-4 text-end">
              <button
                type="submit"
                class="btn btn-success mb-3"
              >
                Save Settings
              </button>
            </div>

          </form>
          
          <div class="card bg-white pt-4 pb-4 px-5 mb-4">

          <strong class="mb-1">Current Settings</strong>

          <table class="table table-bordered mt-3">
            <thead>
              <tr>
                <th width="35%">Key</th>
                <th>Value</th>
              </tr>
            </thead>

            <tbody id="settingsTableBody"></tbody>
          </table>

          </div>

        </div>

      </div>

    </div>
  `;

  const form = container.querySelector("#settingsForm");
  const appName = container.querySelector("#appName");
  const timezone = container.querySelector("#timezone");
  const brandingMode = container.querySelector("#brandingMode");
  const logoInput = container.querySelector("#logo");
  const logoPreview = container.querySelector("#logoPreview");
  const tableBody = container.querySelector("#settingsTableBody");
  const themeColorsTableBody =
  container.querySelector(
    "#themeColorsTableBody"
  );
  const messageBox = container.querySelector("#messageBox");

  const primaryColor =
    container.querySelector("#primaryColor");

  const secondaryColor =
    container.querySelector("#secondaryColor");

  const accentColor =
    container.querySelector("#accentColor");

  const textColor =
    container.querySelector("#textColor");

  const primaryColorPicker =
    container.querySelector("#primaryColorPicker");

  const secondaryColorPicker =
    container.querySelector("#secondaryColorPicker");

  const accentColorPicker =
    container.querySelector("#accentColorPicker");

  const textColorPicker =
    container.querySelector("#textColorPicker");

  let currentLogo = "";

  function showMessage(type, message) {
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

    const colorKeys = [
      "theme.primary_color",
      "theme.secondary_color",
      "theme.accent_color",
      "theme.text_color"
    ];

    Object.entries(data).forEach(([key, value]) => {
      let displayValue = value || "";

      if (key === "branding.logo" && value) {
        displayValue = `
          <img
            src="http://localhost:8000${value}"
            style="max-height:150px;"
          />
        `;
      }

      if (colorKeys.includes(key) && value) {
        displayValue = `
          <div
            class="d-flex align-items-center gap-2"
          >
            <div
              style="
                width:35px;
                height:35px;
                background:${value};
                border:1px solid #ccc;
                border-radius:4px;
              "
            ></div>

            <span>${value}</span>
          </div>
        `;
      }

      const row = document.createElement("tr");

      row.innerHTML = `
        <td>${key}</td>
        <td>${displayValue}</td>
      `;

      tableBody.appendChild(row);
    });
  }

  function bindColorInput(
    textInput,
    colorPicker
  ) {
    colorPicker.addEventListener(
      "input",
      () => {
        textInput.value =
          colorPicker.value;

        console.log(
          `${textInput.id}:`,
          textInput.value
        );
      }
    );

    textInput.addEventListener(
      "input",
      () => {
        const value =
          textInput.value.trim();

        if (
          /^#[0-9A-F]{6}$/i.test(value)
        ) {
          colorPicker.value = value;

          console.log(
            `${textInput.id}:`,
            value
          );
        }
      }
    );
  }

  bindColorInput(
    primaryColor,
    primaryColorPicker
  );

  bindColorInput(
    secondaryColor,
    secondaryColorPicker
  );

  bindColorInput(
    accentColor,
    accentColorPicker
  );

  bindColorInput(
    textColor,
    textColorPicker
  );

  logoInput?.addEventListener(
    "change",
    () => {
      const file =
        logoInput.files?.[0];

      if (!file) return;

      const reader =
        new FileReader();

      reader.onload = (e) => {
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
    const response = await getSettings();
    const settings = response?.data || {};

        console.log(
      "Theme Colors:",
      settings["theme.primary_color"],
      settings["theme.secondary_color"],
      settings["theme.accent_color"],
      settings["theme.text_color"]
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
        "http://localhost:8000" + currentLogo;

      logoPreview.style.display =
        "block";
    }

    primaryColor.value =
      settings["theme.primary_color"] ||
      "#ffffff";

    secondaryColor.value =
      settings["theme.secondary_color"] ||
      "#ccc6c6";

    accentColor.value =
      settings["theme.accent_color"] ||
      "#d6d6d6";

    textColor.value =
      settings["theme.text_color"] ||
      "#060606";

    primaryColorPicker.value =
      primaryColor.value;

    secondaryColorPicker.value =
      secondaryColor.value;

    accentColorPicker.value =
      accentColor.value;

    textColorPicker.value =
      textColor.value;

    console.log(
      "Current Settings:",
      settings
    );

    console.log(
      "Theme Colors:",
      {
        primary:
          primaryColor.value,
        secondary:
          secondaryColor.value,
        accent:
          accentColor.value,
        text:
          textColor.value
      }
    );

    renderTable(settings);

    // ✅ Add this
    renderThemeColorsTable(settings);

  } catch (error) {
    console.error(error);

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
        let logoPath =
          currentLogo;

        const file =
          logoInput.files?.[0];

        if (file) {
          const uploadResponse =
            await uploadFile(file);

          logoPath =
            uploadResponse?.data?.path ||
            "";
        }

        const payload = {
          "branding.app_name":
            appName.value.trim(),

          "app.timezone":
            timezone.value,

          "branding.mode":
            brandingMode.value,

          "branding.logo":
            logoPath,

          "theme.primary_color":
            primaryColor.value,

          "theme.secondary_color":
            secondaryColor.value,

          "theme.accent_color":
            accentColor.value,

          "theme.text_color":
            textColor.value
        };

        console.log(
          "Saving Settings:",
          payload
        );

        await saveSettings(payload);

        document.documentElement.style.setProperty(
          "--primary-color",
          primaryColor.value
        );

        document.documentElement.style.setProperty(
          "--secondary-color",
          secondaryColor.value
        );

        document.documentElement.style.setProperty(
          "--accent-color",
          accentColor.value
        );

        document.documentElement.style.setProperty(
          "--text-color",
          textColor.value
        );

        currentLogo = logoPath;

        showMessage(
          "success",
          "Settings saved successfully."
        );

        await loadSettings();
      } catch (error) {
        console.error(error);

        showMessage(
          "danger",
          error?.message ||
            "Failed to save settings."
        );
      }
    }
  );

  

function renderThemeColorsTable(settings) {
  if (!themeColorsTableBody) return;

  console.log("Theme settings:", settings);

  themeColorsTableBody.innerHTML = "";

  const colors = [
    {
      name: "Primary Color",
      key: "theme.primary_color"
    },
    {
      name: "Secondary Color",
      key: "theme.secondary_color"
    },
    {
      name: "Accent Color",
      key: "theme.accent_color"
    },
    {
      name: "Text Color",
      key: "theme.text_color"
    }
  ];

  colors.forEach(({ name, key }) => {
    const value = settings[key] || "";

    console.log(name, value);

    const row = document.createElement("tr");

    row.innerHTML = `
      <td><strong>${name}</strong></td>

      <td>
        <div
          style="
            width:50px;
            height:35px;
            background:${value || "#fff"};
            border:1px solid #ccc;
            border-radius:4px;
          "
        ></div>
      </td>

      <td>${value || "-"}</td>
    `;

    themeColorsTableBody.appendChild(row);
  });
}

loadSettings();

  return container;
}