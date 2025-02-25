## Guide

Left off here: https://laravel.com/docs/11.x/passport#requesting-tokens

TODO:
- [ ] 

# Kerkalender

description

```bash
php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Set up storage symlinks
php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan storage:link
````

## OAuth2 Clients

To create a client run the following command:

```bash
php artisan passport:client
```
To add multiple redirect URLs, separate them with a comma. Note the URLs need to be URL encoded. For example: `http%3A%2F%2Fexample.com%2Fcallback,http%3A%2F%2Fexamplefoo.com%2Fcallback`.

See this article for a JSON API for the clients: https://laravel.com/docs/11.x/passport#clients-json-api

Credentials for Test Client:
```
Client ID: 9e19c665-7a09-4011-b1b9-2ebab2f7aded
Client Secret: hJRp3MsQaGVJXmrwH3bsUqHHSjTxrwdKK2NVs5Hp
Redirect URL: http%3A%2F%2Flocalhost%2Fauth%2Fcallback
```
