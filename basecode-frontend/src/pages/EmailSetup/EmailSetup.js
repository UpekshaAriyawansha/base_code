import {
  getEmailSettings,
  saveEmailSettings,
  sendTestEmail,
  getEmailLogs
} from "../../services/email";

export default function EmailSetup() {

  const container = document.createElement("div");
  container.classList.add("page");

  container.innerHTML = `
    <div class="container-fluid">

      <div class="card">
        <div class="card-header bg-white text-dark">
          <strong>Email Setup</strong>
        </div>

        <div class="card-body">

          <div id="messageBox"></div>

          <form id="emailForm">

            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">SMTP Host</label>
                <input type="text" id="smtpHost" class="form-control">
              </div>

              <div class="col-md-3">
                <label class="form-label">Port</label>
                <input type="number" id="smtpPort" class="form-control">
              </div>

              <div class="col-md-3">
                <label class="form-label">Encryption</label>
                <select id="encryption" class="form-select">
                  <option value="tls">TLS</option>
                  <option value="ssl">SSL</option>
                  <option value="none">None</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" id="username" class="form-control">
              </div>

              <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-control">
              </div>

              <div class="col-md-6">
                <label class="form-label">Sender Address</label>
                <input type="email" id="senderEmail" class="form-control">
              </div>

              <div class="col-md-6">
                <label class="form-label">Sender Name</label>
                <input type="text" id="senderName" class="form-control">
              </div>

            </div>

            <hr>

            <div class="d-flex justify-content-between">

              <button type="button" id="testEmailBtn" class="btn btn-outline-secondary">
                Send Test Email
              </button>

              <button type="submit" class="btn btn-primary">
                Save Configuration
              </button>

            </div>

          </form>

          <hr>

          <!-- EMAIL LOGS TABLE -->
          <h5 class="mt-4">Sent Email Logs</h5>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Recipient</th>
                  <th>Subject</th>
                  <th>Status</th>
                  <th>Error</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody id="logsTable"></tbody>
            </table>
          </div>

        </div>
      </div>

    </div>
  `;

  const form = container.querySelector("#emailForm");

  const smtpHost = container.querySelector("#smtpHost");
  const smtpPort = container.querySelector("#smtpPort");
  const encryption = container.querySelector("#encryption");
  const username = container.querySelector("#username");
  const password = container.querySelector("#password");
  const senderEmail = container.querySelector("#senderEmail");
  const senderName = container.querySelector("#senderName");

  const testEmailBtn = container.querySelector("#testEmailBtn");
  const messageBox = container.querySelector("#messageBox");

  const logsTable = container.querySelector("#logsTable");

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

  // =========================
  // LOAD SETTINGS
  // =========================
  async function loadSettings() {
    try {
      const response = await getEmailSettings();
      const data = response?.data || {};

      smtpHost.value = data.smtp_host || "";
      smtpPort.value = data.smtp_port || 587;
      encryption.value = data.encryption || "tls";
      username.value = data.username || "";
      password.value = data.password || "";
      senderEmail.value = data.sender_email || "";
      senderName.value = data.sender_name || "";

      console.log("📧 Email Settings Loaded:", data);

    } catch (error) {
      console.error("Load settings error:", error);
    }
  }

  // =========================
  // LOAD EMAIL LOGS
  // =========================
  async function loadLogs() {
    try {
      const response = await getEmailLogs();

      console.log("📊 Email Logs API Response:", response);

      const logs = response?.data || [];

      logsTable.innerHTML = logs.map(log => `
        <tr>
          <td>${log.id}</td>
          <td>${log.recipient}</td>
          <td>${log.subject}</td>
          <td>${log.status}</td>
          <td>${log.error || "-"}</td>
          <td>${log.created_at}</td>
        </tr>
      `).join("");

    } catch (error) {
      console.error("❌ Failed to load email logs:", error);
    }
  }

  // =========================
  // SAVE SETTINGS
  // =========================
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const payload = {
        smtp_host: smtpHost.value.trim(),
        smtp_port: Number(smtpPort.value),
        encryption: encryption.value,
        username: username.value.trim(),
        password: password.value,
        sender_email: senderEmail.value.trim(),
        sender_name: senderName.value.trim()
      };

      console.log("💾 Saving Email Settings:", payload);

      await saveEmailSettings(payload);

      showMessage("success", "Settings saved successfully");

    } catch (error) {
      console.error("Save error:", error);

      showMessage("danger", error.message || "Save failed");
    }
  });

  // =========================
  // TEST EMAIL
  // =========================
  testEmailBtn.addEventListener("click", async () => {

    const email = prompt("Enter test email:");
    if (!email) return;

    try {
      const response = await sendTestEmail({ to: email });

      console.log("📩 Test Email Response:", response);

      showMessage("success", "Test email sent");

      // reload logs after sending email
      loadLogs();

    } catch (error) {
      console.error("Test email error:", error);

      showMessage("danger", error.message || "Failed to send email");
    }
  });

  // =========================
  // INIT
  // =========================
  loadSettings();
  loadLogs();

  return container;
}









// import {
//   getEmailSettings,
//   saveEmailSettings,
//   sendTestEmail
// } from "../../services/email";

// export default function EmailSetup() {

//   const container =
//     document.createElement("div");

//   container.classList.add("page");

//   container.innerHTML = `
//     <div class="container-fluid">

//       <div class="card">

//         <div class="card-header bg-white text-dark">
//           <strong>Email Setup</strong>
//         </div>

//         <div class="card-body">

//           <div id="messageBox"></div>

//           <form id="emailForm">

//             <div class="row g-3">

//               <div class="col-md-6">
//                 <label class="form-label">
//                   SMTP Host
//                 </label>

//                 <input
//                   type="text"
//                   id="smtpHost"
//                   class="form-control"
//                   placeholder="smtp.gmail.com"
//                 >
//               </div>

//               <div class="col-md-3">
//                 <label class="form-label">
//                   Port
//                 </label>

//                 <input
//                   type="number"
//                   id="smtpPort"
//                   class="form-control"
//                   placeholder="587"
//                 >
//               </div>

//               <div class="col-md-3">
//                 <label class="form-label">
//                   Encryption
//                 </label>

//                 <select
//                   id="encryption"
//                   class="form-select"
//                 >
//                   <option value="tls">
//                     TLS
//                   </option>

//                   <option value="ssl">
//                     SSL
//                   </option>

//                   <option value="none">
//                     None
//                   </option>
//                 </select>
//               </div>

//               <div class="col-md-6">
//                 <label class="form-label">
//                   Username
//                 </label>

//                 <input
//                   type="text"
//                   id="username"
//                   class="form-control"
//                 >
//               </div>

//               <div class="col-md-6">
//                 <label class="form-label">
//                   Password
//                 </label>

//                 <input
//                   type="password"
//                   id="password"
//                   class="form-control"
//                 >
//               </div>

//               <div class="col-md-6">
//                 <label class="form-label">
//                   Sender Address
//                 </label>

//                 <input
//                   type="email"
//                   id="senderEmail"
//                   class="form-control"
//                   placeholder="noreply@example.com"
//                 >
//               </div>

//               <div class="col-md-6">
//                 <label class="form-label">
//                   Sender Name
//                 </label>

//                 <input
//                   type="text"
//                   id="senderName"
//                   class="form-control"
//                   placeholder="Basecode"
//                 >
//               </div>

//             </div>

//             <hr>

//             <div class="d-flex justify-content-between">

//               <button
//                 type="button"
//                 id="testEmailBtn"
//                 class="btn btn-outline-secondary"
//               >
//                 Send Test Email
//               </button>

//               <button
//                 type="submit"
//                 class="btn btn-primary"
//               >
//                 Save Configuration
//               </button>

//             </div>

//           </form>

//         </div>

//       </div>

//     </div>
//   `;

//   const form =
//     container.querySelector(
//       "#emailForm"
//     );

//   const smtpHost =
//     container.querySelector(
//       "#smtpHost"
//     );

//   const smtpPort =
//     container.querySelector(
//       "#smtpPort"
//     );

//   const encryption =
//     container.querySelector(
//       "#encryption"
//     );

//   const username =
//     container.querySelector(
//       "#username"
//     );

//   const password =
//     container.querySelector(
//       "#password"
//     );

//   const senderEmail =
//     container.querySelector(
//       "#senderEmail"
//     );

//   const senderName =
//     container.querySelector(
//       "#senderName"
//     );

//   const testEmailBtn =
//     container.querySelector(
//       "#testEmailBtn"
//     );

//   const messageBox =
//     container.querySelector(
//       "#messageBox"
//     );

//   function showMessage(
//     type,
//     message
//   ) {

//     messageBox.innerHTML = `
//       <div class="alert alert-${type}">
//         ${message}
//       </div>
//     `;

//     setTimeout(() => {
//       messageBox.innerHTML = "";
//     }, 3000);
//   }

//   async function loadSettings() {

//     try {

//       const response =
//         await getEmailSettings();

//       const data =
//         response?.data || {};

//       smtpHost.value =
//         data.smtp_host || "";

//       smtpPort.value =
//         data.smtp_port || 587;

//       encryption.value =
//         data.encryption || "tls";

//       username.value =
//         data.username || "";

//       password.value =
//         data.password || "";

//       senderEmail.value =
//         data.sender_email || "";

//       senderName.value =
//         data.sender_name || "";

//       console.log(
//         "Email Settings Loaded",
//         data
//       );

//     } catch (error) {

//       console.error(error);
//     }
//   }

//   form.addEventListener(
//     "submit",
//     async (e) => {

//       e.preventDefault();

//       try {

//         const payload = {

//           smtp_host:
//             smtpHost.value.trim(),

//           smtp_port:
//             Number(
//               smtpPort.value
//             ),

//           encryption:
//             encryption.value,

//           username:
//             username.value.trim(),

//           password:
//             password.value,

//           sender_email:
//             senderEmail.value.trim(),

//           sender_name:
//             senderName.value.trim()
//         };

//         console.log(
//           "Saving Email Settings",
//           payload
//         );

//         await saveEmailSettings(
//           payload
//         );

//         // showMessage(
//         //   "success",
//         //   "Email configuration saved successfully."
//         // );

//             console.log("📩 Send Test Email Response:", response);

//     showMessage("success", "Test email sent successfully");

//       } catch (error) {

//         console.error(error);

//         showMessage(
//           "danger",
//           error.message ||
//           "Failed to save email settings."
//         );
//       }
//     }
//   );




// testEmailBtn.addEventListener("click", async () => {

//   const email = prompt("Enter test email:");

//   if (!email) return;

//   try {

//     await sendTestEmail({ to: email });

//     showMessage("success", "Test email sent successfully");

//   } catch (error) {

//     console.error(error);

//     showMessage(
//       "danger",
//       error.message || "Failed to send email"
//     );
//   }
// });

//   loadSettings();

//   return container;
// }