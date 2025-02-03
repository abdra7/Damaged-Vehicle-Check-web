# Damage Vehicle Check



## Overview

**Damage Vehicle Check** is a web-based application designed to help users upload images of damaged vehicles and receive detailed reports. The platform supports multiple user roles, including regular users, experts, and workshop managers, each with specific functionalities tailored to their needs. Whether you're a vehicle owner looking for a damage assessment or a workshop manager managing repair bookings, this application provides a seamless experience.

---

## Functionalities

### 1. **User Authentication**
- **Purpose**: Securely manage user access to the platform.
- **Features**:
  - Users can register, log in, and log out.
  - Role-based access control (e.g., regular users, experts, workshop managers).
  - Passwords are securely hashed using PHP's `password_hash()` function.
- **How It Works**:
  - Upon login, users are redirected to role-specific dashboards (`user_dashboard.php`, `workshop_dashboard.php`, etc.).
  - Sessions are used to store user data (`$_SESSION`) for secure navigation.

---

### 2. **Image Upload and Damage Reporting**
- **Purpose**: Allow users to upload images of damaged vehicles and generate detailed reports.
- **Features**:
  - Users can upload images via a form.
  - Uploaded images are stored in the `uploads/` directory, and their paths are saved in the database.
  - Experts review uploaded images and add repair notes, estimated costs, and severity levels.
- **How It Works**:
  - The `upload.php` page handles image uploads and stores metadata in the `user_activities` table.
  - Reports are displayed on the user's dashboard with details such as status, repair notes, and estimated costs.

---

### 3. **Role-Based Dashboards**
- **Purpose**: Provide tailored dashboards for different user roles to ensure efficient workflows.
- **Features**:
  - **Regular Users**:
    - View uploaded reports and their statuses.
  - **Experts**:
    - Review uploaded reports, add repair notes, and estimate costs.
    - Update the status of reports (e.g., resolved, pending, rejected).
  - **Workshop Managers**:
    - Manage bookings submitted by users.
    - Assign experts to specific bookings and track repair progress.
- **How It Works**:
  - Each role has its own dashboard (`user_dashboard.php`, `workshop_dashboard.php`) with role-specific functionalities.
  - Data is fetched dynamically from the database based on the user's role.

---

### 4. **Booking System**
- **Purpose**: Allow users to book repair services for their vehicles.
- **Features**:
  - Users can submit booking requests with details such as vehicle type, preferred date, and service type.
  - Workshop managers can accept or reject bookings and assign experts.
- **How It Works**:
  - Bookings are stored in the `user_activities` table with a `type` of `'booking'`.
  - Workshop managers manage bookings via the `manage_bookings.php` page.

---

### 5. **Repair Notes and Estimated Costs**
- **Purpose**: Provide detailed feedback on uploaded reports and bookings.
- **Features**:
  - Experts can add repair notes and estimated costs for accepted reports.
  - These details are stored in the `repair_notes` and `estimated_cost` columns of the `user_activities` table.
- **How It Works**:
  - Experts update reports via the `add_repair_notes.php` page.
  - Users view these details on their dashboard.

---

### 6. **Responsive Design and Accessibility**
- **Purpose**: Ensure the application is accessible and usable on all devices.
- **Features**:
  - A mobile-first approach with media queries to adapt to different screen sizes.
  - High contrast colors for readability.
  - Semantic HTML elements for screen readers.
- **How It Works**:
  - The CSS file (`style.css`) includes responsive design rules and accessibility features.
  - Animations and hover effects enhance user experience without compromising performance.

---

### 7. **Animations and Interactive Elements**
- **Purpose**: Enhance user experience with subtle animations and interactive elements.
- **Features**:
  - Buttons and cards have hover and focus states with smooth transitions.
  - Pages and elements fade in smoothly when loaded.
- **How It Works**:
  - Animations are implemented using CSS keyframes and transitions.
  - Interactive elements like buttons and cards respond to user actions.

---

## Types of Files

### 1. **PHP Files**
- Handle backend logic, database interactions, and dynamic content generation.
- Examples:
  - `login.php`: Handles user authentication.
  - `upload.php`: Manages image uploads.
  - `manage_bookings.php`: Allows workshop managers to manage bookings.

### 2. **CSS File**
- Contains styles for the entire application, ensuring a consistent and professional look.
- Example:
  - `style.css`: Includes responsive design, animations, and utility classes.

### 3. **SQL Schema**
- Defines the database structure and relationships.
- Example:
  - `schema.sql`: Includes tables like `users` and `user_activities`.

### 4. **HTML Templates**
- Reusable components for headers, footers, and other common elements.
- Examples:
  - `header.php`: Contains the navigation bar.
  - `footer.php`: Displays the footer content.

### 5. **Uploaded Files**
- Stores user-uploaded images and assets.
- Example:
  - `uploads/`: Directory for storing uploaded images.

---

## How the System Works Together

1. **User Interaction**:
   - Regular users upload images or submit booking requests via forms.
   - Experts and workshop managers manage these submissions through their respective dashboards.

2. **Backend Logic**:
   - PHP scripts handle form submissions, database queries, and session management.
   - Data is stored in a MySQL database, with tables like `users` and `user_activities`.

3. **Frontend Design**:
   - The CSS file ensures a responsive and visually appealing interface.
   - Animations and interactive elements enhance the user experience.

4. **Database Integration**:
   - The application fetches and updates data dynamically from the database.
   - Role-based access control ensures that users only see relevant information.



Thank you for exploring **Damage Vehicle Check**! We hope this tool helps streamline the process of assessing and repairing damaged vehicles.

