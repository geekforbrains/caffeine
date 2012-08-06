Usage:
    caffeine db install
    caffeine db update
    caffeine db seed
    caffeine db optimize

Description:
    install: 
        Will erase any tables in the database and re-create all model tables.
        If any tables already exist, you will be prompt to continue or cancel.
    
    update:
        Updates any tables whos models have changed and creates any new model
        tables.

    seed:
        Installs "test" data into your database from the projects seed.php file.

    optimize:
        Runs a SQL optimize command on each table in the database.
