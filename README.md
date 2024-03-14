## Undabot Php task

- .env is provided in root of the project, please change credentials to yours (DB_DATABASE=, DB_USERNAME=, DB_PASSWORD=).
- Also postmen collection is provided in root of the project (undabot.postman_collection.json).

Please run next commands in projects root directory to get you started:

- first clone the git repo
- run composer install
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate

### Project route: 
```sh
- project has one GET route: /score?term=test it accepts one param named "term=" with max 255 characters, and maximum of 10 requests per minute
```

