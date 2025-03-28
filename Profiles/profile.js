document.addEventListener("DOMContentLoaded", () => {
  // Check if user is logged in (using localStorage for demo purposes)
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true"

  // Toggle visibility of login button and profile icon based on login status
  const loginBtn = document.getElementById("login-btn")
  const profileContainer = document.getElementById("profile-container")

  if (isLoggedIn) {
    loginBtn.style.display = "none"
    profileContainer.style.display = "block"

    // Load user data (in a real app, this would come from a server)
    loadUserData()
  } else {
    loginBtn.style.display = "block"
    profileContainer.style.display = "none"
  }

  // Logout functionality
  const logoutBtn = document.getElementById("logout-btn")
  if (logoutBtn) {
    logoutBtn.addEventListener("click", (e) => {
      e.preventDefault()
      // Clear login status
      localStorage.setItem("isLoggedIn", "false")
      // Redirect to home page
      window.location.href = "/ReadyWheel-/index.html"
    })
  }

  // Profile picture upload preview
  const profilePicUpload = document.getElementById("profilePicUpload")
  if (profilePicUpload) {
    profilePicUpload.addEventListener("change", (e) => {
      if (e.target.files.length > 0) {
        const file = e.target.files[0]
        const reader = new FileReader()

        reader.onload = (e) => {
          const profilePicPreview = document.getElementById("profilePicPreview")
          profilePicPreview.src = e.target.result
        }

        reader.readAsDataURL(file)
      }
    })
  }

  // Edit Profile button functionality
  const editProfileBtn = document.querySelector(".btn-primary")
  if (editProfileBtn) {
    editProfileBtn.addEventListener("click", () => {
      // Open the edit profile modal
      const editProfileModal = new bootstrap.Modal(document.getElementById("editProfileModal"))

      // Populate the form with current user data
      const userData = getUserData()
      document.getElementById("edit-full-name").value = userData.name
      document.getElementById("edit-email").value = userData.email
      document.getElementById("edit-phone").value = userData.phone
      document.getElementById("edit-address").value = userData.address
      document.getElementById("edit-aadhar").value = userData.aadhar
      document.getElementById("edit-license").value = userData.license

      // Set profile picture preview
      if (userData.profilePic) {
        document.getElementById("profilePicPreview").src = userData.profilePic
      }

      editProfileModal.show()
    })
  }

  // Handle profile form submission
  const profileForm = document.getElementById("edit-profile-form")
  if (profileForm) {
    profileForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // Get profile picture
      const profilePicPreview = document.getElementById("profilePicPreview")
      const profilePic = profilePicPreview.src

      // Get form data
      const updatedUserData = {
        name: document.getElementById("edit-full-name").value,
        email: document.getElementById("edit-email").value,
        phone: document.getElementById("edit-phone").value,
        address: document.getElementById("edit-address").value,
        aadhar: document.getElementById("edit-aadhar").value,
        license: document.getElementById("edit-license").value,
        profilePic: profilePic,
        // Keep other data the same
        memberSince: getUserData().memberSince,
        transactions: getUserData().transactions,
      }

      // Save updated user data
      saveUserData(updatedUserData)

      // Update the UI
      updateProfileUI(updatedUserData)

      // Close the modal
      const editProfileModalEl = document.getElementById("editProfileModal")
      const editProfileModal = bootstrap.Modal.getInstance(editProfileModalEl)
      editProfileModal.hide()

      // Show success message
      showAlert("Profile updated successfully!", "success")
    })
  }

  // Function to load user data
  function loadUserData() {
    // Check if user data exists in localStorage
    const storedUserData = localStorage.getItem("userData")

    if (storedUserData) {
      // Use stored user data if available
      const userData = JSON.parse(storedUserData)
      updateProfileUI(userData)
    } else {
      // In a real application, this data would come from a server/database
      // For demo purposes, we're using hardcoded data
      const userData = {
        name: "Chaitanya Behera",
        email: "chaitanya@gmail.com",
        phone: "(+91) 9998887775",
        address: "123 Main Street, Angul, Odisha, India",
        memberSince: "March 2025",
        aadhar: "XXXX XXXX 1234",
        license: "DL-1234567890",
        profilePic: "../assets/profile-placeholder.jpg",
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
          },
        ],
      }

      // Save the initial user data
      saveUserData(userData)

      // Update the UI
      updateProfileUI(userData)
    }
  }

  // Function to get current user data
  function getUserData() {
    const storedUserData = localStorage.getItem("userData")
    return storedUserData ? JSON.parse(storedUserData) : null
  }

  // Function to save user data
  function saveUserData(userData) {
    localStorage.setItem("userData", JSON.stringify(userData))
  }

  // Function to update profile UI
  function updateProfileUI(userData) {
    // Update text information
    document.getElementById("user-name").textContent = userData.name
    document.getElementById("full-name").textContent = userData.name
    document.getElementById("email").textContent = userData.email
    document.getElementById("phone").textContent = userData.phone
    document.getElementById("address").textContent = userData.address
    document.getElementById("member-since").textContent = userData.memberSince
    document.getElementById("aadhar").textContent = userData.aadhar
    document.getElementById("license").textContent = userData.license

    // Update profile pictures
    if (userData.profilePic) {
      // Update main profile picture
      const profilePicElements = document.querySelectorAll(".profile-icon, .rounded-circle")
      profilePicElements.forEach((element) => {
        element.src = userData.profilePic
      })
    }
  }

  // Function to show alert messages
  function showAlert(message, type = "info") {
    const alertContainer = document.getElementById("alert-container")

    if (!alertContainer) {
      // Create alert container if it doesn't exist
      const container = document.createElement("div")
      container.id = "alert-container"
      container.style.position = "fixed"
      container.style.top = "20px"
      container.style.right = "20px"
      container.style.zIndex = "9999"
      document.body.appendChild(container)
    }

    // Create alert element
    const alert = document.createElement("div")
    alert.className = `alert alert-${type} alert-dismissible fade show`
    alert.role = "alert"
    alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `

    // Add alert to container
    document.getElementById("alert-container").appendChild(alert)

    // Auto-dismiss after 3 seconds
    setTimeout(() => {
      alert.classList.remove("show")
      setTimeout(() => {
        alert.remove()
      }, 150)
    }, 3000)
  }
})

