# Laravel SSO Implementation

This project demonstrates a Single Sign-On (SSO) implementation using Laravel. It consists of two main components:

1. **SSO Authentication Server** (sso-auth-server/)
2. **SSO Client Application** (sso-client-app/)

## Features

- Centralized authentication using Laravel Passport for OAuth2
- Single sign-on across multiple Laravel applications
- Secure token-based authentication
- User registration and profile management
- Token verification and validation
- Token revocation and expiry

## Installation

### Prerequisites

- PHP 8.2+
- Composer
- Node.js (for asset compilation)
- SQLite or MySQL database

### Steps

1. Clone the repository:
   ```
   git clone https://github.com/vinayaksandilya/Laravel-SSO.git
   cd laravel-sso
   ```

2. Set up the authentication server:
   ```
   cd sso-auth-server
   cp .env.example .env
   composer install
   php artisan key:generate
   php artisan migrate
   php artisan passport:install
   php artisan serve
   ```

3. Set up the client application:
   ```
   cd ../sso-client-app
   cp .env.example .env
   composer install
   php artisan key:generate
   php artisan migrate
   php artisan serve
   ```

4. Configure client application:
   - Update the client application's `.env` file with the SSO server URL
   - Update the `config/services.php` file with SSO client credentials

5. Create an SSO client in the authentication server:
   - Access the authentication server at `http://sso-auth-server.test`
   - Register a new user if needed
   - Log in and create a new client from the dashboard

6. Access the client application and test SSO:
   - Go to `http://sso-client-app.test`
   - Click "SSO Login" to be redirected to the SSO server
   - Log in or register a new account
   - Upon successful authentication, you'll be redirected back to the client application

## Directory Structure

- `sso-auth-server/`: Contains the SSO authentication server built with Laravel and Laravel Passport
- `sso-client-app/`: Contains a sample client application that uses the SSO server for authentication
- `index.md`: This README file

## Configuration

### SSO Server (.env)
- `APP_URL`: URL of the SSO server
- `DB_DATABASE`: Database for SSO server (SQLite or MySQL)

### SSO Client (.env)
- `SSO_BASE_URL`: URL of the SSO server
- `SSO_CLIENT_ID`: Client ID from SSO server
- `SSO_CLIENT_SECRET`: Client secret from SSO server
- `SSO_REDIRECT_URI`: Redirect URI for OAuth2 callbacks

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[Licensed under MIT](LICENSE)

