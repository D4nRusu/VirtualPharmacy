OOP Web Application
===
Made by Rusu Dan
---

Features:
=========
- Front-end built with Bootstrap

- Account registration with proper field validation
  
- 1200+ products stored in a database and displayed over 26 pages
  
- Each product has its own description page
  
- If the user is logged in, they can click on the heart icon in order
  to mark that product as favorite. (add to shopping cart basically)
  
- I have included some screenshots (in the `screenshots` folder) of all the 
features listed above.

Database details:
=================
- The database sql dump can be found in `/sql/devshop.sql`

- All database settings, including the database user and database name, can be
  found in `db_settings.php.`.


The logic:
==========
- `index.php` the home page

- There are 3 classes that handle (most of) the logic:
    - **Database** - ensures a connection to the database, using the configuration
    in `db_settings.php`
    - **Products** - has methods for everything product related (adding/deleting
      to/from favorites, getting the products, etc.)
    - **User** - handles login, register, account information, etc.
      
- Users that are not logged in may browse through the products and see their
  details. However, in order to save a product as favorite, the user needs to
  create an account and login.
  
- Once logged in, the user will not be able to access the Login and Register pages
  (instead they will be redirected to the home page). There is also functionality for
  logging out.

- How adding a product to favorites works:
    - The user clicks on a heart icon, which turns to red if they are adding
      that product to favorites, or back to white if they are removing it
    - On click, `addToFavorites.js` is called
    - The script sends the id and checked status of 
      the product associated with the respective heart icon
      to the server using FetchAPI
    - Then the server checks if the user wanted to add or remove that product
      from favorites, and calls the method `saveFavoriteProduct` or 
      `deleteFavoriteProduct` (from the Products class) depending on the operation
      that needs to be executed.
    - These operations happen asynchronously for enhanced user experience (AJAX).

    
    
- Each page is rendered in the `.phtml` files, which are requested in their
specific `.php` file.
    
