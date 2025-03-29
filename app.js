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
            
            const formData = new FormData(this);
            
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store user data in localStorage
                    localStorage.setItem('isLoggedIn', 'true');
                    localStorage.setItem('userData', JSON.stringify(data.user));
                    
                    // Update UI
                    const loginBtn = document.getElementById('login-btn');
                    const profileContainer = document.getElementById('profile-container');
                    
                    if (loginBtn && profileContainer) {
                        loginBtn.style.display = 'none';
                        profileContainer.style.display = 'block';
                    }
                    
                    // Close popup
                    loginPopup.style.display = 'none';
                    
                    // Show success message
                    alert('Login successful!');
                } else {
                    alert(data.message || 'Login failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during login');
            });
        });
    }
    
    // Check if register form exists and add event listener
    const registerForm = document.querySelector('#reg_form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm-password').value;
            
            // Validate password match
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store user data in localStorage
                    localStorage.setItem('isLoggedIn', 'true');
                    
                    // Update UI
                    const loginBtn = document.getElementById('login-btn');
                    const profileContainer = document.getElementById('profile-container');
                    
                    if (loginBtn && profileContainer) {
                        loginBtn.style.display = 'none';
                        profileContainer.style.display = 'block';
                    }
                    
                    // Close popup
                    registerPopup.style.display = 'none';
                    
                    // Show success message
                    alert('Registration successful!');
                } else {
                    alert(data.message || 'Registration failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during registration');
            });
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