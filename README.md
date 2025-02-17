# ROOTS FRAMEWORK

**Simple as roots, Strong as foundation...**

One of the simplest MVC architecture based framework. Which is made on & for PHP. Who wants to test and start PHP based project with MVC architecture.

The concept and purpose of this framework is minimal and robust rather than bulky and fully loaded.

*The framework is independent of any third-party libraries, packages and plug-ins.*

## &#9780; Overview:
1. [Installation](#-installation)
2. [Documentations](#-documentations)
3. [Contribution](#-contribution)
4. [Disclaimer](#-disclaimer)
5. [License](#-license)
6. [Contact](#-contact)

## &#9873; Installation:

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

## &#9873; Documentations:

For project documentations, Refer [DOCUMENTATIONS](DOCUMENTATIONS.md).

## &#9873; Contribution
Contributions are welcome! If you have any suggestions, bug reports, or feature requests, please open an issue or submit a pull request. Make sure to follow the existing coding style and provide clear documentation for your changes.

## &#9873; Disclaimer
This is a simple MVC architecture based PHP framework. It is developed for simplicity and utilize MVC architecture in PHP projects. Consult with experts, double check, test and verify the code that fits and works for your application logics, Before proceed in real-time implementations. This project will not give any claim and/or warranty for damages or loses by using this project in any of the form and in any of the versions.

## &#9873; License
This reference licensed under the [MIT license](LICENSE). Feel free to use, modify, and distribute it as per the terms of the license.

## &#9873; Contact
If you have any questions or need further assistance, please feel free to reach me by referring [My Github Profile](https://github.com/ag-sanjjeev/)

Thanks for reviewing this project!
