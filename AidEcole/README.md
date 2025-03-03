<p align="center"><img alt="symfony logo" src="https://symfony.com/images/logos/header-logo.svg"></p>

Symfony  Application
========================

The "Symfony  Application" is a reference application created to show how
to develop applications following the [Symfony Best Practices][1].

You can also learn about these practices in [the official Symfony Book][5].




# **Installation**


```bash
git clone https://github.com/azerdachraoui00/AidEcole.git  my_project
cd my_project/

```



# **SETUP**
1 - Install all dependencies :

~~~
    composer install
~~~


2 - Create database using the next command:
~~~
    php bin/console doctrine:schema:create
~~~


3 - You will need to populate your database using fixtures for login.

Run:

~~~
    php bin/console doctrine:fixtures:load
~~~



There's no need to configure anything before running the application. There are
2 different ways of running this application depending on your needs:

**Option 1.** [Download Symfony CLI][4] and run this command:

```bash
symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

**Option 2.** Use a web server like Nginx or Apache to run the application
(read the documentation about [configuring a web server for Symfony][3]).

On your local machine, you can run this command to use the built-in PHP web server:

```bash
php -S localhost:8000 -t public/
```

