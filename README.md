# Pre-employment Exam Submission

##### Requirement:

- WSL with Debian Distribution

Assuming that you already installed and logon in your WSL Debian.
Lets start installing PHP, necessary extensions and linux packages.

```bash
sudo apt install -y php8.2 php-dom php-zip php-curl php-mbstring php-cli unzip wget
```

#### Installing composer

```bash
# download the installer
sudo wget -O composer-setup.php https://getcomposer.org/installer
# install the composer globally
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
# check composer
composer -v
```

Start by cloning the Git repository

```bash
git clone https://github.com/hmc-au/alvin-test.git
```

###### File and Directory structure (Preview)

```
.
└── alvin-test/
    ├── app (laravel)
    ├── vue-webapp (vue js)
    ├── .env
    ├── docker-compose.yml
    └── README.md
```

Change to the project directory.

```bash
 cd alvin-test
```

##### Setup Laravel .env and Installing Packages

First, Setup Environment File

```bash
cp app/.env.prod app/.env
```

then Install laravel packages with `composer`

```bash
cd app/ && composer install && cd ../
```

#### Setup Docker Containers

Ensure that your Docker service is running

```bash
sudo service docker start
sudo service --status-all
```

Now that the Laravel codebase is ready, we can build and run the necessary containers for MySQL, Laravel, and Vue.js.

```bash
docker-compose up -d
```

Confirm that the Vue.js, Laravel, and MySQL containers are running.

```bash
docker-compose ps
```

### Laravel Key and Migration

We need to generate a unique and secure application key. This key is used for various security-related purposes within a Laravel application.

```bash
docker-compose exec laravel php artisan key:generate
```

Great! Now let's run the Laravel migration.

```bash
docker-compose exec laravel php artisan migrate --force
```

as you can see, we have `--force` flag in our command, it is because the our laravel Environment has been setup for production same goes with Vue app

#### Unit Test

Let's run tests to ensure all functionalities are working.

```
docker-compose exec laravel php artisan test
```

#### Seeder

```bash
docker-compose exec laravel php artisan db:seed --force
```

### Finally

Now, in your browser, visit the Vue.js web app by opening this link: http://localhost:8080 <br>
You can register or use the default user:<br>
Email: hmcuser@hmc.com <br>
Password: pass1234 <br>
