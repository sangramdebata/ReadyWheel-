// login-popup starts
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
// DOM Elements
const carName = document.getElementById('carName');
const carDescription = document.getElementById('carDescription');
const specificationsTable = document.getElementById('specificationsTable');
const carouselInner = document.getElementById('carouselInner');
const thumbnailContainer = document.getElementById('thumbnailContainer');
const bookingCarName = document.getElementById('bookingCarName');
const bookingPrice = document.getElementById('bookingPrice');
const favoriteBtn = document.getElementById('favoriteBtn');
const pickupDate = document.getElementById('pickupDate');
const pickupTime = document.getElementById('pickupTime');
const dropoffDate = document.getElementById('dropoffDate');
const dropoffTime = document.getElementById('dropoffTime');
const durationText = document.getElementById('durationText');
const totalPrice = document.getElementById('totalPrice');
const bookNowBtn = document.getElementById('bookNowBtn');
const locationInput = document.getElementById('locationInput');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Get selected car from localStorage
    const selectedCar = JSON.parse(localStorage.getItem("selectedCar"));
    
    if (!selectedCar) {
        // Redirect to listing page if no car is selected
        window.location.href = "index.html";
        return;
    }
    
    // Populate car details
    populateCarDetails(selectedCar);
    
    // Set up event listeners
    setupEventListeners(selectedCar);
});

// Populate car details
function populateCarDetails(car) {
    // Set page title
    document.title = `${car.name} - Drivee Car Rental`;
    
    // Car name and description
    carName.textContent = car.name;
    carDescription.textContent = car.description;
    
    // Booking section
    bookingCarName.textContent = car.name;
    bookingPrice.textContent = car.priceDisplay;
    
    // Specifications table
    const specs = [
        { label: 'Body', value: car.body },
        { label: 'Seat', value: `${car.seats} Seats` },
        { label: 'Door', value: `${car.doors} Doors` },
        { label: 'Luggage', value: car.luggage },
        { label: 'Transmission', value: car.transmission },
        { label: 'Drive', value: car.drive },
        { label: 'Year', value: car.year },
        { label: 'Mileage', value: car.mileage },
        { label: 'Fuel Type', value: car.fuel },
        { label: 'Engine', value: car.engine }
    ];
    
    specificationsTable.innerHTML = '';
    specs.forEach(spec => {
        specificationsTable.innerHTML += `
            <tr>
                <td class="text-muted">${spec.label}</td>
                <td class="text-end">${spec.value}</td>
            </tr>
        `;
    });
    
    // Carousel images
    carouselInner.innerHTML = '';
    car.images.forEach((image, index) => {
        carouselInner.innerHTML += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="${image}" class="d-block w-100" alt="${car.name} ${index + 1}">
            </div>
        `;
    });
    
    // Thumbnail images
    thumbnailContainer.innerHTML = '';
    car.images.forEach((image, index) => {
        thumbnailContainer.innerHTML += `
            <div class="col-4">
                <img src="${image}" class="img-thumbnail" alt="Thumbnail ${index + 1}" data-bs-target="#carCarousel" data-bs-slide-to="${index}">
            </div>
        `;
    });
    
    // Set default dates for booking
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    pickupDate.value = formatDate(today);
    pickupTime.value = '10:00';
    dropoffDate.value = formatDate(tomorrow);
    dropoffTime.value = '10:00';
    
    // Calculate initial duration and price
    calculateDurationAndPrice(car);
}

// Set up event listeners
function setupEventListeners(car) {
    // Favorite button
    favoriteBtn.addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            icon.style.color = 'red';
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            icon.style.color = '';
        }
    });
    
    // Date and time inputs
    const dateTimeInputs = [pickupDate, pickupTime, dropoffDate, dropoffTime];
    dateTimeInputs.forEach(input => {
        input.addEventListener('change', function() {
            calculateDurationAndPrice(car);
        });
    });
    
    // Thumbnail click handlers
    const thumbnails = document.querySelectorAll('.img-thumbnail');
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const slideIndex = this.getAttribute('data-bs-slide-to');
            const carousel = document.getElementById('carCarousel');
            const bsCarousel = new bootstrap.Carousel(carousel);
            bsCarousel.to(parseInt(slideIndex));
        });
    });
    
    // Book Now button
    bookNowBtn.addEventListener('click', function() {
        if (!validateBookingForm()) {
            return;
        }
        
        // In a real application, this would submit the booking to a server
        alert(`Booking successful! Your ${car.name} will be ready for pickup.`);
    });
}

// Calculate duration and price
function calculateDurationAndPrice(car) {
    if (!pickupDate.value || !pickupTime.value || !dropoffDate.value || !dropoffTime.value) {
        return;
    }
    
    const pickup = new Date(`${pickupDate.value}T${pickupTime.value}`);
    const dropoff = new Date(`${dropoffDate.value}T${dropoffTime.value}`);
    
    // Validate dates
    if (dropoff <= pickup) {
        alert('Drop-off date and time must be after pick-up date and time');
        return;
    }
    
    // Calculate duration in hours
    const durationMs = dropoff - pickup;
    const durationHours = durationMs / (1000 * 60 * 60);
    
    // Calculate price (hourly rate based on car price)
    const hourlyRate = car.price / 1000; // Example calculation
    const price = hourlyRate * durationHours;
    
    // Update UI
    durationText.textContent = `${durationHours.toFixed(2)} hours`;
    totalPrice.textContent = `$${price.toFixed(2)}`;
}

// Validate booking form
function validateBookingForm() {
    if (!locationInput.value) {
        alert('Please enter a pickup location');
        locationInput.focus();
        return false;
    }
    
    if (!pickupDate.value || !pickupTime.value) {
        alert('Please select a pickup date and time');
        return false;
    }
    
    if (!dropoffDate.value || !dropoffTime.value) {
        alert('Please select a drop-off date and time');
        return false;
    }
    
    const pickup = new Date(`${pickupDate.value}T${pickupTime.value}`);
    const dropoff = new Date(`${dropoffDate.value}T${dropoffTime.value}`);
    
    if (dropoff <= pickup) {
        alert('Drop-off date and time must be after pick-up date and time');
        return false;
    }
    
    return true;
}

// Helper function to format date as YYYY-MM-DD
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}