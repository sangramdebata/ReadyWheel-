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

// Login pop up end

// Global variables
let cars = [];
let filteredCars = [];

function createCarCard(car) {
    const priceDisplay = new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(car.price);

    return `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="car-card">
                <img src="${car.main_image}" alt="${car.name}" class="img-fluid">
                <div class="car-info">
                    <h3>${car.name}</h3>
                    <p class="category">${car.category}</p>
                    <p class="price">${priceDisplay}</p>
                    <div class="specs">
                        <span><i class="fas fa-cog"></i> ${car.transmission}</span>
                        <span><i class="fas fa-gas-pump"></i> ${car.fuel_type}</span>
                        <span><i class="fas fa-users"></i> ${car.passenger_capacity}</span>
                    </div>
                    <button onclick="viewCarDetails(${car.id})" class="view-details btn btn-primary">View Details</button>
                </div>
            </div>
        </div>
    `;
}

function displayCars(carsToDisplay) {
    const carGrid = document.querySelector('.car-grid');
    if (!carGrid) return;
    
    if (carsToDisplay.length === 0) {
        carGrid.innerHTML = '<div class="col-12"><div class="no-results">No vehicles found matching your criteria.</div></div>';
        return;
    }
    
    carGrid.innerHTML = carsToDisplay.map(car => createCarCard(car)).join('');
    
    // Update results count
    const resultsCount = document.getElementById('results-count');
    if (resultsCount) {
        resultsCount.textContent = `Showing ${carsToDisplay.length} vehicles from ${cars.length}`;
    }
}

function setupEventListeners() {
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const transmissionFilter = document.getElementById('transmission-filter');
    const fuelFilter = document.getElementById('fuel-filter');
    const modelButtons = document.querySelectorAll('.model-btn');

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', applyFilters);
    }
    if (priceFilter) {
        priceFilter.addEventListener('change', applyFilters);
    }
    if (transmissionFilter) {
        transmissionFilter.addEventListener('change', applyFilters);
    }
    if (fuelFilter) {
        fuelFilter.addEventListener('change', applyFilters);
    }

    // Add click event for model buttons
    modelButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            // Remove active class from all buttons
            modelButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            e.target.classList.add('active');
            applyFilters();
        });
    });
}

function applyFilters() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const categoryValue = document.getElementById('category-filter').value;
    const priceValue = document.getElementById('price-filter').value;
    const transmissionValue = document.getElementById('transmission-filter').value;
    const fuelValue = document.getElementById('fuel-filter').value;
    const activeModelBtn = document.querySelector('.model-btn.active');
    const modelValue = activeModelBtn ? activeModelBtn.getAttribute('data-model') : 'all';

    filteredCars = cars.filter(car => {
        const matchesSearch = car.name.toLowerCase().includes(searchTerm) ||
                            car.brand.toLowerCase().includes(searchTerm) ||
                            car.description.toLowerCase().includes(searchTerm);
        const matchesCategory = categoryValue === 'all' || car.category === categoryValue;
        const matchesTransmission = transmissionValue === 'all' || car.transmission === transmissionValue;
        const matchesFuel = fuelValue === 'all' || car.fuel_type === fuelValue;
        const matchesModel = modelValue === 'all' || car.model === modelValue;
        
        let matchesPrice = true;
        if (priceValue !== 'all') {
            const price = car.price;
            switch (priceValue) {
                case 'under-5l':
                    matchesPrice = price < 500000;
                    break;
                case '5l-10l':
                    matchesPrice = price >= 500000 && price < 1000000;
                    break;
                case '10l-20l':
                    matchesPrice = price >= 1000000 && price < 2000000;
                    break;
                case 'over-20l':
                    matchesPrice = price >= 2000000;
                    break;
            }
        }

        return matchesSearch && matchesCategory && matchesPrice && 
               matchesTransmission && matchesFuel && matchesModel;
    });

    displayCars(filteredCars);
}

function viewCarDetails(carId) {
    const selectedCar = cars.find(car => car.id === carId);
    if (selectedCar) {
        // Store car data in localStorage for the details page
        localStorage.setItem('selectedCar', JSON.stringify(selectedCar));
        // Redirect to car details page with ID parameter
        window.location.href = `car-detail.php?id=${carId}`;
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
    // Use the initialVehicles data passed from PHP
    if (typeof initialVehicles !== 'undefined' && initialVehicles) {
        cars = initialVehicles;
        filteredCars = [...cars];
        displayCars(filteredCars);
    } else {
        const carGrid = document.querySelector('.car-grid');
        if (carGrid) {
            carGrid.innerHTML = '<div class="col-12"><div class="error-message">Error: No vehicle data available.</div></div>';
        }
    }
    
    setupEventListeners();
});