![screenshot of sample](https://image.ibb.co/htyVMz/Screenshot_1.png) 
<h1>Functions that the system supports</h1>

1. Add a movie
2. Delete the movie
3. Show movie info
4. Show a list of movies sorted by title in alphabetical order.
5. Find a movie by title.
6. Find a movie by the name of an actor.
7. Import movies from a text file (`/import/sample_movies.txt`).
<h1> File structure </h1>

		C:.
		├───config 		- configuration files
		├───controllers 	- contains controllers
		├───import 		- contains a text file for importing data into a database
		├───lib 		- libraries for the normal functioning of the site
		├───models 		- contains models for working with the database 
		├───schema 		- schema database
		├───views 		- pages with markup site
		│   ├───films
		│   └───pages
		└───webroot 		- public resources for the user
		    ├───css
		    ├───fonts
		    ├───img
		    └───js
		  
<h1>More about architecture</h1>

The application is written according to the MVC methodology.
* `index.php` -  is the entry point to the application.
* `/lib/config.class.php` - The class is responsible for saving the site settings, such as the parameters of the connection with the database. 
* `/lib/app.class.php` - This is the main class. Creates all the necessary objects for the application.
* `/lib/pagination.class.php` - This class is responsible for paginated browsing.
* `/lib/init.php` - Class Autoloader.
* `/lib/model.class.php` - The base class of the model for working with the database.
* `/lib/router.class.php` - This class parses the url string into parts.
* `/lib/session.class.php` - Class for working with sessions and messages for users.
* `/lib/view.class.php` - Class displays html content.
* `/lib/db.class.php` - This is a class for working with DB.

<h1>How to install?</h1>
<h4>Environment requirements</h4>

* PHP 7.0 or higher
* extension mysqli
* MySQL 5.7.21 
* Apach or php local server

<h4>Installation</h4>

1. Clone a project
2. Import the DB scheme from `/schema/cinema.sql`
3. Open the file `/config/config.php` and configure the basic settings:

`Config::set('site_name', 'Cinema');` - name of the site
<br>
`Config::set ('routes', array(  
    'default' => '',
    'admin' => 'admin_',
) );` - list of routes
<br>
`Config::set('default_route', '
');` - default route
<br>
`Config::set('default_controller', 'films');` - default controller
`Config::set('default_action', 'index');` - default action
<br>
`Config::set('db.host','localhost');` - host
<br>
`Config::set('db.user','root');`- user db
<br>
`Config::set('db.password','');`- password db
<br>
`Config::set('db.db_name','cinema');`- name table db
<br>
`Config::set('salt', '23fd45g2f839');` - salt
<br>

4. Is ready.




