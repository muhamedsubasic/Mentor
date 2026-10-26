(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader) return;
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    if (!mobileNavToggleBtn) return;
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });
  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  
  if (scrollTop) {
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /* =========================================================
   *  SPA WIDGET INITIALIZATION 
   * ========================================================= */
  
  let glightboxInstance = null;

  function initSPAWidgets() {
    // 1. AOS (Animations)
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      });
      AOS.refreshHard();
    }

    // 2. GLightbox
    if (typeof GLightbox !== 'undefined') {
      if (glightboxInstance) {
        glightboxInstance.reload();
      } else {
        glightboxInstance = GLightbox({ selector: '.glightbox' });
      }
    }

    // 3. Pure Counter (The numbers)
    if (typeof PureCounter !== 'undefined') {
      new PureCounter();
    }

    // 4. Swiper Sliders
    if (typeof Swiper !== 'undefined') {
      document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
        if (swiperElement.swiper) return; // Skip if already initialized
        
        const configElement = swiperElement.querySelector(".swiper-config");
        if (!configElement) return;
        
        let config = JSON.parse(configElement.innerHTML.trim());

        if (swiperElement.classList.contains("swiper-tab")) {
          if (typeof initSwiperWithCustomPagination === 'function') {
            initSwiperWithCustomPagination(swiperElement, config);
          }
        } else {
          new Swiper(swiperElement, config);
        }
      });
    }
  }

  // Expose the function globally so app.js can trigger it exactly when a page loads
  window.initSPAWidgets = initSPAWidgets;

  // Run initially on page load
  window.addEventListener("load", initSPAWidgets);

})();