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

    cinema:.
    ├───app
    │   ├───Controllers - contains controllers
    │   ├───Core - core libraries for the normal functioning of the site
    │   ├───Exceptions - custom application exceptions 
    │   ├───Models - contains models for working with the database 
    │   ├───Requests - contains validation of request parameters
    │   └───Services - сontains business logic 
    │       └───File
    ├───config - configuration files
    ├───import - contains a text file for importing data into a database
    ├───schema - schema database
    ├───views - pages with markup site
    │   ├───films
    │   └───pages
    └───webroot - public resources for the user
        ├───css
        ├───img
        └───js

		  
<h1>More about architecture</h1>

The application is written according to the MVC methodology.
* `index.php` -  is the entry point to the application.
* `/app/Core/Config.php` - The class is responsible for saving the site settings, such as the parameters of the connection with the database. 
* `/app/Core/Bootstrap.php` - This is the main class. Creates all the necessary objects for the application.
* `/app/Core/Pagination.php` - This class is responsible for paginated browsing.
* `/app/Core/Models.php` - The base class of the model for working with the database.
* `/app/Core/Router.php` - This class parses the url string into parts.
* `/app/Core/Session.php` - Class for working with sessions and messages for users.
* `/app/Core/View.php` - Class displays html content.
* `/app/Core/Database.php` - This is a class for working with DB.

<h1>How to install?</h1>
<h4>Environment requirements</h4>

* PHP 8.0 or higher
* extension pdo
* MySQL 5.7.21 
* Apache or php local server

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
4. Run command `php composer install`
5. Switch to directory `/webroot`
6. Run command php -S localhost:8000
7. Is ready.




