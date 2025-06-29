const themes = ["dark-theme", "light-theme", "discord-theme","reddit-theme","solarized-dark-theme","pastel-theme","cyberpunk-theme"];

function set_theme(theme) {
  const body = document.body;
  themes.forEach(t => body.classList.remove(t));
  body.classList.add(theme);
  localStorage.setItem("theme", theme);
}

window.addEventListener("DOMContentLoaded", () => {
  const saved_theme = localStorage.getItem("theme") || "dark-theme";
  set_theme(saved_theme);
});

window.addEventListener("keydown", (e) => {
  
  if (e.key.toLowerCase() === '`') {
    e.preventDefault();
    const body = document.body;
    let current_theme = themes.find(th => body.classList.contains(th)) || themes[0];
    let current_index = themes.indexOf(current_theme);
    let next_index = (current_index + 1) % themes.length;
    set_theme(themes[next_index]);
  }
});
