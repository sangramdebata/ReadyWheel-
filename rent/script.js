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
// Car data array
const cars = [
    {
        // window.location.href = "car-details.html";

        id: 1,
        name: "Maruti-Suzuki Swift",
        category: "Family Car",
        price: 34500,
        priceDisplay: "$34,500",
        transmission: "Manual",
        fuel: "Diesel",
        passenger: "4 Persons",
        image: "../assets/swift1.jpg",
        images: [
            "../assets/swift1.jpg",
            "../assets/swift2.jpg",
            "../assets/swift3.jpg",
            "../assets/swift4.jpg",
        ],
        brand: "Maruti Suzuki",
        class: "Car",
        model: "Family Car",
        color: "Blue",
        year: 2020,
        mileage: 200,
        engine: "3000cc",
        drive: "4WD",
        body: "Coupe",
        seats: 4,
        doors: 4,
        luggage: 150,
        description: "Maruti Suzuki Swift – Stylish & Reliable!Rent the fuel-efficient Swift for a smooth, comfortable drive. Perfect for city trips or getaways, it offers great mileage, spacious interiors, and a sporty design.Book now for an effortless ride! 🚗✨"
    },
    {
        id: 2,
        name: "TATA Tiago",
        category: "Family Car",
        price: 45000,
        priceDisplay: "$45,000",
        transmission: "Automatic",
        fuel: "Diesel",
        passenger: "5 Persons",
        image: "../assets/tiago3.jpg",
        images: [
            "../assets/tiago1.jpg",
           "../assets/tiago2.jpg",
           "../assets/tiago3.jpg",
           "../assets/tiago4.jpg"
        ],
        brand: "Tata",
        class: "Car",
        model: "Family Car",
        color: "Sky Blue",
        year: 2021,
        mileage: 150,
        engine: "200cc",
        drive: "RWD",
        body: "Coupe",
        seats: 5,
        doors: 4,
        luggage: 242 ,
        description: "Tata Tiago – Compact & Efficient!Rent the Tata Tiago for a smooth, fuel-efficient drive. With a stylish design, spacious interiors, and great performance, it's perfect for city commutes and road trips.Book now for a hassle-free ride! 🚗✨"
    },
    {
        id: 3,
        name: "Mahendra Scorpio",
        category: "Family Car",
        price: 120000,
        priceDisplay: "$120,000",
        transmission: "Manual",
        fuel: "Diesel",
        passenger: "7 Persons",
        image: "../assets/scorpio2.jpg",
        images: [
            "../assets/scorpio1.jpg",
            "../assets/scorpio2.jpg",
            "../assets/scorpio3.jpg",
            "../assets/scorpio4.jpg",
        ],
        brand: "Mahendra",
        class: "Car",
        model: "Family Car",
        color: "Green",
        year: 2022,
        mileage: 100,
        engine: "5200cc",
        drive: "AWD",
        body: "Coupe",
        seats: 7,
        doors: 5,
        luggage: 460,
        description: "Mahindra Scorpio – Power Meets Comfort!Rent the rugged and powerful Mahindra Scorpio for an adventurous and comfortable ride. With its bold design, spacious cabin, and powerful engine, it's perfect for city drives, off-road adventures, and long trips. Enjoy top-notch safety, modern features, and a smooth driving experience.Book now and drive with confidence! 🚙🔥"
    },
    {
        id: 4,
        name: "Activa 5G",
        category: "Two wheeler",
        price: 90000,
        priceDisplay: "$90,000",
        transmission: "Automatic",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "../assets/activa5g-4.jpg",
        images: [
            "../assets/activa5g-1.jpg",
            "../assets/activa5g-2.jpg",
           "../assets/activa5g-3.jpg",
           "../assets/activa5g-4.jpg"
        ],
        brand: "Honda",
        class: "Two Wheeler",
        model: "CITY BIKE",
        color: "Blue",
        year: 2023,
        mileage: 40,
        engine: "109.19cc",
        drive: "AWD",
        body: "Sedan",
        seats: 2,
        luggage: 5.3,
        description: "Honda Activa 5G – Smooth & Reliable Ride!Rent the Honda Activa 5G for a hassle-free and fuel-efficient commute. With a stylish design, comfortable seating, and smooth performance, it’s perfect for city rides. Enjoy superior mileage, easy handling, and trusted reliability.Book now for a smooth journey! 🛵✨"
    },
    {
        id: 5,
        name: "Audi Q5",
        category: "Sports Car",
        price: 115000,
        priceDisplay: "$115,000",
        transmission: "Automatic",
        fuel: "Diesel",
        passenger: " 4 Persons",
        image: "../assets/AudiQ5-3.jpg",
        images: [
            "../assets/AudiQ5-1.jpg",
            "../assets/AudiQ5-2.jpg",
             "../assets/AudiQ5-3.jpg",
              "../assets/AudiQ5-4.jpg"
        ],
        brand: "Audi",
        class: "Car",
        model: "Sport",
        color: "White",
        year: 2022,
        mileage: 120,
        engine: "3800cc",
        drive: "RWD",
        body: "Coupe",
        seats: 5,
        doors: 4,
        luggage: 232,
        description: "Audi Q5 – Luxury & Performance Combined!Rent the Audi Q5 for a premium driving experience with powerful performance, elegant design, and advanced features. With a spacious, luxurious interior and top-tier safety, it’s perfect for business trips, vacations, or city drives.Book now and drive in style! 🚘🔥."
    },
    {
        id: 6,
        name: "Hero Splendor+",
        category: "Bike",
        price: 85000,
        priceDisplay: "$85,000",
        transmission: "Manual",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "../assets/splendor2.png",
        images: [
           "../assets/slpendor1.png",
            "../assets/splendor2.png",
            "../assets/splendor3.png",
            "../assets/splendor4.png"
        ],
        brand: "Hero",
        class: "Bike",
        model: "CITY BIKE",
        color: "Black",
        year: 2021,
        mileage: 50,
        engine: "97.2cc",
        drive: "4WD",
        body: "SUV",
        seats: 2,
        description: "Hero Splendor+ – Reliable & Fuel-Efficient Ride!Rent the Hero Splendor+ for a smooth, budget-friendly commute. Known for its excellent mileage, comfortable seating, and hassle-free performance, it's perfect for city rides and daily travel. Enjoy a dependable and economical journey every time!Book now for a smooth ride! 🏍️✨"
    },
    {
        id: 7,
        name: "Hundai Creta",
        category: "Sports Car",
        price: 250000,
        priceDisplay: "$250,000",
        transmission: "Automatic",
        fuel: "Diesel",
        passenger: "4 Persons",
        image: "../assets/creta-2.jpg",
        images: [
           "../assets/creta-1.jpg",
            "../assets/creta-2.jpg",
            "../assets/creta-3.jpg",
            "../assets/creta-4.jpg"
        ],
        brand: "Hundai",
        class: "Car",
        model: "Sport",
        color: "While",
        year: 2022,
        mileage: 30,
        engine: "200cc",
        drive: "AWD",
        body: "Coupe",
        seats: 4,
        doors: 4,
        luggage: 170,
        description: "Hyundai Creta – Style, Comfort & Power!Rent the Hyundai Creta for a premium and comfortable drive. With its bold design, spacious interiors, and advanced features, it's perfect for city commutes and long road trips. Enjoy powerful performance, top-notch safety, and a smooth driving experience.Book now and elevate your journey! 🚗🔥."
    },
    {
        id: 8,
        name: "Royal Enfield classic350",
        category: "Bike",
        price: 45000,
        priceDisplay: "$45,000",
        transmission: "Manual",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "../assets/Royalenfield-3.jpg",
        images: [
           "../assets/Royalenfield-1.jpg",
           "../assets/Royalenfield-2.jpg",
           "../assets/Royalenfield-3.jpg",
           "../assets/Royalenfield-4.jpg",
        ],
        brand: "Royal Enfield ",
        class: "Bike",
        model: "Offroad",
        color: "Black",
        year: 2021,
        mileage: 15,
        engine: "416cc",
        drive: "4WD",
        body: "SUV",
        seats: 2,
        description: "Royal Enfield Classic 350 – Ride with Power & Elegance!Rent the Royal Enfield Classic 350 for a thrilling and iconic riding experience. With its timeless design, powerful engine, and comfortable seating, it’s perfect for city cruising and long highway rides. Feel the road like never before with unmatched performance and style.Book now and ride like a legend! 🏍️🔥"
    }
];


// DOM Elements
const carGrid = document.getElementById('carGrid');
const searchInput = document.getElementById('searchInput');
const brandFilter = document.getElementById('brandFilter');
const classFilter = document.getElementById('classFilter');
const modelButtons = document.querySelectorAll('.model-btn');
const priceRange = document.getElementById('priceRange');
const priceValue = document.getElementById('priceValue');
const fuelFilter = document.getElementById('fuelFilter');
const colorFilter = document.getElementById('colorFilter');
const transmissionFilter = document.getElementById('transmissionFilter');
const applyFiltersBtn = document.getElementById('applyFilters');
const sortSelect = document.getElementById('sortSelect');
const resultsCount = document.getElementById('resultsCount');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Display all cars initially
    displayCars(cars);
    
    // Set up event listeners
    setupEventListeners();
});

// Create car card HTML
function createCarCard(car) {
    return `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="car-card" data-car-id="${car.id}">
                <div class="car-image">
                    <img src="${car.image}" alt="${car.name}">
                    <span class="price-tag">${car.priceDisplay}</span>
                </div>
                <div class="car-details">
                    <h3>${car.name}</h3>
                    <p class="car-category">${car.category}</p>
                    <div class="specs-grid">
                        <div class="spec">
                            <p class="spec-label">Transmission</p>
                            <p class="spec-value">${car.transmission}</p>
                        </div>
                        <div class="spec">
                            <p class="spec-label">Fuel</p>
                            <p class="spec-value">${car.fuel}</p>
                        </div>
                        <div class="spec">
                            <p class="spec-label">Passenger</p>
                            <p class="spec-value">${car.passenger}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Display cars in the grid
function displayCars(carsToDisplay) {
    carGrid.innerHTML = '';
    
    if (carsToDisplay.length === 0) {
        carGrid.innerHTML = '<div class="col-12 text-center py-5"><h3>No cars found matching your criteria</h3><p>Try adjusting your filters</p></div>';
        resultsCount.textContent = 'No cars found';
        return;
    }
    
    carsToDisplay.forEach(car => {
        carGrid.innerHTML += createCarCard(car);
    });
    
    // Add click event to each car card
    document.querySelectorAll('.car-card').forEach(card => {
        card.addEventListener('click', function() {
            const carId = this.getAttribute('data-car-id');
            viewCarDetails(carId);
        });
    });
    
    // Update results count
    resultsCount.textContent = `Showing ${carsToDisplay.length} cars from ${cars.length}`;
}

// Set up event listeners
function setupEventListeners() {
    // Price range slider
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', (e) => {
            const value = e.target.value;
            priceValue.textContent = `$${Number(value).toLocaleString()}`;
        });
    }
    
    // Model buttons
    modelButtons.forEach(button => {
        button.addEventListener('click', () => {
            modelButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });
    
    // Apply filters button
    applyFiltersBtn.addEventListener('click', applyFilters);
    
    // Sort select
    sortSelect.addEventListener('change', applyFilters);
    
    // Search input
    searchInput.addEventListener('input', applyFilters);
}

// Apply all filters
function applyFilters() {
    let filteredCars = [...cars];
    
    // Search filter
    const searchTerm = searchInput.value.toLowerCase();
    if (searchTerm) {
        filteredCars = filteredCars.filter(car => 
            car.name.toLowerCase().includes(searchTerm) || 
            car.category.toLowerCase().includes(searchTerm)
        );
    }
    
    // Brand filter
    const selectedBrand = brandFilter.value;
    if (selectedBrand) {
        filteredCars = filteredCars.filter(car => car.brand === selectedBrand);
    }
    
    // Class filter
    const selectedClass = classFilter.value;
    if (selectedClass) {
        filteredCars = filteredCars.filter(car => car.class === selectedClass);
    }
    
    // Model filter
    const activeModelBtn = document.querySelector('.model-btn.active');
    if (activeModelBtn) {
        const selectedModel = activeModelBtn.getAttribute('data-model');
        filteredCars = filteredCars.filter(car => car.model === selectedModel);
    }
    
    // Price filter
    const maxPrice = parseInt(priceRange.value);
    filteredCars = filteredCars.filter(car => car.price <= maxPrice);
    
    // Fuel filter
    const selectedFuel = fuelFilter.value;
    if (selectedFuel) {
        filteredCars = filteredCars.filter(car => car.fuel === selectedFuel);
    }
    
    // Color filter
    const selectedColor = colorFilter.value;
    if (selectedColor) {
        filteredCars = filteredCars.filter(car => car.color === selectedColor);
    }
    
    // Transmission filter
    const selectedTransmission = transmissionFilter.value;
    if (selectedTransmission) {
        filteredCars = filteredCars.filter(car => car.transmission === selectedTransmission);
    }
    
    // Sort cars
    const sortValue = sortSelect.value;
    if (sortValue === 'price-asc') {
        filteredCars.sort((a, b) => a.price - b.price);
    } else if (sortValue === 'price-desc') {
        filteredCars.sort((a, b) => b.price - a.price);
    } else if (sortValue === 'newest') {
        filteredCars.sort((a, b) => b.year - a.year);
    }
    
    // Display filtered cars
    displayCars(filteredCars);
}

// Function to store car details in localStorage and navigate to the details page
function viewCarDetails(carId) {
    const selectedCar = cars.find(car => car.id === parseInt(carId));
    if (selectedCar) {
        localStorage.setItem("selectedCar", JSON.stringify(selectedCar));
        window.location.href = "car-detail.php";
    }
}