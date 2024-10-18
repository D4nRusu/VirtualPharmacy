Web application
===
Rusu Dan
---

Features:
=========
- Registration
  
- Login
  
- 1200+ products displayed over 26 pages
  
- Clicking on any product will jump to its description page
  
- If the user is logged in, they can click on the heart icon in order
  to mark that product as favorite.
  
- I have included some screenshots (in the `screenshots` folder) of all the 
features listed above.

Database details:
=================
- The database sql dump can be found in `/sql/devshop.sql`

- All database settings, including the database user and database name, can be
  found in `db_settings.php.`. So if you need to change anything to connect to
  the database, this is the place. (for example, database username/password)
  



The logic:
==========
- `index.php` the home page
  
- Users that are not logged in may browse through the products and see their
  details. However, in order to save a product as favorite, the user needs to
  create an account and login.
  
- Once logged in, the user will not be able to access the Login and Register pages
  (instead they will be redirected to the home page)
  
- Pressing the Logout button at the top of the page will (obviously) log the user
out

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
    - There's no need to press a submit button or refresh the page for change
    to take place.

- There are 3 classes that handle (most of) the logic:
    - **Database** - ensures a connection to the database, using the configuration
    in `db_settings.php`
    - **Products** - has methods for everything product related (adding/deleting
      to/from favorites, getting the products, etc.)
    - **User** - handles login, register, account information, etc.
    
- Each page is rendered in the `.phtml` files, which are requested in their
specific `.php` file.
    