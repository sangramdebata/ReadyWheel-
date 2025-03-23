document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in (using localStorage for demo purposes)
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    
    // Toggle visibility of login button and profile icon based on login status
    const loginBtn = document.getElementById('login-btn');
    const profileContainer = document.getElementById('profile-container');
    
    if (isLoggedIn) {
        loginBtn.style.display = 'none';
        profileContainer.style.display = 'block';
    } else {
        loginBtn.style.display = 'block';
        profileContainer.style.display = 'none';
    }
    
    // Logout functionality
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Clear login status
            localStorage.setItem('isLoggedIn', 'false');
            // Redirect to home page
            window.location.href = 'index.html';
        });
    }
    
    // Car listing form submission
    const listCarForm = document.getElementById('list-car-form');
    if (listCarForm) {
        listCarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const carName = document.getElementById('carName').value;
            const carBrand = document.getElementById('carBrand').value;
            const carYear = document.getElementById('carYear').value;
            const carType = document.getElementById('carType').value;
            const fuelType = document.getElementById('fuelType').value;
            const carSpecs = document.getElementById('carSpecs').value;
            const seatingCapacity = document.getElementById('seatingCapacity').value;
            const transmission = document.getElementById('transmission').value;
            const carPrice = document.getElementById('carPrice').value;
            const carLocation = document.getElementById('carLocation').value;
            const availableNow = document.getElementById('availableNow').checked;
            
            // In a real application, you would send this data to a server
            // For demo purposes, we'll just show an alert
            alert(`Car listing submitted successfully!\n\nCar: ${carBrand} ${carName} (${carYear})\nPrice: ₹${carPrice} per day\nLocation: ${carLocation}`);
            
            // Reset form
            listCarForm.reset();
        });
    }
});