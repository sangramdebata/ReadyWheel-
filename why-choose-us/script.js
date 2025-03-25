// login popup
const loginBtn = document.getElementById('login-btn');
const loginPopup = document.getElementById('login-popup');
const registerPopup = document.getElementById('register-popup');
const closeLogin = document.getElementById('close-login');
const closeRegister = document.getElementById('close-register');
const showRegister = document.getElementById('show-register');
const showLogin = document.getElementById('show-login');
const togglePasswordIcons = document.querySelectorAll('.toggle-password');

loginBtn.addEventListener('click', () => {
    loginPopup.style.display = 'flex';
});

closeLogin.addEventListener('click', () => {
    loginPopup.style.display = 'none';
});

closeRegister.addEventListener('click', () => {
    registerPopup.style.display = 'none';
});

showRegister.addEventListener('click', (e) => {
    e.preventDefault();
    loginPopup.style.display = 'none';
    registerPopup.style.display = 'flex';
});

showLogin.addEventListener('click', (e) => {
    e.preventDefault();
    registerPopup.style.display = 'none';
    loginPopup.style.display = 'flex';
});

window.addEventListener('click', (event) => {
    if (event.target === loginPopup) {
        loginPopup.style.display = 'none';
    } else if (event.target === registerPopup) {
        registerPopup.style.display = 'none';
    }
});

togglePasswordIcons.forEach(icon => {
    icon.addEventListener('click', () => {
        const targetId = icon.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);
        if (targetInput.type === 'password') {
            targetInput.type = 'text';
            icon.innerHTML = '&#128064;';
        } else {
            targetInput.type = 'password';
            icon.innerHTML = '&#128065;';
        }
    });
});

  //Login pop up end
// Initialize AOS Animation Library
document.addEventListener("DOMContentLoaded", () => {
  // Check if AOS is already defined
  if (typeof AOS !== "undefined") {
    AOS.init({
      duration: 800,
      easing: "ease-in-out",
      once: true,
    })
  } else {
    console.warn("AOS is not defined. Make sure AOS library is included.")
  }

  // Navbar scroll effect
  const navbar = document.querySelector(".navbar")

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled")
    } else {
      navbar.classList.remove("scrolled")
    }
  })

  // Smooth scrolling for anchor links
  document.querySelectorAll("a.scroll-btn").forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()

      const targetId = this.getAttribute("href")
      const targetElement = document.querySelector(targetId)

      window.scrollTo({
        top: targetElement.offsetTop - 80,
        behavior: "smooth",
      })
    })
  })

  // Testimonial carousel functionality
  const testimonials = document.querySelectorAll(".testimonial-item")
  const currentIndex = 0

  // Only initialize carousel if there are more than 3 testimonials
  if (testimonials.length > 3) {
    setInterval(() => {
      testimonials.forEach((item) => {
        item.style.opacity = "0"
        item.style.transform = "translateX(-20px)"
        item.style.transition = "opacity 0.5s ease, transform 0.5s ease"
      })

      setTimeout(() => {
        // Rearrange testimonials
        const carousel = document.getElementById("testimonialCarousel")
        const firstItem = carousel.firstElementChild
        carousel.appendChild(firstItem)

        testimonials.forEach((item) => {
          item.style.opacity = "1"
          item.style.transform = "translateX(0)"
        })
      }, 500)
    }, 5000)
  }

  // Counter animation
  const counters = document.querySelectorAll(".counter")

  const animateCounter = (counter) => {
    const target = Number.parseInt(counter.innerText.replace(/[^\d]/g, ""))
    const count = 0
    const increment = target / 100

    const updateCount = () => {
      const currentCount = Math.ceil(count)

      if (currentCount < target) {
        counter.innerText = currentCount + (counter.innerText.includes("+") ? "+" : "")
        const newCount = count + increment
        count = newCount
        setTimeout(updateCount, 10)
      } else {
        counter.innerText = target + (counter.innerText.includes("+") ? "+" : "")
      }
    }

    updateCount()
  }

  // Intersection Observer for counters
  const counterObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target)
          counterObserver.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.5 },
  )

  counters.forEach((counter) => {
    counterObserver.observe(counter)
  })
})


