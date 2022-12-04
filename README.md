# Setup

This project uses laravel [Sail](https://github.com/laravel/sail), a helper for docker. 
You can install sail using the [instructions](https://laravel.com/docs/9.x/sail) provided on the laravel website

# Project installation

Run `sail up` to get started

# database seeding

The database must be seeded with its initial data using: `sail art db:seed`

# Linting

[Pint](https://laravel.com/docs/9.x/pint#configuring-pint) is installed by default with Laravel 9, you can run it with:

`sail pint`
