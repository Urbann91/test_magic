# Quiz Application

## Getting Started

### Installation

1. **Clone the repository:**

    ```bash
    git clone <your-repo-url>
    cd <your-repo-directory>
    ```

2. **Build and start the containers:**

   Run the following command to build the Docker containers, install dependencies, and start the application:

    ```bash
    make reset
    ```

   This command will:
    - Build Docker images.
    - Start the application containers.
    - Install all dependencies (PHP, Composer, Symfony).
    - Run database migrations and load initial fixtures.

3. **Access the application:**

   After the containers are up and running, you can access the application via the following URL:

    ```
    http://localhost:8080/quiz
    ```

### Database Connection

For connecting to the PostgreSQL database from an IDE or database client, use the following connection details:

- **Host:** `localhost`
- **Port:** `5432`
- **Database name:** `postgres`
- **Username:** `symfony`
- **Password:** `symfony`

Example JDBC connection string for IDEs like IntelliJ IDEA or DataGrip:

```
jdbc:postgresql://localhost:5432/postgres
```
### Makefile Commands

- **Build and start the app:**

    ```bash
    make build
    ```

- **Run migrations and load fixtures:**

    ```bash
    make migrate
    make fixtures
    ```

### What’s left to improve

- **Due to time, the following improvements could not be implemented but are worth considering:**

	- DTO validation: Proper validation for DTOs.
	- Request validation: Proper request validation for incoming data, leveraging Symfony’s validation component.
	- Logging system: Logging mechanism to handle different log levels.
	- Unit tests: Unit tests.