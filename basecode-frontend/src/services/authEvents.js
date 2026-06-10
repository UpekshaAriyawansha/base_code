const AUTH_EVENT = "auth-change";

export function emitAuthChange() {
    window.dispatchEvent(new Event(AUTH_EVENT));
}

export function onAuthChange(callback) {
    window.addEventListener(AUTH_EVENT, callback);
}