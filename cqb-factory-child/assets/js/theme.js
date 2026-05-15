document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menuToggle");
  const mainMenu = document.getElementById("mainMenu");

  if (menuToggle && mainMenu) {
    menuToggle.addEventListener("click", function () {
      mainMenu.classList.toggle("open");
    });
  }

  const tabButtons = document.querySelectorAll(".tab-btn");
  const tabPanels = document.querySelectorAll(".tab-panel");

  tabButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      const target = button.getAttribute("data-target");

      tabButtons.forEach(function (btn) {
        btn.classList.remove("active");
        btn.setAttribute("aria-selected", "false");
      });

      tabPanels.forEach(function (panel) {
        panel.classList.remove("active");
      });

      button.classList.add("active");
      button.setAttribute("aria-selected", "true");

      const targetPanel = document.getElementById(target);
      if (targetPanel) {
        targetPanel.classList.add("active");
      }
    });
  });

  const revealNodes = document.querySelectorAll(".reveal");
  if (!revealNodes.length || typeof IntersectionObserver === "undefined") {
    return;
  }

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("in-view");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12 }
  );

  revealNodes.forEach(function (node) {
    observer.observe(node);
  });
});
