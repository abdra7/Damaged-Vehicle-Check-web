/**
 * Damage Vehicle Check System - Main JS
 * Features: 
 * - Form validation & UX enhancements
 * - Image preview functionality
 * - Dynamic AJAX interactions
 * - UI/UX animations
 * - Error handling
 */

document.addEventListener('DOMContentLoaded', () => {
    // Global configuration
    const config = {
        imagePreview: {
            maxSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['image/jpeg', 'image/png', 'image/gif']
        },
        apiEndpoints: {
            submitForm: '/upload.php',
            updateStatus: '/user_dashboard.php'
        }
    };

    // ------------------
    // Module 1: Form Handling
    // ------------------
    const initFormHandlers = () => {
        // Image Upload Preview
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', handleImagePreview);
        }

        // Enhanced Form Validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });

        // Real-time Input Validation
        document.querySelectorAll('input[required], textarea[required]').forEach(input => {
            input.addEventListener('input', validateRealTime);
        });
    };

    // ------------------
    // Module 2: Image Handling
    // ------------------
    const handleImagePreview = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file
        if (!config.imagePreview.allowedTypes.includes(file.type)) {
            showToast('Invalid file type. Allowed: JPG, PNG, GIF', 'error');
            event.target.value = '';
            return;
        }

        if (file.size > config.imagePreview.maxSize) {
            showToast('File size exceeds 5MB limit', 'error');
            event.target.value = '';
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('imagePreview') || createImagePreviewElement();
            preview.src = e.target.result;
            preview.classList.add('active');
        };
        reader.readAsDataURL(file);
    };

    const createImagePreviewElement = () => {
        const container = document.querySelector('.upload-section');
        const preview = document.createElement('img');
        preview.id = 'imagePreview';
        preview.className = 'image-preview';
        container.insertBefore(preview, imageInput.nextElementSibling);
        return preview;
    };

    // ------------------
    // Module 3: Form Validation
    // ------------------
    const validateRealTime = (event) => {
        const input = event.target;
        if (input.checkValidity()) {
            input.classList.remove('invalid');
            input.classList.add('valid');
        } else {
            input.classList.remove('valid');
            input.classList.add('invalid');
        }
    };

    const handleFormSubmit = (event) => {
        const form = event.target;
        let isValid = true;

        // Custom validation logic
        if (form.id === 'combinedForm') {
            const hasImage = document.getElementById('image').files.length > 0;
            const hasBooking = document.getElementById('date').value && 
                             document.querySelector('input[name="service_type"]:checked');

            if (!hasImage && !hasBooking) {
                event.preventDefault();
                showToast('Please upload an image or make a booking', 'warning');
                isValid = false;
            }

            if (hasImage && !document.getElementById('description').value.trim()) {
                event.preventDefault();
                showToast('Image description is required', 'warning');
                isValid = false;
            }
        }

        if (isValid) {
            showLoadingIndicator(form);
            // Optional: Add AJAX submission here
        }
    };

    // ------------------
    // Module 4: UI Utilities
    // ------------------
    const showToast = (message, type = 'info') => {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    };

    const showLoadingIndicator = (form) => {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <div class="spinner"></div>
            Processing...
        `;
        submitBtn.disabled = true;

        // Reset after form processing
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    };

    // ------------------
    // Module 5: Dashboard Interactions
    // ------------------
    const initDashboard = () => {
        // Real-time status updates
        document.querySelectorAll('.status-indicator').forEach(indicator => {
            indicator.addEventListener('click', handleStatusClick);
        });

        // Enhanced image zoom
        document.querySelectorAll('.expanded-image').forEach(img => {
            img.addEventListener('click', createLightbox);
        });
    };

    const createLightbox = (event) => {
        const imgSrc = event.target.dataset.fullsize;
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.innerHTML = `
            <div class="lightbox-content">
                <img src="${imgSrc}" alt="Full size damage photo">
                <button class="close-btn">&times;</button>
            </div>
        `;
        document.body.appendChild(lightbox);

        lightbox.querySelector('.close-btn').addEventListener('click', () => {
            lightbox.remove();
        });
    };

    // ------------------
    // Initialization
    // ------------------
    initFormHandlers();
    if (document.querySelector('.expert-dashboard')) initDashboard();

    // Accessibility enhancements
    document.addEventListener('keyup', (event) => {
        if (event.key === 'Escape') {
            document.querySelector('.lightbox')?.remove();
        }
    });
});

  const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
});
