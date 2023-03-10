# Venture Labs Sample Task (Topic Development)

The task was to create a small program that provides the functionality for a blog.

MySQL was chosen as the database.

## Running the code

1. Execute the `create_tables.sql` script and enter the database credentials in `Database.php`
2. Start a local PHP server `php -S localhost:8000`

## Endpoints
All endpoints return a JSON with a corresponding HTTP response.

- List of all blogs:
  - http://localhost:8000/blog.php
- Login:
  - http://localhost:8000/blog.php?action=login&user=admin&password=admin
- Create a new blog post:
  - http://localhost:8000/blog.php?action=new&text=testtext
- Create a new user:
  - http://localhost:8000/blog.php?action=new_user&username=admin&password=admin&permission=superuser&readonly=no
- Delete a blog post:
  - http://localhost:8000/blog.php?action=delete&id=1

## Explanation of the code

The most important code is located in the App folder which contains 3 classes: `Database.php`, `User.php`, and `Blog.php`.

### App/Database.php
Once this class is instantiated, a connection to a `mysql` database is realized. Tthe class contains one method, `getInstance` which returns an instance of `mysqli` class.

The database connection is closed in the destructor.

### App/User.php
This class is instantiated by passing an instance of `Database.php` class.

The class has public methods `create` and `login`.

The `create` method creates a user by setting the required GET parameters. First, the request is validated with the `validateCreateRequest` method. If the validation is successful, the user will be created with `201` HTTP response. Otherwise, a `400` HTTP response will be returned with an appropriate error message.

- `validateCreateRequest` throws an exception and `400` HTTP response will be returned if:
  - the GET parameters are not valid,
  - the user with the given username already exists.

The `login` method logs-in the user by setting session variables. First, the request is validated with the `validateLoginRequest` method. If the validation is successful, it is checked if a user with given username already exists. Finally, a new user is created with `201` HTTP response. If the validation fails or the user already exists, a `400` HTTP response will be returned with an appropriate message.

- `validateLoginRequest` throws an exception and `400` HTTP response will be returned if:
  - the GET parameters are not valid

### App/Blog.php
Similar to the `User.php` class, this class is instantiated by passing an instance of `Database.php` class.

The class has public methods `getAll`, `create`, `delete`.

The `getAll` method returns a JSON object array containing all blogs and the users who created them.

The `create` method creates a blog post of the logged-in user who has write access. First, the request is validated with the `validateCreateRequest` method. If the validation is successful, the blog post will be created with a `201` HTTP response. Otherwise, a `400` HTTP response is returned with an appropriate error message.

- `validateCreateRequest` throws an exception and `400` HTTP response will be returned if:
  - the GET parameters are not valid,
  - the user is not logged-in,
  - the user has set `readonly` to something other than `no`.

The `delete` method deletes a specific blog post from the database. Similar to the `create` method, the user must be logged in and have write access to do this. First, the request is validated with the `validateDeleteRequest` method. If the validation is successful, the blog post is deleted. Otherwise, a `400` HTTP response with an appropriate message is returned.

- `validateDeleteRequest` throws an exception and `400` HTTP response will be returned if:
  - the GET parameters are not valid,
  - the user is not logged-in,
  - the user has set `readonly` to something other than `no`,
  - the blog post with the given ID does not exist.

### includes/helpers.php
This PHP script contains 4 helper functions: `is_logged_in`, `is_read_only`, `to_json`, and `bad_request`. The functions are used throughout the code to avoid code duplication and make it more readable.

### blog.php
This script combines all the scripts and classes to deliver the functionality of a blog.

---

Along with this README file, I wrote comments throughout the code where I thought were necessary.

It's worth noting that this code is incredibly unsecure. For instance, improvements could be achieved by using a [dotenv package](https://github.com/vlucas/phpdotenv) to hide sensitive data such as database credentials and hashing the passwords instead of storing them as plaintext. Also, there is no projection mechanisms against SQL injection.