# ArticleHub API
ArticleHub API is a RESTful API  service built with Laravel that allows for comprehensive user authentication and article management. The API supports creating, reading, updating, and deleting articles, along with user registration and login functionalities using JWT authentication. The project also includes API versioning to demonstrate how to manage different versions of an API.


## Features

- User registration and login with JWT authentication
- Create, read, update, and delete articles
- Search articles by title
- API versioning (V1 and V2)

## Technologies Used

- PHP
- Laravel
- Sanctum (for authentication)
- Carbon (for date manipulation)


## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/articlehub-api.git
    cd articlehub-api
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Generate the application key:

    ```bash
    php artisan key:generate
    ```

5. Configure your database settings in the `.env` file:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

6. Run the database migrations:

    ```bash
    php artisan migrate
    ```

7. (Optional) Seed the database with initial data:

    ```bash
    php artisan db:seed
    ```

8. Serve the application:

    ```bash
    php artisan serve
    ```

## API Endpoints

### Authentication

- **Register**: `POST /register`
- **Login**: `POST /login`
- **Logout**: `POST /logout` (requires authentication)

### Articles (API V1)

- **List Articles**: `GET /V1/ArticleHub/ListOfArticles`
- **Store Article**: `POST /V1/ArticleHub/StoreArticle`
- **Show Article**: `GET /V1/ArticleHub/ShowArticle/{id}`
- **Update Article**: `PUT /V1/ArticleHub/UpdateArticle/{id}`
- **Delete Article**: `DELETE /V1/ArticleHub/DeleteArticle/{id}`
- **Search Articles**: `GET /V1/ArticleHub/Search`

### Articles (API V2)

- **List Articles**: `GET /V2/ArticleHub/ListOfArticles`
- **Resource Routes**: `V2/ArticleHub/ListOfArticles/Resources`

