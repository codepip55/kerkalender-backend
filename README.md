## Guide

Left off here: https://laravel.com/docs/11.x/passport#requesting-tokens

TODO:
- [ ] Update migrations for new models - https://chatgpt.com/c/67cb6589-3904-8004-87ff-2c40f9493c8f

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
Client ID: 9e8c0aee-cf78-4600-8d54-6009b49ced99
Client Secret: B0XqxyJUYHM2EdWef2svwSxFqgwnvj0Lj6Esv7Hf
Redirect URL: http%3A%2F%2Flocalhost%2Fauth%2Fcallback
```
