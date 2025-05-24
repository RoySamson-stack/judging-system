# judging-system
A simple judging system built with LAMP (Linux, Apache, MySQL, PHP) stack.

## Features
1. **Admin Panel**
   - Add new judges with unique usernames and display names
   - View all judges in the system

2. **Judge Portal**
   - View list of all participants
   - Assign scores to participants (1-100)
   - Update existing scores

3. **Public Scoreboard**
   - Displays all participants with their total scores
   - Sorted by highest score
   - Highlights top 3 participants
   - Auto-refreshes every 10 seconds

## Setup Instructions

1. **Prerequisites**
   - Apache web server
   - MySQL database
   - PHP 7.0+

2. **Database Setup**
   - Create a new MySQL database
   - Import the schema from `database.sql`

3. **Configuration**
   - Update database credentials in `includes/db_connect.php`
   - Add some sample data for users (participants)

4. **Access the Application**
   - Admin panel: `/admin`
   - Judge portal: `/judges`
   - Public scoreboard: `/`

## Database Schema

The system uses 3 main tables:
- `judges` - Stores judge information
- `users` - Stores participant information
- `scores` - Stores all scores assigned by judges to participants

## Design Choices

1. **Database**
   - Used foreign key constraints to maintain data integrity
   - Added UNIQUE constraint to prevent duplicate scoring by same judge
   - Used CHECK constraint to enforce score range (1-100)

2. **PHP**
   - Used PDO for database access with prepared statements
   - Implemented basic error handling
   - Separated concerns with includes for header/footer/db connection

3. **Frontend**
   - Simple Bootstrap-based interface
   - JavaScript for auto-refreshing scoreboard
   - Visual highlighting for top performers

## Future Enhancements

1. **Authentication**
   - Secure login for admin and judges
   - Role-based access control

2. **Features**
   - Add/edit participants
   - Multiple scoring criteria 
   - Export scores to CSV/Excel

3. **Improvements**
   - Better error handling
   - Input validation
   - Responsive design
   - AJAX for smoother score updates