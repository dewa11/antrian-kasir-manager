
Built by https://www.blackbox.ai

---

# Antrian Kasir

## Project Overview

Antrian Kasir is a PHP-based web application designed for managing patient queues in a medical setting. It provides functionalities to filter and display patient records based on the treatment date and search criteria. The application connects to a MySQL database to retrieve relevant patient information and allows you to announce patient names using text-to-speech.

## Installation

To set up Antrian Kasir on your local machine, follow these steps:

1. **Clone the Repository:**
   ```bash
   git clone https://your-repo-url.git
   ```

2. **Navigate to the Project Directory:**
   ```bash
   cd antrian-kasir
   ```

3. **Configure the Database:**
   - Open the `db_config.php` file.
   - Set your database credentials for `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME` to match your MySQL server settings.

4. **Create the Database:**
   - Ensure that your MySQL server is running.
   - Create the necessary tables in the database as per your application requirements (not covered in this README).

5. **Access the Application:**
   - Open your web browser.
   - Go to `http://localhost/path-to-your-project/antriankasir.php`.

## Usage

- The main page displays a form where you can filter patient data by specifying the start and end dates and optionally entering a patient's name.
- Click on a patient's name in the resulting table to announce their turn at the cashier's desk via text-to-speech functionality.

## Features

- **Date Filtering**: Easily filter patient records by selecting a date range.
- **Search Functionality**: Search for patients by name, medical record number, treatment type, or doctor.
- **User-Friendly Interface**: Styled with Bootstrap for a pleasant UI experience.
- **Voice Announcement**: Clickable patient names trigger announcements for the waiting patients at the cashier.

## Dependencies

This project does not include any dependencies in a package management file (like `package.json`). However, it uses Bootstrap for styling. Ensure that you have internet access to load Bootstrap CSS or download it for local use.

## Project Structure

```
antrian-kasir/
│
├── antriankasir.php      # Main application file that displays patient information
├── db_config.php         # Database configuration file containing connection details
└── assert/
    └── css/
        └── bootstrap.min.css  # Bootstrap CSS for styling the application
```

## License

This project is open-source and available for modification and redistribution.

## Acknowledgments

Special thanks to all contributors and the developers who made this project possible.