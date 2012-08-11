# README
---

### History
First, thank you for checking out Collect'em, a simple web-based movie library application. I decided to create it when my wife and I had family come visit and we had a hard time deciding on a movie to watch because our movies are stored in a closet (my wife hates having to look at them, it ruins the Pinterest home she's created for us) and only one person can be in there at a time. So I wanted something that would allow me to pull it up on a tablet or a phone or even our web devices that are connected to our TV. This application is the result of that need.

### Installation
**Note:** Collect'em supports both MySQL and SQLite (for those who don't want to devote resources to a full-fledged database). Steps 3 and 4 deal with the database setup, so you only have to follow the steps for the one you are planning on using.

1. Download the directory and place it where you want it in your web server's directory.
2. Grant your webserver write permissions to the assets/json directory and all files in it (for configuration)
3. SQLite
	1. Grant your webserver write permissions to the assets/db directory and all files in it (it needs directory access for temporary file creation/writing).
4. MySQL
    1. Download the SQL script.
    2. Create a database (or use an existing one) with a user who has SELECT, UPDATE, and DELETE access. I recommend granting the user access to Views, too, just in case that's used in a future version.
	3. Run the assets/sql/MySQL.sql script against that database (it should create a table called COLLECTION).
5. API(s)
	1. Obtain an API from The Movie Database (http://help.themoviedb.org/kb/general/how-do-i-register-for-an-api-key).
6. Configuration
	1. Browse to the application in a web brower (should display index.php).
	2. Either go to the settings page (the gear in the top-right of the screen) or click on "Search" or "View Library" (because if no configuration file is found, you are redirected to the configuration screen).
	3. Fill in all information, test the database connection, and save the settings.
7. You are ready to use Collect'em.

### Release History
