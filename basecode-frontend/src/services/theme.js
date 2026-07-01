import { getSettings } from "./settings";

export async function applyTheme() {
  try {
    const response = await getSettings();
    const settings = response?.data || {};

    const primary =
      settings["theme.primary_color"] ||
      "#ffffff";

    const secondary =
      settings["theme.secondary_color"] ||
      "#e0e0e0";

    const accent =
      settings["theme.accent_color"] ||
      "#d6d6d6";

    const text =
      settings["theme.text_color"] ||
      "#060606";

    document.documentElement.style.setProperty(
      "--primary-color",
      primary
    );

    document.documentElement.style.setProperty(
      "--secondary-color",
      secondary
    );

    document.documentElement.style.setProperty(
      "--accent-color",
      accent
    );

    document.documentElement.style.setProperty(
      "--text-color",
      text
    );

    console.log("Theme Applied", {
      primary,
      secondary,
      accent,
      text
    });
  } catch (error) {
    console.error(
      "Failed to load theme",
      error
    );
  }
}