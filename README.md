# Course-Management-System---Symfony-PHP-Application

## Overview
This project is a web application for managing courses (formations), built with Symfony and Twig. The system allows administrators to manage courses through a dashboard and enables students to rate courses. Stripe payment integration is used for paid courses, and additional features include search, filters, PDF generation, and statistics.

## Key Features:
- **Admin Dashboard**: Manage courses, student registrations, and course details.
- **Course Ratings**: Students can rate courses and provide feedback.
- **Stripe Integration**: Secure payments for courses using Stripe.
- **Search & Filters**: Easily find courses using advanced search and filtering.
- **PDF Generation**: Generate PDF reports for course ratings and details.
- **Statistics**: Visualize data on course performance and student engagement.

## Technologies Used:
- **Symfony (Twig)**: Backend framework and templating engine.
- **PHPMyAdmin & SQL**: Database management.
- **Stripe API**: For payment integration.
- **PDF Libraries**: To generate course reports.
- **Statistics Tools**: To display course and student data.

## Installation Instructions:
1. Clone the repository:
   
   git clone https://github.com/your-username/course-management-system.git


2. Install Symfony and all required dependencies:


composer install

3. Set up your PHPMyAdmin and SQL database and configure the connection in the .env file.
4. Set up Stripe API credentials.
5. Run the Symfony server:
symfony server:start
Contributing:
Contributions are welcome! Feel free to submit pull requests or open issues for enhancements.