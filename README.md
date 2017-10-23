Yet another Sandbox
====

Project was created to play with Symfony3 framework basic behavior.

The original task

```text
Blog application

Develop a Blog in Symfony framework
Private Part

    Private part is accessible after successful login using username and password
        Registration and creating new administrators is not possible, so there will be at least one predefined account
    Administrator can create/edit blog posts. Each blog post has
        Title - required short text, up to 150 chars
        Text - required  wysiwyg content
        Date - required
        Tags - can have multiple tags
        Url - unique

    Administrator can disable(hide) blog post. It cannot be deleted
        Administrator still sees the disabled blog post, but it is not accessible for public users.
        Can be re-enabled
    Administrator can see the number of views for each blog post

Public part
    Shows paginated list of blog posts
        Ordered by date from the latest to oldest
        Two records per page
        Shows title and date
    Every blog post has a detail page with unique URL
        Adds +1 to blog post views

    REST API with at least two endpoints :
        1. Returns the full list of blog posts without textual content and tags
        2. Returns the detail of single blog post including textual content and tags
            Adds +1 to blog post views

General Requirements
    Symfony framework 3.x
    SQL or NOSQL database
    PHP 7.x
    Create a github public repository and send the link
    Include README with the full build process described or any automatic build solution
    Use Composer
```

## Installation

Simple installation

```bash
$ git clone https://github.com/pavlobalandin/blog.git
$ cd blog
$ composer install

$ chmod 777 var/logs
$ chmod 777 var/cache
$ chmod 777 var/sessions
```

## Database options

Copy database options from app\config\parameters.yml.dist to app\config\parameters.yml

Update database connection options according to host configuration.

Create access to local database according to the configuration above.

```bash
mysql -u root -p localhost
```

```mysql
create database blog_demo;
grant all privileges on blog_demo.* to 'blog'@'%' identified by 'blog';
flush privileges;
```

## Create default tables

```bash
$ php bin/console doctrine:schema:update --force
```

## Launch application

```bash
php bin/console server:run 0.0.0.0:8000
```

Now open http://localhost:8000/login

## Logging in as Admin

user: admin, password: admin

User is created in database automatically on the first launch.