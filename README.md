Tourist Agency 

A web-based application for a tourist agency. Users can browse destinations, make reservations, and manage their profiles. Admins can manage users, destinations, and view all reservations.

Features
User:
Register and login (passwords hashed)
Browse destinations (image, description, price)
Create and view reservations
Edit profile

Admin:
Login to admin panel
Manage users (add, edit, delete)
Manage destinations (add, edit, delete)
View all reservations

Tech Stack
Backend: PHP 8+
Database: MySQL
Web Server: Apache (XAMPP/WAMP)
Frontend: HTML5, CSS3, JavaScript
DB Admin: phpMyAdmin

Project Structure
File / Folder	Purpose
index.php	Home page with destinations
login_user.php, register.php	User login and registration
profile.php, my_reservations.php	User profile and reservations
admin_dashboard.php	Admin main panel
admin_destinations.php, add_tour.php, edit_greece.php	Destination management
config.php	Database connection settings
touragency.sql	SQL script for database
styles.css	CSS styling

How to Run
Install XAMPP/WAMP and start Apache + MySQL.
Import touragency.sql into MySQL.
Configure config.php with your database credentials.
Open the project in your browser via http://localhost/[project-folder].
Test user and admin functionality.
Security & Validation
Passwords hashed with password_hash()
Sessions used to separate user and admin roles
Form validation and error messages implemented
Notes
Sample users and destinations included for testing
Minimal CSS design included

Links
Repository:
