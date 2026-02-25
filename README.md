# Money Tracker API

A simple REST API built with Laravel for tracking money across multiple wallets per user. The API code is located in the `api` folder.

## Features

- **User Management**: Create users and view their profile with overall balance.
- **Wallet Management**: Create multiple wallets for a user.
- **Transaction Management**: Add income and expenses to specific wallets.
- **Auto-Calculating Balance**: Wallet and user balances are updated automatically when transactions are recorded.
- **ACID Compliant**: Uses database transactions for atomic operations.

## Setup Instructions

1. **Navigate to the API directory**:

   ```bash
   cd api
   ```

2. **Install Dependencies**:

   ```bash
   composer install
   ```

3. **Environment Configuration**:
   The project uses SQLite by default. Ensure the database path exists:

   ```bash
   touch database/database.sqlite
   ```

4. **Run Migrations**:

   ```bash
   php artisan migrate
   ```

5. **Start the Server**:
   ```bash
   php artisan serve
   ```

## API Endpoints

(All endpoints are prefixed with `/api`)

### Users

- `POST /api/users`: Create a new user.
  - Body example:
    ```json
    {
      "name": "Jane Doe",
      "email": "jane@example.com",
      "password": "password123"
    }
    ```
  - **Successful response (201):**
    ```json
    {
      "id": 1,
      "name": "Jane Doe",
      "email": "jane@example.com",
      "created_at": "2026-02-25T13:00:00.000000Z",
      "updated_at": "2026-02-25T13:00:00.000000Z"
    }
    ```
  - **Validation error (422):**
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "email": ["The email field is required."]
      }
    }
    ```
- `GET /api/users/{id}`: View user profile, wallets, and overall balance.

### Wallets

- `POST /api/wallets`: Create a new wallet.
  - Body: `{"user_id": 1, "name": "Work"}`
- `GET /api/wallets/{id}`: View wallet balance and transaction history.

### Transactions

- `POST /api/transactions`: Record a transaction.
  - Body: `{"wallet_id": 1, "amount": 100, "type": "income", "description": "..."}`
  - `type` must be either `income` or `expense`.

## Running Tests

Run the feature tests to verify all functionality:

```bash
cd api
php artisan test
```
