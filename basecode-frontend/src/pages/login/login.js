import { login as apiLogin } from "../../services/auth.js";
import { setSession } from "../../services/session.js";
import { navigate } from "../../services/navigate.js";
import { showToast } from "../../utils/toast.js";

export default function LoginPage() {

  const container = document.createElement("div");

  container.innerHTML = `
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light">

      <div class="card shadow border-0 p-4" style="width:400px">

        <h3 class="text-center mb-4">Basecode Login</h3>

        <form id="loginForm">

          <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" id="email" required />
          </div>

          <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" id="password" required />
          </div>

          <button class="btn btn-dark w-100" type="submit">
            Login
          </button>

        </form>

      </div>

    </div>
  `;

  container.querySelector("#loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = container.querySelector("#email").value;
    const password = container.querySelector("#password").value;

    try {
      const res = await apiLogin(email, password);

      console.log("LOGIN RESPONSE:", res);

      // ❌ backend failed response safety
      if (!res || !res.token) {
        throw new Error(res?.message || "Invalid credentials");
      }

      const user = res.user;

      if (!user || !user.email) {
        throw new Error("User data missing from response");
      }

      // save session
      setSession(res.token, user);

      // success toast
      showToast(`${user.email} login successful`);

      navigate("#/dashboard");

    } catch (err) {
      console.log("LOGIN ERROR:", err);

      showToast(err.message || "Login failed", "error");
    }
  });

  return container;
}