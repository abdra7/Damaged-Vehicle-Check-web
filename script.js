document.addEventListener('DOMContentLoaded', () => {
    const pages = document.querySelectorAll('.page'); // All pages
    const menuItems = document.querySelectorAll('#menu li'); // Menu items
    const menuButton = document.getElementById('menuButton'); // Menu toggle button
    const menu = document.getElementById('menu'); // Menu container
    const appointmentButton = document.getElementById('goToUserManagement'); // "Book Appointment" button
    const backToHomeButton = document.getElementById('backToHome');
    const appointmentTimes = document.getElementById('appointmentTimes');
    const submitButton = document.getElementById('submitAppointment');
    const confirmationMessage = document.getElementById('confirmationMessage');
    const appointmentDate = document.getElementById('appointmentDate');
    const userForm = document.getElementById('userForm');
    const userList = document.getElementById('userList');
    const generateReportButton = document.getElementById('generateReport');
    const reportOutput = document.getElementById('reportOutput');
    const reportContent = document.getElementById('reportContent');
    const settingsForm = document.getElementById('settingsForm');
    const themeSelect = document.getElementById('theme');
    const addNotificationButton = document.getElementById('addNotification');
    const notificationList = document.getElementById('notificationList');

    // Sign-Up Modal Elements
    const signUpModal = document.getElementById('signUpModal');
    const openSignUpModal = document.getElementById('openSignUpModal');
    const closeSignUpModal = document.getElementById('closeSignUpModal');
    const signUpForm = document.getElementById('signUpForm');

    let chosenTime = null; // Variable to hold the selected appointment time

    /**
     * Helper function to show a specific page and hide all other pages.
     * @param {string} pageId - The ID of the page to show.
     */
    function showPage(pageId) {
        pages.forEach(page => {
            page.classList.add('hidden');
        });
        const targetPage = document.getElementById(pageId);
        if (targetPage) {
            targetPage.classList.remove('hidden');
        }
    }

    // Show the login page by default
    showPage('homePage');

    // Toggle Menu
    menuButton.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    menuButton.addEventListener('click', () => {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            const pageId = item.getAttribute('data-page');
            pages.forEach(page => {
                page.classList.remove('active');
                if (page.id === pageId) {
                    page.classList.add('active');
                }
            });
            menu.style.display = 'none';
        });
    });
    // Show available appointment times when clicking on "Book Appointment"
    if (appointmentButton) {
        appointmentButton.addEventListener('click', () => {
            showAppointmentTimes();
        });
    }

    function showAppointmentTimes() {
        appointmentTimes.innerHTML = ''; // Clear previous times
        chosenTime = null;
        confirmationMessage.classList.add('hidden');

        for (let hour = 8; hour <= 17; hour++) {
            const period = hour < 12 ? 'AM' : 'PM';
            const displayHour = hour > 12 ? hour - 12 : hour;
            const time = `${displayHour}:00 ${period}`;
            const listItem = document.createElement('li');
            listItem.textContent = time;
            listItem.className = 'appointment-time';

            listItem.addEventListener('click', () => {
                if (!chosenTime) {
                    chosenTime = time;
                    listItem.classList.add('selected');
                    submitButton.disabled = false;

                    Array.from(appointmentTimes.children).forEach(item => {
                        if (item !== listItem) {
                            item.style.pointerEvents = 'none';
                        }
                    });
                }
            });

            appointmentTimes.appendChild(listItem);
        }

        showPage('bookAppointmentPage');
    }

    // Back to Home Page
    if (backToHomeButton) {
        backToHomeButton.addEventListener('click', () => {
            showPage('homePage');
            resetAppointment();
        });
    }

    function resetAppointment() {
        appointmentDate.value = '';
        appointmentTimes.innerHTML = '';
        submitButton.disabled = true;
        chosenTime = null;
        confirmationMessage.classList.add('hidden');
    }

    if (submitButton) {
        submitButton.addEventListener('click', () => {
            if (chosenTime && appointmentDate.value) {
                confirmationMessage.textContent = `Appointment booked for ${chosenTime} on ${appointmentDate.value}`;
                confirmationMessage.classList.remove('hidden');
                showPage('homePage');
                resetAppointment();
            }
        });
    }

    // Login Form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Login successful!');
            showPage('homePage');
        });
    }

    // Upload Form
    const uploadForm = document.getElementById('uploadForm');
    const adminEntries = document.getElementById('adminEntries');
    if (uploadForm) {
        uploadForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const uploadImage = document.getElementById('uploadImage').files[0];
            const description = document.getElementById('description').value;

            if (uploadImage && description) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${adminEntries.children.length + 1}</td>
                    <td>${uploadImage.name}</td>
                    <td>${description}</td>
                    <td>${new Date().toLocaleDateString()}</td>
                `;
                adminEntries.appendChild(row);
                uploadForm.reset();
                alert('Image uploaded successfully!');
            } else {
                alert('Please fill all fields.');
            }
        });
    }

    // Cancel Upload
    const cancelUpload = document.getElementById('cancelUpload');
    if (cancelUpload) {
        cancelUpload.addEventListener('click', () => {
            if (uploadForm) uploadForm.reset();
            showPage('homePage');
        });
    }

    // User Management Functionality
    if (userForm) {
        userForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const userName = document.getElementById('userName').value;
            const userEmail = document.getElementById('userEmail').value;
            const li = document.createElement('li');
            li.textContent = `Name: ${userName}, Email: ${userEmail}`;
            userList.appendChild(li);
            userForm.reset();
        });
    }

    // Reporting Tools
    if (generateReportButton) {
        generateReportButton.addEventListener('click', () => {
            reportOutput.classList.remove('hidden');
            reportContent.textContent = 'Generated Report: All data is up-to-date.';
        });
    }

    // Settings Functionality
    if (settingsForm) {
        settingsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const selectedTheme = themeSelect.value;
            document.body.className = selectedTheme;
            alert(`Settings saved! Theme changed to ${selectedTheme}.`);
        });
    }

    // Notifications
    if (addNotificationButton) {
        addNotificationButton.addEventListener('click', () => {
            const li = document.createElement('li');
            li.textContent = `Notification at ${new Date().toLocaleTimeString()}`;
            notificationList.appendChild(li);
        });
    }

    // Sign-Up Modal
    if (openSignUpModal) {
        openSignUpModal.addEventListener('click', () => {
            signUpModal.style.display = 'block';
        });
    }

    if (closeSignUpModal) {
        closeSignUpModal.addEventListener('click', () => {
            signUpModal.style.display = 'none';
        });
    }

    window.addEventListener('click', (event) => {
        if (event.target === signUpModal) {
            signUpModal.style.display = 'none';
        }
    });

    if (signUpForm) {
        signUpForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('signUpUsername').value;
            const email = document.getElementById('signUpEmail').value;
            const password = document.getElementById('signUpPassword').value;

            if (username && email && password) {
                alert(`Account created for ${username}`);
                signUpModal.style.display = 'none';
                signUpForm.reset();
            } else {
                alert('Please fill in all fields.');
            }
        });
    }
});