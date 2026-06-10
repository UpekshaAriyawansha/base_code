export function navigate(route) {

    // update URL
    window.location.hash = route;

    // force app re-render immediately
    window.dispatchEvent(new Event('navigate'));
}