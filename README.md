# Code Hub

Code Hub is a PHP-based collaborative code-snippet sharing platform for
developers, students, and programmers. It is designed to help users
publish, discover, organize, and learn from code snippets in multiple
programming languages.

The uploaded pages describe support for PHP, Python, Java, C++, HTML,
CSS, and JavaScript. The dashboard includes user-specific features,
snippet browsing, search, and a code-running request flow.

## Features

Based on the supplied PHP files, the project includes or is designed for
the following:

-   **Landing page:** Introduces Code Hub and links to registration and
    other information pages.
-   **About and Info pages:** Explain the purpose and benefits of the
    platform.
-   **Session-aware navigation:** Shows different dashboard actions
    depending on whether a user is signed in.
-   **User dashboard:** Displays the signed-in user's name, email, and
    membership date.
-   **Snippet feed:** Lists snippets with their title, author,
    language/code display, and creation date.
-   **Snippet management links:** Provides links to add, view, edit,
    search, and bookmark snippets.
-   **Popular and recent snippets:** Shows snippets ordered by likes and
    creation date.
-   **Global code search:** Integrates Google Custom Search.
-   **Syntax highlighting:** Uses Prism.js.
-   **Code execution request:** Sends code to a PHP endpoint
    (`snippets/run_code.php`) for execution through the configured
    service.
-   **Profile sidebar:** Offers profile details, bookmarks, settings,
    security, and logout links.
-   **Database access:** Uses MySQL through a connection file referenced
    as `includes/db.php`.



## Technologies

-   **PHP** --- server-side pages and session handling
-   **MySQL** --- user and snippet data
-   **HTML5** --- page structure
-   **CSS3** --- styling, layouts, transitions, and animations
-   **JavaScript** --- interactive profile sidebar and code-running
    requests
-   **XAMPP / Apache** --- local development server
-   **Prism.js** --- code syntax highlighting
-   **Google Custom Search** --- global web/code search integration
-   **JDoodle API test file** --- `test_jdoodle.php` demonstrates a
    separate JDoodle execution request

## Project Structure

The following is a representative structure based on the screenshot and
uploaded files. Not every referenced file was included in the upload.

``` text
CODEHUB/
├── admin/                    # Admin-related pages (referenced)
├── assets/                   # Images and other static assets (referenced)
├── auth/                     # Login, registration, and logout (referenced)
├── includes/
│   └── db.php                # Database connection (referenced)
├── snippets/                 # Snippet create/view/edit/search/run pages (referenced)
├── user/                     # User dashboard/settings/security (referenced)
├── index.php                 # Public landing page
├── about.php                 # About page
├── info.php                  # Information page
├── dashboard.php             # Signed-in user dashboard
├── test_jdoodle.php          # JDoodle API test example
├── default.png               # Default profile image
└── README.md                 # Project documentation
```

## Requirements

-   Windows, macOS, or Linux
-   PHP and Apache (XAMPP is a convenient local option on Windows)
-   MySQL/MariaDB if using database-backed features
-   A modern web browser
-   Internet access for external resources such as Google Fonts, Font
    Awesome, Prism CDN, Google Custom Search, and any external code
    execution service

## Run Locally with XAMPP

### 1. Put the project in `htdocs`

Copy the complete project folder into:

``` text
C:\xampp\htdocs\CODEHUB
```

Make sure the `auth`, `includes`, `snippets`, `user`, `admin`, and
`assets` directories are present if the application uses them.

### 2. Start Apache and MySQL

1.  Open **XAMPP Control Panel**.
2.  Start **Apache**.
3.  Start **MySQL** for database-backed features.

### 3. Configure the database

1.  Open `http://localhost/phpmyadmin/`.
2.  Create the database expected by the project, or import the supplied
    SQL export if one exists.
3.  Check `includes/db.php` and set the database host, name, username,
    and password for your local setup.
4.  Confirm that the database contains the tables and columns used by
    the PHP pages, including `users` and `snippets`.

The uploaded `dashboard.php` queries the `users` table for `id`,
`username`, `email`, and `created_at`, and the `snippets` table for
fields including `user_id`, `title`, `language`, `code`, `likes`, and
`created_at`. Other pages may require additional tables or columns.

### 4. Open Code Hub

Visit:

``` text
http://localhost/CODEHUB/
```

To open the dashboard directly, use:

``` text
http://localhost/CODEHUB/dashboard.php
```

The dashboard redirects to `auth/login.php` when there is no active user
session. If the project uses a different directory layout, adjust the
URL and links accordingly.

## Configuration Notes

### Database

Database credentials belong in the local database configuration file. Do
not publish real passwords or credentials.

### JDoodle test

`test_jdoodle.php` contains placeholder values for `clientId` and
`clientSecret`. Replace them with valid credentials only in a private
local configuration before testing. Do not commit API secrets to a
public repository. The test also requires PHP cURL support and a working
internet connection.

### Google Custom Search

The dashboard contains a Google Custom Search Engine identifier in its
script URL. Confirm that the identifier and search engine settings
belong to your project before deployment.

### Code execution

The dashboard sends code to `snippets/run_code.php`. The endpoint was
not included in the uploaded files, so its implementation and execution
provider could not be verified. Never execute untrusted user-submitted
code directly on your web server; use a properly isolated sandbox and
apply resource limits.

## Troubleshooting

### Apache or MySQL will not start

-   Check the XAMPP Control Panel for error messages.
-   Verify that another service is not using the required ports.
-   Review the Apache or MySQL logs.

### `404 Not Found`

-   Check that the project folder is named `CODEHUB` and is inside
    `htdocs`.
-   Confirm that the requested PHP file exists.
-   Open `http://localhost/CODEHUB/index.php` directly.

### Database connection or SQL error

-   Ensure MySQL is running.
-   Verify the settings in `includes/db.php`.
-   Confirm the database and required tables exist.
-   Compare the database schema with the columns queried in
    `dashboard.php`.

### Login redirect or missing page

-   The dashboard requires a logged-in session and redirects to
    `auth/login.php`.
-   Confirm the authentication files are present and links point to the
    correct locations.
-   The uploaded `index.php` links to `auth/register.php`, while
    `about.php` links to `login.php` at the project root. Check the
    actual project structure and update inconsistent links if needed.

### Missing images or styling

-   Confirm the referenced files exist in `assets/` and that
    `default.png` is in the location expected by `dashboard.php`.
-   External CDN resources require internet access.

## Security Checklist Before Deployment

-   Use prepared statements for database queries, especially queries
    containing user input.
-   Validate and escape output to reduce cross-site scripting risks.
-   Protect state-changing actions against CSRF.
-   Store secrets outside public source control.
-   Enforce authorization checks on admin and user-specific pages.
-   Secure sessions and password handling.
-   Run user-submitted code only in a properly isolated environment.
-   Review third-party API use, rate limits, and error handling.
