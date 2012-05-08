# README
---

### History
First, thank you for checking out Collect'em, a simple web-based movie library application. I decided to create it when my wife and I had family come visit and we had a hard time deciding on a movie to watch because our movies are stored in a closet (my wife hates having to look at them, it ruins the Pinterest home she's created for us) and only one person can be in there at a time. So I wanted something that would allow me to pull it up on a tablet or a phone or even our web devices that are connected to our TV. This application is the result of that need.

### Installation
**Note:** Collect'em supports both MySQL and SQLite (for those who don't want to devote resources to a full-fledged database). Steps 2 and 3 deal with the database setup, so you only have to follow the steps for the one you are planning on using.

1. Download the directory and place it where you want it in your web server's directory.
2. SQLite
	1. Grant your webserver write permissions to the assets/db directory and all files in it (it needs directory access for temporary file creation/writing)
3. MySQL
    1. Download the SQL script. 
    2. Create a database (or use an existing one) with a user who has SELECT, UPDATE, and DELETE access. I'd recommend giving them access to Views, too, just in case that's used in a future version. 
	3. Run the SQL script against that database (it should create a table called COLLECTION)
	4. Open configuration.xml and put your database information in there.
4. API's

### Release History
