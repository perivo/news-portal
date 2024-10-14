
# News Portal Web Application

## Overview

This project is a web-based **News Portal** built with PHP, MySQL, HTML, CSS, and JavaScript, using the Bootstrap framework for responsive design. The application allows users to browse news articles, bookmark their favorite ones, and like articles. Users can also register and log in to manage their profiles and see personalized content, such as bookmarked articles.

Key features of the News Portal:
- User registration and login system
- Article creation with title, description, and content
- Bookmarking system for saving favorite articles
- Like feature to engage with articles
- Admin management to control content and user privileges
- Responsive design with Bootstrap

## Project Structure

```
C:\xampp\htdocs\news-portal
│
├── assets
│   ├── css
│   ├── js
├── includes
│   ├── db.php           # Database connection
│   ├── functions.php     # Reusable functions for the app
├── user
│   ├── login.php         # User login page
│   ├── register.php      # User registration page
│   ├── logout.php        # Logout script
│   ├── profile.php       # User profile page
├── index.php             # Homepage
├── mybookmarks.php       # Displays user's bookmarked articles
└── ...                   # Other PHP files and assets
```

## Prerequisites

Before you begin, make sure you have the following installed on your system:

- [XAMPP](https://www.apachefriends.org/index.html) (for Apache, MySQL, PHP)
- Web browser (Google Chrome, Mozilla Firefox, etc.)
- Git (to clone the repository)

## Setup Instructions

Follow these steps to set up the project on your local machine:

1. **Clone the repository**  
   Open a terminal and clone the repository into your `htdocs` folder (typically `C:\xampp\htdocs\` for Windows users) or any directory served by Apache:

   ```bash
   git clone https://github.com/your-github-username/news-portal.git
   ```

2. **Set up XAMPP**  
   Start Apache and MySQL from the XAMPP control panel.

3. **Create the MySQL database**  
   - Open your web browser and navigate to `http://localhost/phpmyadmin`.
   - Create a new database named `news_portal`.
   - Import the database schema by executing the SQL script in the `db.sql` file, which includes the table creation queries.

4. **Update database connection**  
   Go to the `includes/db.php` file and update the database connection settings:

   ```php
   <?php
   $host = 'localhost';
   $db = 'news_portal';
   $user = 'root';  // Default for XAMPP
   $pass = '';      // Default for XAMPP
   $charset = 'utf8mb4';

   $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
   try {
       $pdo = new PDO($dsn, $user, $pass);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die('Connection failed: ' . $e->getMessage());
   }
   ?>
   ```

5. **Run the project**  
   Once the database is set up, open your browser and navigate to `http://localhost/news-portal`. You should now be able to view and interact with the News Portal.

## Features

1. **User Authentication**
   - Users can register, log in, log out, and manage their profiles.

2. **Article Management**
   - Users can view articles, bookmark them for later, and like articles.

3. **Admin Panel**
   - Admins can manage user accounts, articles, and other platform content.

4. **Responsive Design**
   - The UI is designed to work seamlessly on all device sizes, using Bootstrap for styling.

## Future Enhancements

- Commenting system for user interaction on articles.
- Article categories or tags for better content organization.
- Admin dashboard for better management.

## Contributing

If you would like to contribute to this project, feel free to fork the repository and submit a pull request with your improvements. We welcome all kinds of contributions!

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).

## Contact

For any inquiries or feedback, feel free to reach out:

- **Developer**: [Ivo Pereira](https://github.com/perivo)
- **GitHub**: [https://github.com/perivo](https://github.com/perivo)

