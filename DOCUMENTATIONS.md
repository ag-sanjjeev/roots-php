## &#10162; DOCUMENTATIONS:

Welcome to the documentation for **ROOTS Framework**, It is a simple, lightweight and independent PHP MVC framework. This documentation provides a comprehensive walkthrough of **ROOTS Framework**, and explaining the details of its architecture, core components, and usage. It is built entirely without third-party libraries, packages and plugins. It offers a clean and efficient foundation for web application development with simple MVC architecture in PHP. 

### &#9780; Content:
1. [Overview](#-overview)
2. [Requirements](#-requirements)
3. [Installation](#-installation)
4. [Project Structure](#-project-structure)
5. [Configuration](#-configuration)
6. [Views](#-views)
7. [Routes](#-routes)
8. [Controllers](#-controllers)
9. [Models](#-models)
10. [Request](#-request)
11. [Response](#-response)
12. [Middlewares](#-middlewares)
13. [Database](#-database)
14. [Session](#-session)
15. [Cookie](#-cookie)
16. [Storage](#-storage)
17. [Exception Handler](#-exception-handler)
18. [Logger](#-logger)
19. [HTACCESS](#-htaccess)
20. [ROBOTS](#-robots)

### &#10022; Overview:

This PHP Framework is developed without support of any other third-party libraries, packages and plug-ins. 

This documentation covers essential aspects of the **ROOTS Framework**, It starting with system requirements and installation procedures. Then it guides you through configuring the framework to your specific needs. Further, It explores core components like views, routes, controllers, and models, explaining how they interact to handle requests and generate responses. Also, It explains about request, response, middleware implementation, database interaction, session and cookie management, file storage, exception handling, and logging. Finally, it addresses server configurations with HTACCESS and search engine optimization with ROBOTS.

### &#10022; Requirements:

The **ROOTS Framework** requires PHP version 8.1 or higher. This applies to both the core framework dependencies and development dependencies, ensuring compatibility for both production and development environments. The `minimum-stability` is set to `dev`, indicating the framework is currently in development.

It requires git to be installed in the system to clone the framework. It requires composer to be installed in the system for install dependencies and packages as well as create composer autoloader. 

### &#10022; Installation:

Currently, This project is not available in any of the package managers.

To use this framework, Follow the installation process below:

**Requirements:**
- It requires PHP version 8.1 or higher.
- Git installed in the system.
- Composer installed in the system.

**Steps:**
- First create an empty project directory. Where this framework to be cloned. 
- Move to the project directory and Initiate git repository. 
- Fetch this framework latest changes with below command.
```bash
git fetch --depth 1 https://github.com/ag-sanjjeev/roots-php-framework.git
```
- Now, the repository has FETCH_HEAD, Then merge FETCH_HEAD to the current active branch by below command.
```bash
git merge FETCH_HEAD
```

**Post Installation:**
- Copy the `example.config.php` as `config.php` under `app\configurations`.
- Install composer dependencies and packages.

*For production:*
```bash
composer install --no-dev
```
*For development:*
```bash
composer install
```
- Initiate Composer Autoload with below command.
```bash
composer dump-autoload
```

*Make sure, the framework works without any trouble before start developing the project. And apply testing logic and run test before use it.*

### &#10022; Project Structure:

```
.
├── app
│   ├── configurations
│   │   ├── config.php
│   │   └── example.config.php
│   ├── controllers
│   │   └── DemoController.php
│   ├── core
│   │   ├── Configuration.php
│   │   ├── Cookie.php
│   │   ├── Database.php
│   │   ├── ExceptionHandler.php
│   │   ├── Logger.php
│   │   ├── Model.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Route.php
│   │   ├── Session.php
│   │   └── Storage.php
│   ├── logs
│   ├── middlewares
│   │   └── auth.php
│   ├── models
│   │   └── Demo.php
│   └── routes
│   │   ├── auth.routes.php
│   │   ├── entries.routes.php
│   │   └── web.routes.php
│   └── Main.php
├── public
│   ├── responses
│   │   ├── 404.view.php
│   │   └── 500.view.php
│   ├── storage
|   |   ├── uploads
|   |   └── assets
│   └── views
│   │   ├── dashboard
│   │   │   └── index.view.php
│   │   ├── demo
│   │   │   ├── form.view.php
│   │   │   ├── index.view.php
│   │   │   └── show.view.php
│   │   └── user
|   |   |   ├── register.view.php
│   │   │   └── login.view.php
│   │   └── welcome.view.php
│   └── index.php
├── .htaccess
├── composer.json
├── DOCUMENTATIONS.md
├── LICENSE
├── README.md
└── robots.txt
```

**Project Structure Explanation:**

This structure organizes the **ROOTS Framework**, separating concerns for maintainability and scalability.


**Directory Explanation:**

-  `app`:  Contains the core logic of the application.
-  `app/configurations`: Holds configuration files.
-  `app/controllers`: Holds controller classes.
-  `app/core`: Contains core framework classes.
-  `app/logs`: Directory for store different log files.
-  `app/middlewares`: Contains middleware classes.
-  `app/models`: Contains model classes.
-  `app/routes`: Contains route definitions.
-  `public`: Contains publicly accessible files.
-  `public/responses`: Stores custom response views (e.g., error pages).
-  `public/storage`: Publicly accessible storage.
-  `public/views`: Contains view files.

**File Explanation:**

-  `app/configurations/config.php`: Main configuration file.
-  `app/configurations/example.config.php`: Configuration template.
-  `app/controllers/DemoController.php`: Example controller.
-  `app/core/Configuration.php`: Handles configuration loading.
-  `app/core/Cookie.php`: Manages cookies.
-  `app/core/Database.php`: Handles database interactions.
-  `app/core/ExceptionHandler.php`: Custom handlers for errors and exceptions.
-  `app/core/Logger.php`: Handles logging.
-  `app/core/Model.php`: Base model class.
-  `app/core/Request.php`: Handles HTTP requests.
-  `app/core/Response.php`: Handles HTTP responses.
-  `app/core/Route.php`: Handles routing.
-  `app/core/Session.php`: Manages sessions.
-  `app/core/Storage.php`: Handles file storage.
-  `app/middlewares/auth.php`: Example authentication middleware.
-  `app/models/Demo.php`: Example model.
-  `app/routes/auth.routes.php`: Authentication routes.
-  `app/routes/entries.routes.php`: Routes for entries.
-  `app/routes/web.routes.php`: Main web routes.
-  `app/Main.php`: Framework's entry point.
-  `public/responses/404.view.php`: 404 error page.
-  `public/responses/500.view.php`: 500 error page.
-  `public/views/dashboard/index.view.php`: Demo Dashboard index view.
-  `public/views/demo/form.view.php`: Demo form view.
-  `public/views/demo/index.view.php`: Demo index view.
-  `public/views/demo/show.view.php`: Demo show view.
-  `public/views/user/login.view.php`: Demo User login view.
-  `public/views/user/welcome.view.php`: Demo Welcome view.
-  `public/index.php`: Main entry point for all requests.
-  `.htaccess`: Server configuration file.
-  `composer.json`: Composer manifest file.
-  `DOCUMENTATIONS.md`: Project documentation.
-  `LICENSE`: Project license.
-  `README.md`: Project README file.
-  `robots.txt`: Robots exclusion file.

### &#10022; Configuration:

The framework's configuration resides within the `app/configurations/` directory. Two files are present: `config.php` (the active configuration) and `example.config.php` (a template). If `config.php` is absent, copy `example.config.php` and rename it to `config.php`. This file returns a PHP array containing key-value pairs that define the framework's behavior.

**Possible Configurations Explanation:**

*Example 1 (Basic Setup):*

```php
<?php
return [
    'application' => [
        'app_name' => 'MySimpleApp',
        'session_name' => 'a_very_random_session_key', // Replace with a truly random/unpredictable session name
        'root_path' => dirname(dirname(__DIR__)), // Don't change
        'middleware_namespace' => 'roots\\app\\middlewares\\',
        'storage_directory' => 'storage',
        'timezone' => 'UTC',
        'environment' => 'development'
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'my_simple_db',
        'username' => 'simple_user',
        'password' => 'simple_password'
    ],
    'logger' => [
        'path' => 'app/logs',
        'max_files' => 5,
        'file_size' => 1048576 // 1MB
    ],
    'response' => [
        404 => dirname(dirname(__DIR__)) . '/public/responses/404.view.php',
        500 => dirname(dirname(__DIR__)) . '/public/responses/500.view.php'
    ]
];
```

*Example 2 (Production Setup):*

```php
return [
    'application' => [
        'app_name' => 'MyProductionApp',
        'session_name' => 'a_strong_and_unique_session_key', // Replace with a truly random/unpredictable session name
        'root_path' => dirname(dirname(__DIR__)), // Don't change
        'middleware_namespace' => 'roots\\app\\middlewares\\',
        'storage_directory' => 'data', // More descriptive name
        'timezone' => 'America/Los_Angeles',
        'environment' => 'production'
    ],
    'database' => [
        'driver' => 'mysql',
				'host' => 'localhost',
				'port' => 3306,
        'dbname' => 'production_db',
        'username' => 'production_user',
        'password' => 'a_very_strong_password'
    ],
    'logger' => [
        'path' => 'app/logs',
        'max_files' => 10,  // Keep more logs in production
        'file_size' => 5242880 // 5MB
    ],
    'response' => [
        404 => dirname(dirname(__DIR__)) . '/public/responses/errors/404.view.php', // Custom 404 page
        500 => dirname(dirname(__DIR__)) . '/public/responses/errors/500.view.php'  // Custom 500 page
    ]
];
```

**Detailed Configuration Explanation:**

1.  `application` Configuration:

  -  `app_name`: The name of your application. That will be useful and reflect in your application anywhere.

      ```php
      'app_name' => 'MyBlog', // Example
      'app_name' => 'OnlineStore', // Example
      ```

  -  `session_name`:  The name of the session cookie. It is important and critical for security!. Use a long, random, unique and unpredictable name.

      ```php
      'session_name' => 'a_long_and_random_string_that_is_unique', // Good
      'session_name' => 'my_app_session', // Bad - too predictable
      ```

  -  `root_path`: The absolute path to your project's root directory. Do not change this configuration setting.

  -  `middleware_namespace`:  The namespace for all middleware classes.

      ```php
      'middleware_namespace' => 'roots\\app\\middlewares\\', // Default
      'middleware_namespace' => 'App\\Http\\Middleware\\', // Another Example
      ```

  -  `storage_directory`: The directory where your application stores files.

      ```php
      'storage_directory' => 'storage', // Default
      'storage_directory' => 'uploads', // For user uploads
      'storage_directory' => 'data', // General data storage
      ```

  -  `timezone`: The timezone for your application. Use a valid PHP timezone identifier.

      ```php
      'timezone' => 'UTC', // Coordinated Universal Time
      'timezone' => 'America/New_York', // Eastern Time
      'timezone' => 'Europe/London', // British Summer Time
      'timezone' => 'Asia/Kolkata', // Indian Standard Time
      ```

  -  `environment`: The current environment.

      ```php
      'environment' => 'development', // For development purposes
      'environment' => 'production', // For live sites
      ```

2.  `database` Configuration:

    -  `driver`: The database driver. But currently, the framework model is developed based on MySQL. Use your own model logic by extending and overwrite your model methods to adapt as per your database driver.

        ```php
        'driver' => 'mysql', // MySQL
        'driver' => 'pgsql', // PostgreSQL
        'driver' => 'sqlite', // SQLite
        ```

    -  `host`: The database server hostname.

        ```php
        'host' => 'localhost', // Local development
        'host' => '127.0.0.1', // Local development
        'host' => 'your_db_host.com', // Remote server
        ```

    -  `port`: The database server port.

        ```php
        'port' => 3306, // MySQL (default)
        'port' => 5432, // Some other port Example: PostgreSQL (default)
        ```

    -  `dbname`: The name of the database.

        ```php
        'dbname' => 'my_database',
        'dbname' => 'blog_db',
        ```

    -  `username`: The database username.

        ```php
        'username' => 'db_user',
        'username' => 'root', // Use with caution in production!
        ```

    -  `password`: The database password.  Protect this!

        ```php
        'password' => 'your_strong_password',
        ```

3.  `logger` Configuration:

    -  `path`: The path to the log directory.

        ```php
        'path' => 'app/logs',
        'path' => 'logs', // Relative to project root
        ```

    -  `max_files`: The maximum number of log files to keep per level.

        ```php
        'max_files' => 5,
        'max_files' => 10, // Keep more log history
        ```

    -  `file_size`: The maximum size of each log file (in bytes).

        ```php
        'file_size' => 1048576, // 1MB
        'file_size' => 2097152, // 2MB
        ```

4.  `response` Configuration:

    -  `404`, `500`, etc.: Paths to custom error response view files.

        ```php
        'response' => [
            404 => dirname(dirname(__DIR__)) . '/public/responses/errors/404.view.php',
            500 => dirname(dirname(__DIR__)) . '/public/responses/errors/500.view.php',
            403 => dirname(dirname(__DIR__)) . '/public/responses/errors/403.view.php', // Example: 403 Forbidden
        ],
        ```

Remember to replace placeholder values with your actual settings. Pay close attention to security best practices, especially when configuring session names and database credentials. Using environment variables for sensitive data is highly recommended for production environments.

**Accessing Configuration Values:**

The **ROOTS Framework** provides a centralized way to access configuration settings through the `Configuration` class. This class allows you to retrieve configuration values defined in your `config.php`.

**Retrieving Configuration Values:**

You use the static `get()` method of the `Configuration` class to access configuration values.  The `get()` method takes a string as a first argument, which represents the key name. And takes mixed type as a second argument, which represents default value if the key value pair missing. This key name uses dot notation to access nested configuration arrays.

**Example:**

```php
self::$rootPath = Configuration::get('application.root_path');
self::$environment = Configuration::get('application.environment');
self::$timezone = Configuration::get('application.timezone');
```

### &#10022; Views:

Views in the **ROOTS Framework** are responsible for presenting data to the user. These views reside in the `project_directory/public/views/` directory. The view files are named with the `.view.php` extension (e.g., `home.view.php`, `product.view.php`). Unlike many frameworks, **ROOTS Framework** does not employ a template engine. Instead, views are pure PHP files. This means you directly embed PHP code within your HTML to dynamically generate content. While this approach offers flexibility and speed, it requires careful organization to maintain clean separation of concerns between presentation and logic. Essentially, views focus solely on preserve the PHP code, later it is helpful to review the code and application logic. Additionally, it is easy to migrate in any other form or to any other framework.

Remember, views in this framework are pure PHP files, allowing you to embed PHP directly within your HTML.

**Some Examples:**

1. `welcome.view.php` (Simple Welcome Page):

```php
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to My Website!</h1>

    <p>Hello, <?php echo $name; ?>!  This is a simple welcome message.</p>

    <p>Current Time: <?php echo date('Y-m-d H:i:s'); ?></p>

    <a href="/about">About Us</a>

</body>
</html>
```

*   This view demonstrates how to display variables passed from the controller (`$name`).
*   It also uses PHP's `date()` function to display the current time.
*   A simple link to an "About Us" page is included.

2. `product.view.php` (Displaying Product Details):

```php
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?></title>
</head>
<body>
    <h1><?php echo $product['name']; ?></h1>

    <img src="/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">

    <p><?php echo $product['description']; ?></p>

    <p>Price: $<?php echo $product['price']; ?></p>

    <a href="/products">Back to Products</a>
</body>
</html>
```

*   This view displays details of a product stored in the `$product` array.
*   It shows how to access array elements (e.g., `$product['name']`, `$product['image']`).
*   An image is displayed using a dynamically generated `src` attribute.

3. `form.view.php` (HTML Form):

```php
<!DOCTYPE html>
<html>
<head>
    <title>Create New Product</title>
</head>
<body>
    <h1>Create New Product</h1>

    <form action="/products/create" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required><br><br>

        <button type="submit">Create</button>
    </form>

</body>
</html>
```

*   This view creates a simple HTML form for creating a new product.
*   The form's `action` attribute points to the route that will handle the form submission.
*   The `method` is set to `post` for sending the form data.

**Key Considerations for Views:**

*   **Keep Logic Minimal:** Views should primarily focus on presentation. Avoid putting complex business logic in your views.
*   **Data from Controllers:**  Views might receive data from controllers. Make sure your controllers are passing the necessary data to the views. Add validation for missing data from controller to avoid potential errors.
*   **HTML Structure:** Use well-formed HTML.
*   **Security:** Be mindful of security when displaying data. Escape any user-provided data to prevent cross-site scripting (XSS) vulnerabilities. Use functions like `htmlspecialchars()` for this purpose. For example: `<?php echo htmlspecialchars($name); ?>`

These examples provide a starting point for creating views in the **ROOTS Framework**. Remember to adapt them to your specific needs and project requirements.

### &#10022; Routes:

Routing is the backbone of any MVC web application, determining how incoming requests are directed to the appropriate handlers. In the **ROOTS Framework**, routes are defined within the `app/routes/` directory. All route files adhere to the naming convention `<name>.routes.php` (e.g., `web.routes.php`, `api.routes.php`). This structure allows for organized route management and the creation of additional route files as your project grows, promoting modularity and maintainability.

**Route Definition and Structure:**

The **ROOTS Framework** utilizes a dedicated `Route` class, which is located at `project_directory/app/core/Route.php`, to define and manage routes. To use this class, you must import it using the `use` keyword and the appropriate namespace `roots\app\core\Route`. Route definitions within these files leverage method chaining on the `Route` class, providing a concise and readable syntax.

**Available Route Methods:**

The `Route` class provides several key methods for defining routes:

-  `get(string $uri, mixed $callback)`: Defines a route that handles HTTP GET requests.
-  `post(string $uri, mixed $callback)`: Defines a route that handles HTTP POST requests.
-  `any(string $uri, mixed $callback)`: Defines a route that handles any HTTP request method (GET, POST, PUT, DELETE, etc.).
-  `middleware(string $middleware)`:  Assigns middleware to a route by following middleware namespace defined in the configuration.
-  `name(string $name)`: Assigns a name to a route, allowing for easy referencing later.

**Route Callbacks and Targets:**

The second argument to the `get()`, `post()`, and `any()` methods specifies the *callback* or *target* for the route.  This target can take several forms, each offering different levels of flexibility:

1.  **String Callback:**  Specifies a view file.  The framework will automatically include and render the specified view.

    ```php
    Route::get('/about', 'about'); // Renders the 'about.view.php' file under `public\views` directory
    ```

2.  **Closure/Anonymous Function:** Defines a function inline to handle the request. Useful for simple routes or quick prototyping.

    ```php
    Route::get('/hello/{name}', function ($name) {
        echo "Hello, " . $name;
    });
    ```

3.  **Controller Class and Method (Array Callback):** Specifies a controller class and method to handle the request. This is the recommended approach for most routes as it promotes separation of concerns and maintainability.

    ```php
    use roots\app\controllers\ProductController; // use the corresponding namespace for the controller that going to target for requests

    Route::get('/products', [ProductController::class, 'index']); // Calls the 'index' method of the 'ProductController'.
    ```

**Route Naming:**

It assigns name to the route. It can be useful. It is for identification purpose, But it is not give the actual URL for corresponding name.

```php
Route::get('/', 'welcome')->name('home'); // The route '/' is named 'home'.
```

**Route Middleware:**

Middleware allows you to add pre-processing logic to routes, such as authentication, authorization, or input validation.

```php
use roots\app\controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth'); // The 'auth' middleware will be executed before the 'DashboardController' index method.
```

*Example Route Definitions (Detailed):*

```php
use roots\app\core\Route;
use roots\app\controllers\DemoController;
use roots\app\controllers\AdminController;
use roots\app\controllers\ContactController;

// Simple route to a view
Route::get('/', 'welcome')->name('home'); // Renders 'welcome.view.php'

// Route with a parameter and function callback
Route::get('/user/{id}', function ($id) {
    echo "User ID: " . $id;
})->name('user.profile');

// Route to a controller method
Route::get('/products', [DemoController::class, 'index'])->name('products.index');

// Route to render form view
Route::get('/product/create', 'product/create')->name('products.create');

// Route to handle form submission
Route::post('/product/store', [DemoController::class, 'store'])->name('products.store');

// Route with parameters and controller method
Route::get('/product/{id}/edit', [DemoController::class, 'edit'])->name('products.edit');

// Route with middleware
Route::get('/admin/panel', [AdminController::class, 'index'])->middleware('auth')->name('admin.panel');

// Route using Route::any for handling multiple request methods
Route::any('/contact', [ContactController::class, 'index'])->name('contact');
```

This comprehensive explanation of routing in the **ROOTS Framework** provides a simple foundation for managing your application's request handling.

### &#10022; Controllers:

Controllers are important in application logic. It might handle and provide response based on inputs, logics and requests. It helps to interact with models to retrieve and manipulate data, and then pass that data to views for presentation. In the **ROOTS Framework**, controllers reside within the `app/controllers/` directory. You have the flexibility to organize your own controllers into subdirectories within `app/controllers/` as needed, promoting better code organization for larger projects.

**Controller Structure and Naming:**

Each controller file should adhere to the namespace `namespace roots\app\controllers;`. While the framework doesn't enforce a strict naming convention, it's a best practice to use descriptive names for your controllers (e.g., `UserController.php`, `ProductController.php`).

**Controller Methods and Accessibility:**

Controllers typically contain methods that handle actions for specific requests. You can define your own methods within your controllers, adhering to standard PHP visibility rules (public, protected, private). Public methods are generally used to handle requests, while protected or private methods can be used for internal controller logic.

**Middleware Integration within Controllers:**

As the **ROOTS Framework** follows Object Oriented Programmings, this allows to use middleware directly within your controllers. This allows you to apply multiple middlewares to specific controller actions, providing fine-grained control over request processing. You can load and use custom middlewares within the controller's constructor.

*Example Controller Definition:*

```php
<?php

namespace roots\app\controllers;

use roots\app\core\Request; // Import the Request class
use roots\app\models\Demo; // Example Model Import
use roots\app\core\Response;
use roots\app\core\Logger;
use roots\app\core\Storage;

class DemoController
{
    /**
     * Constructs a new DemoController object.
     */
    public function __construct()
    {
       // Example: load custom middleware (if needed)
       //$this->middleware('auth'); // Example usage
    }

    /**
     * Handles the index request.
     *
     * @return view.
     */
    public function index()
    {
        $contentType = 'text/html';
        $isAcceptContent = Request::isAcceptableContentType($contentType);

        try {
          if (!$isAcceptContent) {
            throw new Exception("Unacceptable content type", 1);
          }
        } catch (Exception $e) {
          Logger::logWarning($e);
        }
        $data = Demo::select('*')->getAll();
        return view('demo/index', $data); // Render 'demo/index.view.php'
    }

    /**
     * Handles the form request.
     */
    public function form()
    {
        return view('demo/form'); // Render 'demo/form.view.php'
    }

    /**
     * Handles the form upload request.
     */
    public function formUpload()
    {
        $target = 'upload' . DIRECTORY_SEPARATOR . 'image';
        $imagename = Request::input('imagename');		
        $file = Request::input('image');
        $target .= DIRECTORY_SEPARATOR . $imagename;
        Storage::upload($file, $target);
        echo "file uploaded";
    }

    /**
     * Handles the download file request.
     * @param string $filename.
     */
    public function downloadFile($filename)
    {
        $filePath = 'upload' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $filename . '.png';
        Storage::download($filePath);
    }

    /**
     * Handles the delete file request.
     * @param string $filename.
     */
    public function deleteFile($filename)
    {
        $filePath = 'upload' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $filename . '.png';

        Storage::unlink($filePath);
        echo 'file deleted';
    }

    /**
     * Handles the show request.
     *
     * @param int $id.
     * @return view.
     */
    public function show($id)
    {
        $contentType = 'text/html';
        $isAcceptContent = Request::isAcceptableContentType($contentType);

        if (!$isAcceptContent) {
          throw new Exception("Unacceptable content type", 1);
          die();
        }

        Response::contentType($contentType);
        $responseCode = 200;
        $data = Demo::select('*')->where(['id' => 4])->get();
        return Response::view('demo/show', ['data' => $data, 'test' => 'value'], $responseCode);
    }

    // Example of a protected method (for internal controller use)
    protected function processData($data)
    {
        // ... some data processing logic ...
        return $processedData;
    }
}
```

**Key Points Summarized:**

-  Controllers contains application logic and handle specific request.
-  It reside in the `app/controllers/` directory.
-  Use the namespace `namespace roots\app\controllers;`.
-  Define methods (typically public) to handle requests.
-  Use the constructor for initialization and middleware loading.
-  Interact with models to retrieve and manipulate data.
-  Pass data to views for rendering.
-  Organize controllers into subdirectories as needed.
-  Follow best practices for method naming and visibility.

Consider, This is a basic and simple controller logic implementation in this **ROOTS Framework**. 

### &#10022; Models:

Models serve as the interface to your data, representing database tables or data structures. They provide various methods for data interaction as well as define your custom methods in the model itself. In the **ROOTS Framework**, models reside in the `app/models/` directory. You can organize them into subdirectories as needed.

**Model Structure and Naming:**

All models must extend the base `Model` class found at `project_directory/app/core/Model.php`.  The namespace should be `namespace roots\app\models;` and you'll need `use roots\app\core\Model;`.  Use descriptive file names (e.g., `User.php`, `Product.php`).

**Defining Model Properties:**

It is essential and important to define table name, fields and primary key for current model class. Because, base model class will work with that properties when using the model methods. 

*Example: Definition for model properties*

```php
protected static string $tableName = 'articles'; // Database table name
protected static array $fields = ['id', 'article_title', 'content']; // Table fields
protected static string $primaryKey = 'id'; // Primary key column
```

**Base Model Methods:**

The `Model` class provides methods for database interaction, designed for method chaining:

*   `instance()`: Returns a model instance.
*   `select()`: Specifies columns to select.
*   `get()`: Retrieves a single record.
*   `getAll()`: Retrieves all matching records.
*   `where()`: Adds a WHERE clause.
*   `whereAnd()`: WHERE clause with AND.
*   `whereOr()`: WHERE clause with OR.
*   `groupBy()`: Adds a GROUP BY clause.
*   `orderAsc()`: ORDER BY (ascending).
*   `orderDesc()`: ORDER BY (descending).
*   `limit()`: Adds a LIMIT clause.
*   `insert()`: Inserts a new record.
*   `update()`: Updates records.
*   `delete()`: Deletes records.
*   `set()`: Executes the update query.
*   `execute()`: Executes a query when method chaining.

**Instance Creation:**

```php
$obj = Demo::instance(); // it is instanceof Model;
```

**Data Retrieval:**

- Basic SELECT:

```php
$result = Demo::select('*')->get(); // Select all columns and get the first record.
$result = Demo::select('*')->getAll(); // Select all columns and get all records.
```

These are the most basic select queries. `select('*')` means "select all columns".  `get()` retrieves only the first matching row as an object or associative array, while `getAll()` returns all matching rows as an array of objects or arrays.

- SELECT with WHERE clause:

```php
$result = Demo::select('*')->where(['article_title' => 'test fourth title', 'content' => 'some other content'])->getAll(); // Select all where both conditions are true.
$result = Demo::select('*')->where(['id' => 4])->get(); // Select all where id is 4 and get the first record.
```

The `where()` method allows you to filter the results.  In the first example, the query will only return rows where both `article_title` matches 'test fourth title' and `content` matches 'some other content'.  The second example filters by `id = 4`.

- SELECT with WHERE AND/OR:

```php
$result = Demo::select('*')->whereAnd(['id' => 4, 'article_title' => 'test third title'])->get(); // Select all where id is 4 AND article_title is 'test third title' and get the first record.
$result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->getAll(); // Select all where id is 4 OR id is 5.
$result = Demo::select('*')->whereOr(['id' => 4])->whereOr(['id' => 5])->getAll(); // Same as above (OR conditions chained).
$result = Demo::select('*')->where(['author' => 2])->whereAnd(['id' => 5])->getAll(); // Select all where (author is 2) AND (id is 5).
$result = Demo::select('*')->whereOr(['author' => 2])->whereAnd(['id' => 5])->getAll(); // Same as above.
$result = Demo::select('*')->where(['id' => 4])->whereAnd(['article_title' => 'test third title'])->get(); // Select all where id is 4 AND article_title is 'test third title' and get the first record.
```

`whereAnd()` adds conditions that must all be true. `whereOr()` adds conditions where at least one must be true.  You can chain `where()` with `whereAnd()` or `whereOr()` to create more complex queries.

- SELECT with ORDER BY:

```php
$result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->orderDesc(['id', 'title'], 'content')->getAll(); // Select all where id is 4 OR id is 5, ordered by id and title descending, then content.
$result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->orderDesc('content', 'id')->getAll();  // Select all where id is 4 OR id is 5, ordered by content descending, then id.
$result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->orderAsc('content', 'id')->getAll(); // Select all where id is 4 OR id is 5, ordered by content ascending, then id.
```

`orderDesc()` sorts the results in descending order.  You can provide multiple columns as an array; the results will be sorted by the first column, then the second, and so on.  `orderAsc()` does the same but in ascending order.

- SELECT with GROUP BY:

```php
$result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->groupBy('author')->orderAsc('content', 'id')->getAll(); // Select all where id is 4 OR id is 5, grouped by author, then ordered by content ascending, then id.
```

`groupBy()` groups the results based on the specified column.  It's often used with aggregate functions (like `SUM`, `COUNT`, `AVG`).

- SELECT with Aggregate Functions:

```php
$result = Demo::select('*', 'author', 'SUM(views) AS total_views')->getAll(); // Select all columns, author, and the sum of views as total_views.
$result = Demo::select('author', 'SUM(views) AS total_views')->groupBy('author')->orderDesc('total_views')->getAll(); // Select author and sum of views, grouped by author, ordered by total_views descending.
$result = Demo::select(['author', 'SUM(views) AS total_views'])->groupBy('author')->orderDesc('total_views')->getAll(); // Same as above, using array for select.
```

These examples show how to use aggregate functions like `SUM()` within your `select()` method.  You can alias the result of the aggregate function using `AS`.

- SELECT with LIMIT:

```php
$result = Demo::select(['author', 'SUM(views) AS total_views'])->groupBy('author')->orderDesc('total_views')->limit(0,3)->getAll(); // Same as above, limited to 3 records (starting from offset 0).
$result = Demo::select(['author', 'SUM(views) AS total_views'])->groupBy('author')->orderDesc('total_views')->limit(1)->getAll(); // Same as above, limited to 1 record.
```

`limit()` restricts the number of rows returned.  You can specify an offset (starting position) and the number of rows to retrieve.

**Data Manipulation:**

- INSERT:

```php
Demo::insert(['article_title' => 'title413', 'content' => 'some content', 'author' => 8, 'views' => 15, 'comments' => 2]); // Insert a new record.
```

The `insert()` method adds a new row to the database table.  You provide the data as an associative array, where the keys are the column names and the values are the data to be inserted.

- UPDATE:

```php
Demo::update(['article_title' => 'title415'])->where(['id' => 415])->set(); // Update article_title where id is 415.
Demo::update(['article_title' => 'title7', 'content' => 'content 7'])->where(['id' => 7])->set(); // Update article_title and content where id is 7.
Demo::update(['article_title' => 'title test', 'content' => 'content test'])->where(['id' => 8, 'article_title' => 'title8'])->set(); // Update where id is 8 AND article_title is 'title8'.
Demo::update(['article_title' => 'title test', 'content' => 'content test'])->where(['id' => 9])->whereOr(['article_title' => 'title1', 'id' => 12])->set(); // Update where id is 9 OR (article_title is 'title1' AND id is 12).
```

The `update()` method modifies existing records.  You provide the new data as an associative array, use `where()` (or `whereAnd()`, `whereOr()`) to specify which records to update, and then call `set()` to execute the update.

- DELETE:

```php
Demo::delete(415); // Delete record with primary key of 415. which considers primary key name defined as property in Demo model class 
Demo::delete(['id' => 409, 'author' => 1]); // Delete record where id is 409 AND author is 1.
Demo::delete(['author' => 0]); // Delete records where author is 0.
```

The `delete()` method removes records from the database.  You can specify the records to delete using a single primary key ID, or an associative array of conditions (similar to `where()`).

These examples cover the core Model functionalities and how to chain them.  Remember that this framework, as described, is specifically designed for MySQL.

Since the base `Model` class methods are designed for static usage (e.g., `Demo::select('*')->get()`), the custom methods in your models should also be either static or not. Best practices, follow same structure of method chaining in model classes. This ensures consistency and allows you to call them directly on the model class without needing to instantiate it.

Here's the corrected version of the `Article` model with static custom methods:

```php
<?php
namespace roots\app\models;

use roots\app\core\Model;

class Article extends Model
{
    protected static string $tableName = 'articles';
    protected static array $fields = ['id', 'article_title', 'content', 'author', 'views', 'published_at'];
    protected static string $primaryKey = 'id';

    // 1. Articles by Author (Static):
    public static function getArticlesByAuthor(int $authorId)
    {
        return static::select('*') // Use static:: for static methods
                    ->where(['author' => $authorId])
                    ->getAll();
    }
}
```

**Key points:**
- It is developed with simpler logics.
- It supports conditional statements with where clause only with equals.
- It does not support various conditional statements such as LIKE, Greater than, Less than and so on.

### &#10022; Request:

The `Request` class, residing at `project_directory/app/core/Request.php` under the `roots\app\core` namespace, acts as your central hub for accessing all aspects of the incoming HTTP request. Think of it as a direct line to the client's interaction with your application.  

**Decoding the Request:**

- Protocol, Host, and Base URL:

```php
Request::protocol();  // http or https
Request::host();      // example.com or example.com:8080
Request::baseURL();   // https://example.com
```

These methods provide the foundational elements of the request URL.  `protocol()` tells you whether the request was made over HTTP or HTTPS. `host()` reveals the server's hostname, including the port if it's non-standard. `baseURL()` combines the protocol and host to give you the root URL of your application.

- Navigating the URL:

```php
Request::fullUrl();    // The complete URL, including query parameters
Request::urlPath();    // The URL path (everything after the base URL, but before query parameters)
Request::urlParams('string'); // Access URL parameters defined in your routes
```

These methods help you dissect the request URL. `fullUrl()` gives you the entire URL string, perfect to get the information of the user requested for. `urlPath()` isolates the part of the URL that identifies the specific resource being requested. `urlParams()` lets you pluck out specific parameters embedded within the URL as defined in your routes. For instance, in a route like `/users/{id}`, `Request::urlParams('id')` would retrieve the user ID.

**Request Method:**

```php
Request::method(); // GET, POST, PUT, DELETE, etc.
```

This method reveals the HTTP request methods being used in the request, indicating the action the client intended to perform (retrieving data, submitting a form, updating a resource, etc.).

**Sifting Through User Input:**

- Accessing Input Data:

```php
Request::input('price');      // Get a specific input value
Request::inputsOnly(['rate', 'tax']); // Get only the specified input values
Request::inputsExcept('csrf_token');    // Get all input values *except* the specified ones
```

These methods are helpful to extracting data submitted by the client, typically from forms or API requests. `input('price')` retrieves the value associated with a given input field name.  `inputsOnly()` lets you cherry-pick specific input values, while `inputsExcept()` does the opposite, grabbing everything *but* the named fields.

- Checking for Input Presence:

```php
Request::hasInput('csrf_token');        // Check if an input field exists
Request::missingInputs(['price', 'rate']); // Check for missing input fields
```

These methods help you validate the incoming data.  `hasInput()` quickly verifies whether a particular input field was submitted. `missingInputs()` allows you to check for multiple missing fields at once, returning an array of the missing names.

**Negotiating Content:**

```php
Request::acceptableContentType(); // Get the client's preferred content types
Request::isAcceptableContentType(['text/html', 'application/xhtml+xml']); // Check if the client accepts specific content types
```

These methods deal with content negotiation, a process where the client and server agree on the format of the response. `acceptableContentType()` returns an array of content types the client is willing to accept (from the `Accept` header). `isAcceptableContentType()` checks if the client accepts at least one of the content types you provide.

**Unveiling the Client:**

```php
Request::ip(); // Get the client's IP address
```

This method reveals the IP address of the client making the request, which can be useful for identification, logging, security, tracking or geolocation purposes.

*Example:*

```php
$method = Request::method();          // Is it a GET or POST?
$username = Request::input('username'); // What's the username?
$onlyNameEmail = Request::inputsOnly(['name', 'email']); // Get only name and email
$exceptPassword = Request::inputsExcept('password'); // Get all but password
$nameExists = Request::hasInput('name'); // Is the name field present?
$missingFields = Request::missingInputs(['name', 'email']); // Are name or email missing?
$isJsonPreferred = Request::isAcceptableContentType('application/json'); // Does client prefer JSON?
$clientIp = Request::ip();           // Where is the request coming from?
```

### &#10022; Response:

The `Response` class, located at `project_directory/app/core/Response.php` under the `roots\app\core` namespace, is a toolkit for constructing and sending HTTP responses back to the client. It provides methods for rendering views, redirecting users, handling errors responses, and setting content types. Remember to include `use roots\app\core\Response;` at the top of your PHP files to use this class methods.

**Rendering Views:**

```php
return Response::view('article/show', ['data' => $data, 'test' => 'value'], $responseCode);
```

The `view()` method allows you to render a view file and send its output as the response.

- The first argument is the path to the view file relative to your views directory (e.g., `article/show.view.php`).
- The second argument is an optional associative array of data to be passed to the view.  You can then access this data within your view file (e.g., `<?php echo $data['title']; ?>`).
- The third argument is an optional HTTP response code (e.g., 200 for OK, 404 for Not Found). If omitted, a 200 status code is assumed.

*Example:*

```php
$article = getArticleFromDatabase(123); // Retrieve article data
return Response::view('articles/show', ['article' => $article]); // Render the 'articles/show.view.php' file, passing the $article data to the view.
```

**Redirecting Users:**

```php
return Response::redirect(string $urlPath, int $statusCode = 301);
```

The `redirect()` method sends a redirect response to the client, instructing the browser to navigate to a different URL.

- The first argument is the URL path to redirect to.
- The second argument is the HTTP status code for the redirect (301 for permanent redirect, 302 for temporary redirect). 301 is the default.

*Example:*

```php
if ($userLoggedIn) {
    return Response::redirect('/dashboard'); // Redirect to the dashboard
} else {
    return Response::redirect('/login', 302); // Redirect to login (temporary)
}
```

**Handling Errors:**

```php
errorResponse(int|string $statusCode);
```

The `errorResponse()` method is designed for handling errors, targeting and displaying appropriate error pages. It is crucial to place this at the top of any response or result displays to ensure errors are handled gracefully.

This method automatically looks for a view file corresponding to the error code under the `public/responses/` directory. For example, if `$statusCode` is 404, it will attempt to render `404.view.php`.

*Example:*

```php
if ($article === null) {
    errorResponse(404); // Display the 404 error page
    return; // Stop further execution to prevent other output
}

// If the article is found, proceed with displaying it
return Response::view('articles/show', ['article' => $article]);
```

**Setting Content Type:**

```php
Response::contentType(string $type);
```

The `Response::contentType()` method allows you to set the `Content-Type` header of the response, informing the client about the format of the data being sent.  This is essential for proper interpretation of the response by the client's browser or other applications.

*Example:*

```php
$data = ['message' => 'Hello, world!'];
Response::contentType('application/json'); // Set content type to JSON
echo json_encode($data);       // Encode the data as JSON and output it
```

Or for HTML content:

```php
Response::contentType('text/html');
echo "<h1>Hello, World!</h1>";
```

*Complete Example Combining Methods:*

```php
try {
    $user = getUserFromDatabase(123);
    if ($user === null) {
        errorResponse(404);
        return;
    }
    Response::contentType('text/html'); // Set Content-Type
    return Response::view('users/profile', ['user' => $user]); // Render the user profile view
} catch (\Exception $e) {
    errorResponse(500); // Handle exceptions and display 500 page
    return;
}
```

This example demonstrates how to use the `Response` class methods together to create more robust and flexible responses.  It handles potential errors, sets the correct content type, and renders a view. Remember to adapt these examples to your specific application logic.

### &#10022; Middlewares:

Middleware in the **ROOTS Framework** provides a powerful mechanism to intercept and modify requests before they reach your controllers actions. It acts like a gate Gatekeeper for your request, So it controlling the access and performing pre-processing tasks like authentication, authorization, input validation, and more. 

Middleware classes reside in the directory specified by your `middleware_namespace` configuration (typically `project_directory/app/middlewares`), and their namespace should match this configuration (e.g., `roots\app\middlewares`).

**Creating Middleware:**

Middleware classes are simple PHP classes that implement your custom logic. They typically have a constructor (`__construct()`) where you define the actions to be performed on the incoming request.

*Example Middleware Definition (Auth.php):*

```php
<?php
namespace roots\app\middlewares;

use roots\app\core\Response;
use roots\app\core\Session;

class Auth
{
    function __construct()
    {
        $user_id = Session::get('user_id');
        if (is_null($user_id)) {
            Response::redirect('/login');
        }
    }
}
```

**Applying Middleware:**

- Route-Level Middleware:

```php
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
```

This line in your route definition applies the `auth` middleware to the `/dashboard` route.  Before the `DashboardController`'s `index` method is executed, the `Auth` middleware's constructor will be called. If the middleware's logic dictates (in this case, if the user is not logged in), the request will be intercepted (by redirecting to the login page) and the controller method will never be reached.

**Important:** The string `'auth'` in the `middleware()` method corresponds to the name of the middleware class (e.g., `Auth` becomes `auth`).

- Controller-Level Middleware (Constructor):

```php
<?php
namespace roots\app\controllers;

use roots\app\middlewares\Auth; // Import the Auth middleware

class DashboardController
{
    public function __construct()
    {
        new Auth(); // Apply the Auth middleware to the entire controller
    }

    public function index()
    {
        // ... dashboard logic ...
    }

    // ... other controller methods ...
}
```

This approach applies the `Auth` middleware to **all** methods within the `DashboardController`. The middleware is instantiated in the controller's constructor. This is useful when you want to protect all actions within a controller.

- Controller-Level Middleware (Method-Specific):

While less common, you can apply middleware to specific methods within a controller using a similar approach as the constructor, but within the method itself. However, it's generally better practice to use route-level middleware for more granular control.

*Example Usage Scenarios:*

-  Authentication: Protecting routes that require logged-in users.
-  Authorization: Restricting access to certain resources based on user roles or permissions.
-  Input Validation: Validating user input before it reaches your controller logic.
-  CSRF Protection: Preventing Cross-Site Request Forgery attacks.
-  Logging: Logging request details for debugging or auditing.

### &#10022; Database:

The `Database` class, located at `project_directory/app/core/Database.php` under the `roots\app\core` namespace, provides a streamlined way to connect and interact with your database. It extends PHP's built-in PDO (PHP Data Objects) class, inheriting all of its powerful features while adding some helpful methods specific to the **ROOTS Framework**. Remember, you'll need to configure your database connection settings in your `config.php` file, as discussed in previous sections.

**Establishing the Connection:**

The `Database` class handles the connection process. You typically create an instance of the `Database` class, and it automatically establishes the connection based on your configuration.

*Example:*

```php
use roots\app\core\Database;

$db = new Database();
```

This single line creates a database connection using the credentials you've defined in `config.php`. The `Database` class likely handles the DSN (Data Source Name) construction and connection internally.

**Leveraging PDO:**

Because the `Database` class extends PDO, you have direct access to all of PDO's methods. This means you can use familiar PDO functions for preparing statements, executing queries, fetching results, and handling transactions.

*Example:*

```php
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Set error mode to exceptions
$statement = $db->prepare("SELECT * FROM articles"); // Prepare a SQL statement
$statement->execute(); // Execute the statement
$results = $statement->fetchAll(); // Fetch all results
echo "<pre>";
print_r($results);
echo "</pre>";
```

**Transactions:**

It is possible to utilize built-in database transaction features via `Database` class. But it has defined with `startTransaction()`, `commitTransaction()` and `rollBackTransaction()` methods.

*Example:*

```php
try {
    $db->startTransaction(); // Start a transaction

    // Perform multiple database operations here
    // ...

    $db->commitTransaction(); // Commit the transaction (all operations succeed)
} catch (\PDOException $e) {
    $db->rollBackTransaction(); // Rollback the transaction (one or more operations failed)
}
```

### &#10022; Session:

**Managing User State:**

The `Session` class, located at `project_directory/app/core/Sessions.php` under the `roots\app\core` namespace, provides a convenient way to manage user sessions in your application. It wraps PHP's built-in session functions, offering a cleaner and more structured interface. Remember that session-related configurations, such as the `session_name`, should be set in your `config.php` file.

**Initializing the Session:**

```php
use roots\app\core\Session;

Session::instance(); // to initiate and it is optional 
```

The `instance()` method initializes the session. This is typically called once at the beginning of your script, *before* any headers are sent to the browser. Calling `Session::instance()` multiple times within the same script will not restart the session. It will only start if not already started.

**Setting and Retrieving Session Data:**

```php
Session::set('test', 123);
echo Session::get('test');
```

- `Session::set('key', 'value')`: Stores a value in the session.  The `'key'` is used to identify the value, and `'value'` is the data you want to store.
- `Session::get('key')`: Retrieves a value from the session using its key. If the key doesn't exist, it will return null.

*Example:*

```php
Session::set('user_id', 42); // Store the user ID in the session
$userId = Session::get('user_id'); // Retrieve the user ID
if ($userId) {
    echo "User ID: " . $userId;
}
```

**Session ID and Regeneration:**

```php
Session::regenerateId();
echo Session::id();
```

- `Session::regenerateId()`: Generates a new session ID.  This is crucial for security, especially after a user logs in, to prevent session fixation attacks.
- `Session::id()`: Returns the current session ID.

*Example:*

```php
$oldSessionId = Session::id();
Session::regenerateId();
$newSessionId = Session::id();
echo "Old Session ID: " . $oldSessionId . "<br>";
echo "New Session ID: " . $newSessionId;
```

**Destroying the Session:**

```php
Session::destroy();
```

`Session::destroy()`: Destroys the current session. This removes all session data and effectively logs the user out. It is typically called when a user logs out or when you want to completely invalidate the session.

*Example:*

```php
Session::destroy(); // Destroy the session
echo "Session destroyed.";
```

**Setting a Custom Session ID:**

```php
$byte = random_bytes(16);
$customId = bin2hex($byte);
Session::instance($customId);
```

You can set custom session id before the session is started. This is useful for more control over session management. The ID should be a strong, randomly generated string.

*Complete Example:*

```php
use roots\app\core\Session;

Session::instance(); // Initialize the session

if (isset($_POST['login'])) {
    // ... authentication logic ...
    if ($userAuthenticated) {
        Session::set('user_id', $user->id);
        Session::regenerateId(); // Regenerate session ID after login
        return redirect('/dashboard');
    }
}

if (isset($_GET['logout'])) {
    Session::destroy();
    return redirect('/login');
}

// ... other application logic ...

$userId = Session::get('user_id');
if ($userId) {
    echo "Welcome, User " . $userId;
}
```

**Key Considerations:**

- Security: Always regenerate the session ID after user login to prevent session fixation attacks. Use strong, randomly generated session IDs.
- Configuration: Ensure your session configuration in `config.php` is correct, especially the `session_name`.
- Headers: `Session::instance()` must-be called before any headers are sent to the browser. Otherwise, session management might not work correctly.
- Usage: Sessions are typically used to store user-specific data, such as login status, user preferences, or cart contents.

### &#10022; Cookie:

The `Cookie` class, located at `project_directory/app/core/Cookies.php` under the `roots\app\core` namespace, provides a simple interface for managing cookies in your application. Cookies are small text files that are stored on the user's browser and can be used to store data like user preferences, session IDs, or tracking information.

**Setting Cookies:**

```php
Cookie::set(string $name, string $value, int $expire, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false);
```

The `set()` method allows you to create a new cookie.

- `$name`: The name of the cookie (required).
- `$value`: The value to store in the cookie (required).
- `$expire`: The expiration time of the cookie in seconds since the Unix epoch (required). Use `time() + seconds` to set a relative expiration time.
- `$path`: The path on the server where the cookie will be available (optional, defaults to the current directory).
- `$domain`: The domain for which the cookie is valid (optional).
- `$secure`: Indicates if the cookie should only be transmitted over HTTPS (optional, defaults to `false`).
- `$httpOnly`: Indicates if the cookie should only be accessible through the HTTP protocol (optional, defaults to `false`). Setting this to `true` helps mitigate XSS attacks.

*Example:*

```php
Cookie::set('user_id', '12345', time() + (30 * 24 * 60 * 60), '/', '', true, true); // Set a cookie named 'user_id' that expires in 30 days, is accessible from the root path, is only sent over HTTPS, and is HTTPOnly.
```

**Retrieving Cookies:**

```php
Cookie::get(string $name): mixed
```

The `get()` method retrieves the value of a cookie, if the key is not exist then it will return null.

- `$name`: The name of the cookie to retrieve (required).

*Example:*

```php
$userId = Cookie::get('user_id');
if ($userId) {
    echo "User ID: " . $userId;
} else {
    echo "User ID cookie not found.";
}
```

**Updating Cookies:**

```php
Cookie::update(string $name, string $value, int $expire, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false);
```

The `update()` method modifies an existing cookie. It takes the same parameters as the `set()` method. If any of the optional parameters are not provided, they will retain their original values.

- `$name`: The name of the cookie to update (required).
- `$value`: The new value of the cookie (required).
- `$expire`: The new expiration time of the cookie in seconds (required).
- `$path`: The path of the cookie (optional).
- `$domain`: The domain of the cookie (optional).
- `$secure`: Whether the cookie is secure (optional).
- `$httpOnly`: Whether the cookie is HTTP only (optional).

*Example:*

```php
Cookie::update('user_id', '54321', time() + (7 * 24 * 60 * 60)); // Update the 'user_id' cookie to a new value and expire in 7 days.
```

**Deleting Cookies:**

```php
Cookie::delete(string $name, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false);
```

The `delete()` method removes a cookie. It is crucial to use the same `$path` and `$domain` that were used when the cookie was set.

- `$name`: The name of the cookie to delete (required).
- `$path`: The path of the cookie (optional).
- `$domain`: The domain of the cookie (optional).
- `$secure`: Whether the cookie is secure (optional).
- `$httpOnly`: Whether the cookie is HTTP only (optional).

*Example:*

```php
Cookie::delete('user_id', '/', ''); // Delete the 'user_id' cookie.
```

*Complete Example:*

```php
use roots\app\core\Cookie;

// Setting a cookie
Cookie::set('my_cookie', 'some_value', time() + 3600, '/', '', true, true);

// Retrieving a cookie
$cookieValue = Cookie::get('my_cookie');
if ($cookieValue) {
    echo "Cookie value: " . $cookieValue;
}

// Updating a cookie
Cookie::update('my_cookie', 'new_value', time() + 7200);

// Deleting a cookie
Cookie::delete('my_cookie');
```

**Key Considerations:**

- Security: Be mindful of security when working with cookies. Avoid storing sensitive data directly in cookies. Use HTTPS and set the `httpOnly` flag to mitigate XSS attacks.
- Expiration: Set appropriate expiration times for your cookies. Cookies can persist across browser sessions, so consider how long you need the data to be stored.
- Path and Domain: Make sure you understand how the `path` and `domain` parameters work. They control the scope of the cookie.
- Size Limits: Cookies have size limits. Avoid storing large amounts of data in cookies.

### &#10022; Storage:

The `Storage` class, located at `project_directory/app/core/Storages.php` under the `roots\app\core` namespace, provides a less as much as simple way to interact with files within your application storage directory. It offers methods for retrieving file paths, uploading files, downloading files, and deleting files. It is important to set configuration for storage path in the configuration as seen before.

**Accessing Files:**

```php
Storage::path(string $fileTarget);
Storage::filePath(string $fileTarget);
Storage::fileSize(string $fileTarget);
```

- `Storage::path(string $fileTarget)`: Returns the absolute path to a file within the storage directory.`$fileTarget` is the relative path from your storage directory. This is useful for working with files on the server's filesystem.
- `Storage::filePath(string $fileTarget)`: Returns the full URL to a file within the storage directory. This is helpful for accessing files directly from the browser (e.g., displaying images).
- `Storage::fileSize(string $fileTarget)`: Returns the size of the file in bytes.

*Example:*

```php
$filePath = Storage::path('images/my_image.jpg'); // Get the absolute path
$fileUrl = Storage::filePath('images/my_image.jpg'); // Get the URL
$fileSize = Storage::fileSize('documents/report.pdf'); // Get the file size

if ($filePath) {
    echo "File path: " . $filePath . "<br>";
}

if ($fileUrl) {
    echo "File URL: " . $fileUrl . "<br>";
}

if ($fileSize !== false) {
    echo "File size: " . $fileSize . " bytes<br>";
}
```

**Uploading Files:**

```php
Storage::upload(array $file, string $target);
Storage::uploadAs(array $file, string $target, string $fileName);
```

- `Storage::upload(array $file, string $target)`: Uploads a file. `$file` is the file information from the `$_FILES` array. `$target` is the full path including the file name with extension and relative to the storage directory.
- `Storage::uploadAs(array $file, string $target, string $fileName)`: Uploads a file with a specified filename with extension. `$target` is the directory relative to the storage directory.

*Example:*

```php
if (Request::hasInput('image')) {
		$image = Request::input('image');
    $target = 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $image['name']; // Target path with original filename
    if (Storage::upload($image, $target)) {
        echo "File uploaded successfully!";
    } else {
        echo "File upload failed.";
    }

    $targetDirectory = 'uploads' . DIRECTORY_SEPARATOR . 'images'; // Target directory
    $newFileName = 'profile_image.jpg'; // New filename
    if (Storage::uploadAs($image, $targetDirectory, $newFileName)) {
        echo "File uploaded successfully with new name!";
    } else {
        echo "File upload failed.";
    }
}
```

**Downloading Files:**

```php
Storage::download(string $file);
```

`Storage::download(string $file)`: Downloads a file. `$file` is the path relative to the storage directory. This method will send the appropriate headers to force a download in the user's browser.

*Example:*

```php
$filePath = 'downloads' . DIRECTORY_SEPARATOR . 'reports' . DIRECTORY_SEPARATOR . 'annual_report.pdf';
if (Storage::download($filePath) === false) { // Check if headers were already sent.
    echo "Headers were already sent. Cannot download the file.";
}
// Note: The script will terminate after a successful download.
```

**Deleting Files:**

```php
Storage::unlink(string $link);
```

`Storage::unlink(string $link)`: Deletes a file. `$link` is the path relative to the storage directory.

*Example:*

```php
$filePath = 'uploads' . DIRECTORY_SEPARATOR . 'profile_image.jpg';
if (Storage::unlink($filePath)) {
    echo "File deleted successfully!";
} else {
    echo "File deletion failed.";
}
```

*Complete Example (File Upload and Download):*

```php
use roots\app\core\Request;
use roots\app\core\Storage;

if (Request::hasInput('profile_image')) {
    $targetDirectory = 'uploads' . DIRECTORY_SEPARATOR . 'profile_images';
    $newFileName = 'user_' . time() . '.jpg';
    $file = Request::input('profile_image');
    if (Storage::uploadAs($file, $targetDirectory, $newFileName)) {
        echo "Profile image uploaded successfully!";

        // Download the uploaded image:
        $filePath = $targetDirectory . DIRECTORY_SEPARATOR . $newFileName;
        Storage::download($filePath); // User will download the file.
        // The script will terminate here after successful download.
    } else {
        echo "Profile image upload failed.";
    }
}
```

**Key Considerations:**

- Security: Be very careful when allowing users to upload files. Validate file types, sizes, and names to prevent security vulnerabilities. Never store executable files in publicly accessible directories.
- File Paths: Use consistent directory separators (e.g., `DIRECTORY_SEPARATOR`) to ensure your code works across different operating systems.
- Error Handling: Always check the return values of the `Storage` methods to handle potential errors (e.g., file not found, upload failed).
- Storage Directory: Configure your storage directory properly, making sure it's writable by your web server.

### &#10022; Exception Handler:

When comes into Framework, It is important to handle errors and exceptions. Finally, log those messages for reviewing later. **ROOTS Framework** has built-in `ExceptionHandler` class. Which is used to handle almost all types of errors and exceptions in the application.

**Key Considerations:**

1. Exception Handler can be found under `project_directory/app/core/ExceptionHandler.php` with namespace 'roots\app\core'.

2. Exception Handler class might contains instance, handleException, handleError, displayDevelopmentError, displayProductionError, setErrorReporting and register methods.

3. These methods can be used for internal logic to handle and log errors and exceptions.

4. ExceptionHandler is registered in the `Main.php` under `roots/app/` namespace with the method of `ExceptionHandler::register(self::$environment);`. So it will get into the action through out the application.

5. No need to worry, almost all errors and exceptions will be caught by this handler and log them with `Logger` class.

### &#10022; Logger:

The `Logger` class, located at `project_directory/app/core/Loggers.php` under the `roots\app\core` namespace, provides different ways to log different levels of events and messages within your application. This is essential and helpful for debugging, monitoring, and auditing.

Logs are stored under configured directory. Which found in the `configuration` file. Configure log related settings for your application such as,

- Configure log path or leave default.
- Configure maximum log history to be preserve.
- Configure maximum size of log files.

**Logging methods for different levels:**

- `logDebug` method is used for detailed debugging information. Use this for messages that are only relevant during development.
```php
	logDebug(mixed $object, mixed $context = '', string $file = '', int|string $line = '');
```

- `logError` method is used for log the errors and their message that occur during application execution. These should be investigated and fixed.
```php
logError(mixed $object, mixed $context = '', string $file = '', int|string $line = '');
```

- `logInfo` method is used for log informational messages about the application state or events.
```php
logInfo(string $object, mixed $context = '', string $file = '', int|string $line = '');
```

- `logWarning` method is used for log warnings about potential issues or unusual situations. These might not be errors, but they deserve attention.
```php
logWarning(mixed $object, mixed $context = '', string $file = '', int|string $line = '');
```

**Parameters:**

- `$object`: The primary message or object to log. This can be a string, an exception object, or any other data you want to record.
- `$context`: Additional context information related to the log message. This can be an array, a string, or any other relevant data (optional).
- `$file`: The file where the log message originated (often automatically provided using `__FILE__`) (optional).
- `$line`: The line number where the log message originated (often automatically provided using `__LINE__`) (optional).

*Example Usage Logging in Action:*

```php
use roots\app\core\Logger;

try {
    // Some code that might throw an exception
    $result = 10 / 0;  // This will cause a division by zero error
} catch (\Exception $e) {
    Logger::logError("Division by zero error", $e->getTraceAsString(), __FILE__, __LINE__); // Log the exception with context
}

$user = getUserFromDatabase(123);
if ($user === null) {
    Logger::logWarning("User not found in database", null, __FILE__, __LINE__); // Log a warning
}

Logger::logInfo("User logged in", ['user_id' => 123, 'username' => 'john_doe']); // Log user login information

$data = ['name' => 'Product A', 'price' => 99.99];
Logger::logDebug("Product data", $data, __FILE__, __LINE__); // Log debug information about product data

// More examples:

$name = 'user name';
Logger::logDebug($name); // Log the user name

$e = new \Exception("Something went wrong");
Logger::logError($e); // Log the exception object directly

Logger::logInfo('File uploaded', ['filename' => 'image.jpg']); // Log file upload information

Logger::logWarning('Low disk space', ['available_space' => '10MB']); // Log a warning about disk space
```

**Logging Best Practices:**

- Use appropriate log levels: Choose the correct severity level for each message (debug, error, info, warning). This makes it easier to filter and analyze logs later.
- Provide context: Include relevant context information with your log messages otherwise method will decide. This can help you understand the circumstances surrounding the event.
- Use consistent formatting: Log messages are written in a consistent format, which is easier to read and parse.
- Log exceptions: Always log exceptions with their stack trace. This is essential for debugging.
- Separate log files: It will log separate log files for different levels of messages (e.g., error logs, debug logs).
- Rotate log files: It has built-in log rotation functionality to prevent log files from growing indefinitely. It rotates log based on the configuration specified.

### &#10022; HTACCESS:

This `.htaccess` configuration is designed to route all requests to your application's `public/index.php` file, which is a common practice for modern PHP frameworks.

1. Character Encoding:

```.htaccess
# Use UTF-8 for anything served text/plain or text/html
AddDefaultCharset utf-8

# Force UTF-8 for below file formats
AddCharset utf-8 .php .scss .css .js .json
```

- `AddDefaultCharset utf-8`: This sets the default character encoding to UTF-8 for files served with `text/plain` or `text/html` content types. This is crucial for ensuring proper display of characters from various languages.
- `AddCharset utf-8 .php .scss .css .js .json`: This specifically forces UTF-8 encoding for PHP files, SCSS files, CSS files, JavaScript files, and JSON files. This is important because these file types often contain text that needs to be displayed correctly.

**Impact on the application:**  UTF-8 encoding is essential for internationalization and preventing character encoding issues. Without these directives, you might encounter problems with displaying special characters, accented letters, or characters from non-Latin alphabets. This ensures that your application handles text correctly across different languages and character sets.

2. Directory Browsing:

```.htaccess
# Disable Directory Browsing
Options All -Indexes
```

- `Options All -Indexes`: This directive disables directory browsing. If a user tries to access a directory that doesn't contain an `index` file (e.g., `index.html`, `index.php`), they will see a "Forbidden" error instead of a listing of the directory's contents.

**Impact on the application:** Disabling directory browsing is a crucial security measure. It prevents users from accidentally discovering and accessing files that they shouldn't be able to see (e.g., configuration files, sensitive data). It protects your application from potential vulnerabilities.

3. URL Rewriting:

```.htaccess
# Target all requests to public/index.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f [OR]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.*$ public/index.php [L,QSA]
```

- `RewriteEngine On`: Enables the URL rewriting engine.
- `RewriteCond %{REQUEST_FILENAME} !-f [OR]`: This condition checks if the requested filename is not-a file.
- `RewriteCond %{REQUEST_FILENAME} !-d`: This condition checks if the requested filename is not-a directory.
- `RewriteRule ^.*$ public/index.php [L,QSA]`: This rule rewrites all-requests that are not-for existing files or directories to `public/index.php`. `[L]` means this is the last rule to be applied. `[QSA]` (Query String Append) ensures that any query string parameters are appended to the rewritten URL.

**Impact on the application:** This URL rewriting is the core of how this application likely handles routing. By directing all requests to `public/index.php`, which allows the application front controller (usually `index.php`) to bootstrap everything and handle the routing logic. This enables to use pretty URLs (URLs without `index.php`) and implement custom routing rules. Without this, the application's routing would likely break, and it would have to access your application using URLs like `public/index.php?route=/dashboard`.

**In Summary:**

This `.htaccess` file is essential for the proper functioning and security of your project. It handles character encoding, prevents directory browsing, and enables URL rewriting. These settings ensure that your application is secure, handles text correctly, and uses clean, user-friendly URLs. Modifying or removing these directives could lead to unexpected behavior, security vulnerabilities, or broken functionality.

### &#10022; ROBOTS:

`robots.txt` is a simple text file that tells search engines, which parts of your website they should and shouldn't visit. It is like a "do not disturb" sign for some areas.

**Why it is important:**

- Keeps secrets safe: It blocks search engines from seeing your admin area, code, and other private parts of your site.
- Helps search engines find the good stuff: It guides them to your public pages, images, and other important content.
- Makes your website faster: It stops search engines from wasting time on unimportant files.

**How it works:**

It uses simple rules:

- `Disallow:`  "Don't go here."
- `Allow:` "It is okay to go here."

---
[&#8682; To Top](#-documentations)

[&#8962; Goto README](README.md)