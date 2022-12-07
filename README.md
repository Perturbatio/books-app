# Setup

This project uses laravel [Sail](https://github.com/laravel/sail), a helper for docker. 
It's a shell script located at `vendor/bin/sail`.

It isn't required, but makes running various tasks a little easier.

To add an alias for sail to your commandline, use:

`alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'`

# Project installation

Run `sail up` or `./vendor/bin/sail up` to get started


# database migrating and seeding

The database can be migrated with `sail art migrate`

If you want initial data in the database for testing manually, 
then the database must be seeded with its initial data using: `sail art db:seed`

If you want to reset the whole database and reseed it you can do `sail art migrate:fresh --seed`

# Linting

[Pint](https://laravel.com/docs/9.x/pint#configuring-pint) is installed by default with Laravel 9, you can run it with:

`sail pint`
