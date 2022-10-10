
# Simple-Blog

This is Simple blog project using Laravel and VueJS.


## Tech Stack

**Client:** VueJS, Bootstrap 5

**Server:** PHP, Laravel


## Features
1. User
- Login
- Registration
- Create / Modify / Delete own Blog Posts
2. Admin
- Login
- Register / Modify / Delete Users
3. Blog Display
- List
- Details

## Installation

1. Clone the repository.

```bash
  git clone git@github.com:cokyudi/simple-blog.git
```
2. Switch to the repo folder.
```bash
  cd simple-blog
```

3. Install all the dependencies using composer and npm.
```bash
  composer Install
  npm install
```

4. Copy the example env file and make the required configuration changes in the .env file.
```bash
  cp .env.example .env
```

5. Create database named "simple_blog" in MySQL.

6. Run the database migrations.
```bash
  php artisan migrate
```
7. Run the database seeder.
```bash
  php artisan db:seed --class=CreateUsersSeeder
  php artisan db:seed --class=CreatePostsSeeder
  php artisan db:seed --class=CreatePostImagesSeeder
```

8. Run application.
```bash
  npm run dev
  php artisan serve
```

9. You can now access the server at http://localhost:8000
## Credentials
Please use the following credentials to login.

1. Admin.
```bash
  email : admin@gmail.com
  password : 12345678
```

2. User
```bash
  email : yudi@gmail.com
  password : 12345678
```

