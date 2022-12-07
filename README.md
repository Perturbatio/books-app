# Setup

This project uses laravel [Sail](https://github.com/laravel/sail), 
a helper for docker. 
It's a shell script located at `vendor/bin/sail`.

It isn't required, but makes running various tasks a little easier.

To add an alias for sail to your commandline, use:

`alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'`

# Project installation

Run `sail up` or `./vendor/bin/sail up` to get started
copy `.env.example` to `.env` then run `sail art key:generate` 
to update the key in the the `.env`

# database migrating and seeding

The database can be migrated with `sail art migrate` or 
`sail art migration --seed` if you want the seeded data 
to be inserted at the same time

If you want to reset the whole database and reseed it you can
do `sail art migrate:fresh --seed`

# Linting

[Pint](https://laravel.com/docs/9.x/pint#configuring-pint) 
is installed by default with Laravel 9, you can run it with:

`sail pint`

# Testing

Tests can be run with `sail test`

The scenarios outlined in the PDF have been added into
`/tests/Feature/Http/ScenariosTest.php` for convenience only
