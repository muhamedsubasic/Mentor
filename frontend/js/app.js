$(document).ready(function () {

  const app = $.spapp({
    templateDir: "./views/",
    defaultView: "home",
    pageNotFound: false
  });

  // Fix for not breaking the templates animations
  $("main#spapp > section").each(function() {
    app.route({
      view: $(this).attr("id"),
      onReady: function() {
        if (typeof window.initSPAWidgets === "function") {
          window.initSPAWidgets();
        }
      }
    });
  });

  function onViewChange() {
    const view = location.hash.slice(1) || "home";

    // 1. HIDE ALL SECTIONS, SHOW ONLY THE ACTIVE ONE
    $("main#spapp > section").css("display", "none");
    $("#" + view).css("display", "block");

    // 2. Determine which layout to use
    const isAdmin = view.startsWith("admin-");
    const isUser = view.startsWith("user-");
    const isAuth = ["login", "register"].includes(view);

    // 3. Hide ALL layouts (Header, Footer, Sidebars) by default
    $("#header, #footer, #admin-sidebar, #user-sidebar").hide();
    $("body").removeClass("admin-mode user-mode auth-mode");

    // 4. Show the correct layout
    if (isAdmin) {
      $("#admin-sidebar").show();
      $("body").addClass("admin-mode");
    } else if (isUser) {
      $("#user-sidebar").show();
      $("body").addClass("user-mode");
    } else if (isAuth) {
      $("body").addClass("auth-mode");
    } else {
      // Public Layout
      $("#header, #footer").show();
    }

    // 5. Highlight active link in the correct sidebar
    if (isAdmin) {
      $(".admin-sidebar .nav-link").removeClass("active");
      $(".admin-sidebar .nav-link[href='#" + view + "']").addClass("active");
    } else if (isUser) {
      $(".user-sidebar .nav-link").removeClass("active");
      $(".user-sidebar .nav-link[href='#" + view + "']").addClass("active");
    } else {
      $("#main-nav .nav-link").removeClass("active");
      $("#main-nav .nav-link[href='#" + view + "']").addClass("active");
    }

    // 6. Update Title
    let title = $("#main-nav .nav-link[href='#" + view + "']").text().trim() || view;
    document.title = title ? title + " - Mentor" : "Mentor";

    // 7. Close Mobile Nav
    if (document.body.classList.contains("mobile-nav-active")) {
      const btn = document.querySelector(".mobile-nav-toggle");
      document.body.classList.remove("mobile-nav-active");
      if (btn) {
        btn.classList.remove("bi-x");
        btn.classList.add("bi-list");
      }
    }

    window.scrollTo(0, 0);
  }

  window.addEventListener("hashchange", onViewChange);
  onViewChange();

  app.run();
});