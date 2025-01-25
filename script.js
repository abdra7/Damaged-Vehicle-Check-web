document.addEventListener('DOMContentLoaded', () => {
    const pages = document.querySelectorAll('.page');
    const menuItems = document.querySelectorAll('#menu li');
    const menuButton = document.getElementById('menuButton');
    const menu = document.getElementById('menu');
    const appointmentButton = document.getElementById('goToUserManagement');
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
    const signUpModal = document.getElementById('signUpModal');
    const openSignUpModal = document.getElementById('openSignUpModal');
    const closeSignUpModal = document.getElementById('closeSignUpModal');
    const signUpForm = document.getElementById('signUpForm');
    const loginForm = document.getElementById('loginForm');
    const uploadForm = document.getElementById('uploadForm');
    const adminEntries = document.getElementById('adminEntries');
    const cancelUpload = document.getElementById('cancelUpload');
    const featureItems = document.querySelectorAll('.feature-item');
    const uploadImage = document.getElementById('uploadImage');
    const imagePreview = document.getElementById('imagePreview');

    let chosenTime = null;

    function showPage(pageId) {
        pages.forEach(page => {
            page.classList.add('hidden');
            page.classList.remove('active');
        });
        const targetPage = document.getElementById(pageId);
        if (targetPage) {
            targetPage.classList.remove('hidden');
            targetPage.classList.add('active');
        }
    }

    showPage('homePage');

    menuButton.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            const pageId = item.getAttribute('data-page');
            showPage(pageId);
            menu.classList.add('hidden');
        });
    });

    if (appointmentButton) {
        appointmentButton.addEventListener('click', () => {
            showAppointmentTimes();
            showPage('bookAppointmentPage');
        });
    }

    function showAppointmentTimes() {
        appointmentTimes.innerHTML = '';
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
    }

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

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (username && password) {
                alert('Login successful!');
                showPage('homePage');
            } else {
                alert('Please fill in all fields.');
            }
        });
    }

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

    if (cancelUpload) {
        cancelUpload.addEventListener('click', () => {
            if (uploadForm) uploadForm.reset();
            showPage('homePage');
        });
    }

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

    if (generateReportButton) {
        generateReportButton.addEventListener('click', () => {
            reportOutput.classList.remove('hidden');
            reportContent.textContent = 'Generated Report: All data is up-to-date.';
        });
    }

    if (settingsForm) {
        settingsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const selectedTheme = themeSelect.value;
            document.body.className = selectedTheme;
            alert(`Settings saved! Theme changed to ${selectedTheme}.`);
        });
    }

    if (addNotificationButton) {
        addNotificationButton.addEventListener('click', () => {
            const li = document.createElement('li');
            li.textContent = `Notification at ${new Date().toLocaleTimeString()}`;
            notificationList.appendChild(li);
        });
    }

    if (openSignUpModal) {
        openSignUpModal.addEventListener('click', () => {
            signUpModal.classList.remove('hidden');
        });
    }

    if (closeSignUpModal) {
        closeSignUpModal.addEventListener('click', () => {
            signUpModal.classList.add('hidden');
        });
    }

    window.addEventListener('click', (event) => {
        if (event.target === signUpModal) {
            signUpModal.classList.add('hidden');
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
                signUpModal.classList.add('hidden');
                signUpForm.reset();
            } else {
                alert('Please fill in all fields.');
            }
        });
    }

    featureItems.forEach(item => {
        item.addEventListener('click', () => {
            const targetPage = item.id.replace('goTo', '').toLowerCase() + 'Page';
            showPage(targetPage);
        });
    });
});

if (uploadImage) {
    uploadImage.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = `<p>No image selected</p>`;
        }
    });
}

const cancelUpload = document.getElementById('cancelUpload');
if (cancelUpload) {
    cancelUpload.addEventListener('click', () => {
        uploadImage.value = '';
        imagePreview.innerHTML = `<p>No image selected</p>`;
        document.getElementById('description').value = '';
    });
}