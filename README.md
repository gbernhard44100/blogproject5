#BlogProject5 : Professionnal blog
=====================================

## Context :
This code is the result of a project I completed within a context of my degree in Web Design and Development. 
The web application using this code can be viewed in the next link : (http://www.gaetanbernhard.ovh/).
This web application meet the next specifications : [Specifications for project 5 Parcours developer PHP / Symfony](https://openclassrooms.com/projects/creez-votre-premier-blog-en-php).

## Code quality :
Link of the code analysis made by Codacy : [![Codacy Badge](https://api.codacy.com/project/badge/Grade/5cc4ecdbd0cc4bd4ad446eeb97b675d0)](https://www.codacy.com/app/gbernhard44100/blogproject5?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=gbernhard44100/blogproject5&amp;utm_campaign=Badge_Grade) <- status on the 7th July 2018

## Main Code / frameworks used for this project :
* PHP 7.0
* HTML 5
* TWIG
* CSS 3
* Bootstrap 3.7
* xml
* htaccess
* ... and my own PHP framework : GBFram !!!

## Install :
1. clone this Git repository in your project folder
2. install composer in your project directory by typing the next command in your terminal: **composer install**
3. generate the autoloader from composer by typing the next command in your terminal: **composer dump-autoload**
4. Go on the App/Blog/Config directory and rename the file **mysqlconnection.xml.dist** by **mysqlconnection.xml**.
5. Open the renamed file **mysqlconnection.xml** and fill the information like below : 
    * host: *The host of your datatbase*
    * dbname: *name of your database*
    * user: *username to connect to your database*
    * password: *password to connect to your database*
6. Create the database in MySQL and respect the constrains below :
    * For each repository : create a table in the database and name it with the same name as the attribute "table" of the repository class (Example : 'blogpost' for the class BlogPostRepositoryPDO)
    * The column names and the names of all entity attributes have to be the same.
    
7. ENJOY!!!

