document.addEventListener("DOMContentLoaded", function () {
    var lastScrollTop = 0;
    var navbar = document.getElementById("navbar");
    var navButtons = document.querySelectorAll("#navButtons a");
  
    window.addEventListener(
      "scroll",
      function () {
        var st = window.pageYOffset || document.documentElement.scrollTop;
  
        if (st > lastScrollTop) {
          // Scroll Down
          if (window.innerWidth <= 767) {
            navbar.style.top = "-100px"; // Adjust the value as needed
          }
        } else {
          // Scroll Up
          navbar.style.top = "0";
        }
  
        lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
  
        // Highlight the current section
        var sections = document.querySelectorAll(".category");
        sections.forEach(function (section) {
          var rect = section.getBoundingClientRect();
          var button = document.querySelector(
            '#navButtons a[href="#' + section.id + '"] button'
          );
          if (
            rect.top >= 0 &&
            rect.bottom <=
              (window.innerHeight || document.documentElement.clientHeight)
          ) {
            button.classList.add("active");
          } else {
            button.classList.remove("active");
          }
        });
      },
      false
    );
  
    navButtons.forEach(function (navButton) {
      navButton.addEventListener("click", function () {
        if (window.innerWidth <= 767) {
          navbar.style.top = "-100px"; // Hide navbar on button click
        }
      });
    });
  });
  