# Laravel and Vue.js boilerplate

Laravel and Vue.js boilerplate. To authenticate locally use credentials below:
```
Email: admin@admin.com
Password: admin
```
## Installation

We use Laravel Sail to manage our docker containers, read
more [here](https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects).

1. Creat `.env` file
```bash
cp .env.example .env
```

2. Installing composer dependencies without have PHP or composer locally
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

3. Build the server up
```bash
./vendor/bin/sail up -d
```

4. Generate Laravel Key
```bash
   sail php artisan key:generate
```

5. Run migrations and seed the database
```bash
./vendor/bin/sail php artisan migrate:fresh --seed
```

6. Run all server side tests
```bash
sail test
```

### Frontend

Install frontend npm packages `npm install`

Run npm watch `npm run watch`

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
