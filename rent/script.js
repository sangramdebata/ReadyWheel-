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
        name: "BMW M2 2020",
        category: "Sports Car",
        price: 34500,
        priceDisplay: "$34,500",
        transmission: "Automatic",
        fuel: "Hybrid",
        passenger: "2 Persons",
        image: "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Car%20Booking%20Confirm.jpg-Eg42szEro6sHGecCYLtL5sdzS8SlQn.jpeg",
        images: [
            "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Car%20Booking%20Confirm.jpg-Eg42szEro6sHGecCYLtL5sdzS8SlQn.jpeg",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "BMW",
        class: "Sports",
        model: "Sport",
        color: "Black",
        year: 2020,
        mileage: 200,
        engine: "3000cc",
        drive: "4WD",
        body: "Coupe",
        seats: 2,
        doors: 2,
        luggage: 150,
        description: "The BMW M2 Is The High-Performance Version Of The 2 Series 2-Door Coupé. The First Generation Of The M2 Is The F87 Coupé And Is Powered By Turbocharged."
    },
    {
        id: 2,
        name: "Mercedes AMG GT",
        category: "Luxury Car",
        price: 45000,
        priceDisplay: "$45,000",
        transmission: "Automatic",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Mercedes",
        class: "Luxury",
        model: "Sport",
        color: "Silver",
        year: 2021,
        mileage: 150,
        engine: "4000cc",
        drive: "RWD",
        body: "Coupe",
        seats: 2,
        doors: 2,
        luggage: 175,
        description: "The Mercedes-AMG GT is a grand tourer produced by German automobile manufacturer Mercedes-AMG. The car is powered by a front-mid-engine, rear-wheel-drive layout, and is the first sports car developed entirely in-house by Mercedes-AMG."
    },
    {
        id: 3,
        name: "Audi R8",
        category: "Sports Car",
        price: 120000,
        priceDisplay: "$120,000",
        transmission: "Automatic",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Audi",
        class: "Sports",
        model: "Sport",
        color: "Red",
        year: 2022,
        mileage: 100,
        engine: "5200cc",
        drive: "AWD",
        body: "Coupe",
        seats: 2,
        doors: 2,
        luggage: 112,
        description: "The Audi R8 is a mid-engine, 2-seater sports car, which uses Audi's trademark quattro permanent all-wheel drive system. It was introduced by the German car manufacturer Audi AG in 2006."
    },
    {
        id: 4,
        name: "Tesla Model S",
        category: "Electric Car",
        price: 90000,
        priceDisplay: "$90,000",
        transmission: "Automatic",
        fuel: "Electric",
        passenger: "5 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Tesla",
        class: "Electric",
        model: "City Car",
        color: "White",
        year: 2023,
        mileage: 50,
        engine: "Electric",
        drive: "AWD",
        body: "Sedan",
        seats: 5,
        doors: 4,
        luggage: 200,
        description: "The Tesla Model S is an all-electric five-door liftback sedan produced by Tesla, Inc. The Model S features a dual-motor, all-wheel drive layout, and has access to Tesla's Supercharger charging stations."
    },
    {
        id: 5,
        name: "Porsche 911",
        category: "Sports Car",
        price: 115000,
        priceDisplay: "$115,000",
        transmission: "Manual",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Porsche",
        class: "Sports",
        model: "Sport",
        color: "Yellow",
        year: 2022,
        mileage: 120,
        engine: "3800cc",
        drive: "RWD",
        body: "Coupe",
        seats: 2,
        doors: 2,
        luggage: 132,
        description: "The Porsche 911 is a two-door, 2+2 high performance rear-engined sports car. It has a rear-mounted flat-six engine and all round independent suspension. It has undergone continuous development, though the basic concept has remained unchanged."
    },
    {
        id: 6,
        name: "Range Rover Sport",
        category: "SUV",
        price: 85000,
        priceDisplay: "$85,000",
        transmission: "Automatic",
        fuel: "Diesel",
        passenger: "5 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Land Rover",
        class: "SUV",
        model: "SUV",
        color: "Black",
        year: 2021,
        mileage: 180,
        engine: "3000cc",
        drive: "4WD",
        body: "SUV",
        seats: 5,
        doors: 5,
        luggage: 250,
        description: "The Range Rover Sport is a luxury mid-size SUV produced by Land Rover. The Range Rover Sport combines the luxury and capability of the Range Rover with a more sporty and dynamic character."
    },
    {
        id: 7,
        name: "Lamborghini Huracan",
        category: "Sports Car",
        price: 250000,
        priceDisplay: "$250,000",
        transmission: "Automatic",
        fuel: "Petrol",
        passenger: "2 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Lamborghini",
        class: "Sports",
        model: "Sport",
        color: "Green",
        year: 2022,
        mileage: 80,
        engine: "5200cc",
        drive: "AWD",
        body: "Coupe",
        seats: 2,
        doors: 2,
        luggage: 100,
        description: "The Lamborghini Huracán is a sports car built by Italian automotive manufacturer Lamborghini. The Huracán is Lamborghini's best-selling model to date, with more than 14,000 cars delivered worldwide."
    },
    {
        id: 8,
        name: "Jeep Wrangler",
        category: "SUV",
        price: 45000,
        priceDisplay: "$45,000",
        transmission: "Manual",
        fuel: "Petrol",
        passenger: "4 Persons",
        image: "/placeholder.svg?height=400&width=600",
        images: [
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600",
            "/placeholder.svg?height=400&width=600"
        ],
        brand: "Jeep",
        class: "SUV",
        model: "Offroad",
        color: "Blue",
        year: 2021,
        mileage: 150,
        engine: "3600cc",
        drive: "4WD",
        body: "SUV",
        seats: 4,
        doors: 2,
        luggage: 200,
        description: "The Jeep Wrangler is a series of compact and mid-size four-wheel drive off-road SUVs manufactured by Jeep. The Wrangler is known for its iconic design, round headlamps, solid axles, and removable doors and roof."
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
        window.location.href = "car-detail.html";
    }
}