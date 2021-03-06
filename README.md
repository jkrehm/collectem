# README
---

### History
First, thank you for checking out Collect'em, a simple web-based movie library application. I decided to create it when my wife and I had family come visit and we had a hard time deciding on a movie to watch because our movies are stored in a closet (they've been deemed aesthetically displeasing) and only one person can fit in there at a time. So I wanted something that would allow me to display our library on a tablet, phone or whatever device happened to be nearby. This application is the result of that need.

### Tour
View a [screenshot tour](http://collectem.rehm.me/) of the application.

### Prerequisites
1. A web server (either on a dedicated server or running on a personal computer via Apache, IIS, etc.)
2. Knowledge of granting the web server write permissions to a directory and its files (see instructions below).
3. A movie library that you would like to browse from a web enabled device.

### Installation
**Note:** Collect'em supports both MySQL and SQLite (for those who don't want to devote resources to a full-fledged database). Steps 3 and 4 deal with the database setup, so you only have to follow the steps for the one you are planning on using.

1. Download the directory and place it where you want it in your web server's directory.
2. Grant your web server write permissions to the assets/json directory and all files in it (for configuration)
3. SQLite
	* Grant your web server write permissions to the assets/db directory and all files in it (it needs directory access for temporary file creation/writing).
4. MySQL
	* Download the SQL script.
	* Create a database (or use an existing one) with a user who has SELECT, UPDATE, and DELETE access. I recommend granting the user access to Views, too, just in case that's used in a future version.
	* Run the assets/sql/MySQL.sql script against that database (it should create a table called COLLECTION).
5. API(s)
	* Obtain an API from The Movie Database (http://help.themoviedb.org/kb/general/how-do-i-register-for-an-api-key).
	**Note:** If you intend to use the application regularly, please obtain your own key so the maximum request/day is never hit. The key is free.
6. Configuration
	* Browse to the application in a web browser (should display index.php).
	* Either go to the settings page (the gear in the top-right of the screen) or click on "Search" or "View Library" (because if no configuration file is found, you are redirected to the configuration screen).
	* Fill in all information, test the database connection, and save the settings.
7. You are ready to use Collect'em.

### License
Released under the [GPLv3 license.](http://www.gnu.org/licenses/gpl.html)

Repository available on both [BitBucket](https://bitbucket.org/jkrehm/collectem) and [Github](https://github.com/jkrehm/collectem).