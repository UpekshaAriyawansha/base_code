// import { getUser } from "./session.js";

// export function hasPermission(permission) {

//   const user = getUser();

//   console.log("Current User:", user);
//   console.log("Permissions:", user?.permissions);

//   return user?.permissions?.includes(permission) || false;
// }


/**
 * Get all permissions of the current user
 */
export function getPermissions() {

  try {

    const permissions = JSON.parse(
      localStorage.getItem("permissions") || "[]"
    );

    return Array.isArray(permissions)
      ? permissions
      : [];

  } catch (error) {

    console.error(
      "Failed to parse permissions:",
      error
    );

    return [];
  }
}

/**
 * Check a single permission
 *
 * Example:
 * hasPermission("users.edit")
 */
export function hasPermission(permission) {

  if (!permission) {
    return false;
  }

  return getPermissions().includes(permission);
}

/**
 * Check if user has at least one permission
 *
 * Example:
 * hasAnyPermission([
 *   "users.create",
 *   "users.edit"
 * ])
 */
export function hasAnyPermission(permissionList) {

  if (!Array.isArray(permissionList)) {
    return false;
  }

  const permissions = getPermissions();

  return permissionList.some(permission =>
    permissions.includes(permission)
  );
}

/**
 * Check if user has all permissions
 *
 * Example:
 * hasAllPermissions([
 *   "users.view",
 *   "users.edit"
 * ])
 */
export function hasAllPermissions(permissionList) {

  if (!Array.isArray(permissionList)) {
    return false;
  }

  const permissions = getPermissions();

  return permissionList.every(permission =>
    permissions.includes(permission)
  );
}

/**
 * Optional helper
 * Check if current user is super admin
 */
export function isSuperAdmin() {

  return hasPermission("*");
}