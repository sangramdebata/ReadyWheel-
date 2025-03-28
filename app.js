// This file extends the existing app.js with additional functionality for profile and login

// Original scroll reveal code from app.js
const scrollRevealOption = {
    distance: "500px",
    origin: "bottom",
    duration: 3000,
};
  
ScrollReveal().reveal(".header__image img", {
    ...scrollRevealOption,
    origin: "right",
});
ScrollReveal().reveal(".header__content h2", {
    ...scrollRevealOption,
    delay: 200,
});
ScrollReveal().reveal(".header__content h1", {
    ...scrollRevealOption,
    delay: 700,
});
ScrollReveal().reveal(".header__content .section__description", {
    ...scrollRevealOption,
    delay: 1100,
});

// Login pop up functionality
const loginBtn = document.getElementById('login-btn');
const loginPopup = document.getElementById('login-popup');
const registerPopup = document.getElementById('register-popup');
const closeLogin = document.getElementById('close-login');
const closeRegister = document.getElementById('close-register');
const showRegister = document.getElementById('show-register');
const showLogin = document.getElementById('show-login');
const togglePasswordIcons = document.querySelectorAll('.toggle-password');

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        loginPopup.style.display = 'flex';
    });
}

if (closeLogin) {
    closeLogin.addEventListener('click', () => {
        loginPopup.style.display = 'none';
    });
}

if (closeRegister) {
    closeRegister.addEventListener('click', () => {
        registerPopup.style.display = 'none';
    });
}

if (showRegister) {
    showRegister.addEventListener('click', (e) => {
        e.preventDefault();
        loginPopup.style.display = 'none';
        registerPopup.style.display = 'flex';
    });
}

if (showLogin) {
    showLogin.addEventListener('click', (e) => {
        e.preventDefault();
        registerPopup.style.display = 'none';
        loginPopup.style.display = 'flex';
    });
}

window.addEventListener('click', (event) => {
    if (event.target === loginPopup) {
        loginPopup.style.display = 'none';
    } else if (event.target === registerPopup) {
        registerPopup.style.display = 'none';
    }
});

if (togglePasswordIcons.length > 0) {
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
}

// Enhanced login functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if login form exists and add event listener
    const loginForm = document.querySelector('#log_form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get username and password
            const username = document.getElementById('login-username').value;
            const password = document.getElementById('login-password').value;
            
            // In a real application, you would validate these credentials with a server
            // For demo purposes, we'll use predefined user data
            const userData = {
                name: "Chaitanya Behera",
                email: "chaitanya@gmail.com",
                phone: "(+91) 9998887775",
                address: "123 Main Street, Angul, Odisha, India",
                memberSince: "March 2025",
                aadhar: "XXXX XXXX 1234",
                license: "DL-1234567890",
                profilePic: "assets/profile-placeholder.jpg",
                transactions: [
                    {
                        date: "15 Mar 2025",
                        car: "Tata Nexon",
                        duration: "3 days",
                        amount: "₹4,500",
                        status: "Completed",
                    },
                    {
                        date: "28 Feb 2025",
                        car: "Mahindra XUV300",
                        duration: "1 day",
                        amount: "₹1,800",
                        status: "Completed",
                    },
                    {
                        date: "10 Feb 2025",
                        car: "Honda City",
                        duration: "5 days",
                        amount: "₹8,000",
                        status: "Completed",
                    }
                ]
            };
            
            // Save login status and user data
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userData', JSON.stringify(userData));
            
            // Close the popup
            loginPopup.style.display = 'none';
            
            // Refresh the page to update UI
            window.location.reload();
        });
    }
    
    // Check if register form exists and add event listener
    const registerForm = document.querySelector('#reg_form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const fullName = document.getElementById('reg-name').value;
            const mobile = document.getElementById('reg-mobile').value;
            const email = document.getElementById('reg-email').value;
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm-password').value;
            
            // Validate password match
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            // Create user data object
            const userData = {
                name: fullName,
                email: email,
                phone: mobile,
                address: "123 Main Street, Angul, Odisha, India", // Default address
                memberSince: new Date().toLocaleString('default', { month: 'long', year: 'numeric' }),
                aadhar: "XXXX XXXX 1234", // Default value
                license: "DL-1234567890", // Default value
                profilePic: "assets/profile-placeholder.jpg",
                transactions: [] // Empty transactions for new users
            };
            
            // Save login status and user data
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userData', JSON.stringify(userData));
            
            // Close the popup
            registerPopup.style.display = 'none';
            
            // Refresh the page to update UI
            window.location.reload();
        });
    }
    
    // Check if user is logged in and update UI accordingly
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const loginBtn = document.getElementById('login-btn');
    const profileContainer = document.getElementById('profile-container');
    
    if (isLoggedIn && loginBtn && profileContainer) {
        loginBtn.style.display = 'none';
        profileContainer.style.display = 'block';
    }
});