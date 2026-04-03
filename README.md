# Appliance Inventory

A web-based house appliance inventory system built with PHP and MySQL, 
allowing users to register and manage their home appliances.

## Features
- Register household appliances with full details
- Validates Cork Eircodes only
- Tracks warranty expiration dates
- Session-based inventory management
- Persistent inventory across multiple registrations

## Tech Stack
- PHP
- MySQL
- JS
- Bootstrap 5
- SCSS
- HTML5/CSS3

## Setup
1. Clone the repository
   git clone https://github.com/ayjay-9/appliance_inventory.git

2. Copy the example config file
   cp config.example.php config.php

3. Fill in your database credentials in config.php
   $host = 'localhost';
   $dbname = 'your_database_name';
   $username = 'your_username';
   $password = 'your_password';

4. Import the database schema
   mysql -u your_username -p your_database < schema.sql

5. Run on a local server (e.g. XAMPP, WAMP)

## Validation Rules
- Eircode: Valid Cork Eircode only (e.g. T12 BC34)
- Model Number: 2 letters followed by 4 digits (e.g. AB1234)
- Serial Number: SN followed by 8 digits (e.g. SN12345678)
- Brand: 2 letters followed by 4 digits (e.g AB1234)
- Warranty date cannot be before purchase date

## License
This project is for educational purposes only.
All rights reserved © Emmanuel Ayobanjo 2025
