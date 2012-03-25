Caffeine 1.0.2
==============

A simple PHP framework that combines modules through the use of routes and events to form an application.

Requirements
------------
* PHP 5.2+
* MySQL/PDO (more databases coming soon)
* Web server (Apache, Nginx, etc) with mod rewrite

Overview
--------

### Application Flow

Below is an outline of how a typical Caffeine process works.

1. User vists application
2. Caffeine loads core modules and functionality
3. Caffeine looks for a route defined that matches the current URL
4. The module's controller associated with the matched route is loaded and its method called
5. The controller communicates with the model to get data as needed, and makes that data available to the view
6. Caffeine determines which view file to load, injects the view data and renders to the browser

### Routes

Routes define URL's that are associated with module controllers. When a URL is visited, Caffeine checks if a route
has been defined that matches that URL. If there is, Caffeine calls the controller and method associated with that route.
Routes can have any number of "dynamic" parameters in them by defining parameter hooks.

Example of basic route:

    blog/posts

When a users visits the url `http://example.com/blog/posts` in their browser, the above route would be called. Notice that
leading and trailing slashes are omitted.

Example of route with parameter:

    blog/post/:id

The above route works similar to the basic route, with one exception. The addition of a parameter. The :id tag tells
Caffeine that the end of the URL must end with an id (which basically means it must be a number).

Visiting the URL `http://example.com/blog/post/hello` would return a 404. The reason is "hello" is not a valid parameter. However, 
the URL `http://example.com/blog/post/23` is valid and would result in Caffeine calling that routes controller.

Any number of parameters can be defined in a route. For example:

    photo-gallery/album/:id/photo/:id

The above URL defines two parameters. As you may have guessed, the first param is the ID of the album and the second param is the photo ID. 
Parameters are passed to the routes controller method automatically. Controllers are described in more details below.

There are number of route hooks that can be used when defining parameters. They are:

    :id - Matches numbers only
    :num - Same as :id
    :abc - Matches letters only
    :slug - Matches letters, numbers and dashes
    :any - Matches anything, including forward slashes

### Modules

Modules are a collection of controllers, models and configurations that provide functionality to Caffeine. Modules can provide content such
as a Blog, or provide functionality such as Twitter OAuth.

There are 3 different locations for modules to reside. The first is the `core/` directory. This is where all required Caffeine modules are located and are always made available to all areas of your app. The second is the `modules/` directory. This is where custom modules you've written or downloaded are placed. They are also made available to all areas of your app. The last location is `sites/<site>/modules/`. This directory is used for "one-off" modules that need to be written for a specific site. Modules in this directory or only made available to that site and are not available anywhere else.

You can read more about sites below.

### Models

Models are PHP classes that represent a table in the database. They provide simple ways of storing and quering data. When installing Caffeine, the fields and indexes in a model are used to build the tables automatically.

Example of using a model to get all blog posts:

    $posts = Blog::post()->all();

Example of using a model to get a blog post by id:

    $post = Blog::post()->find($id);

Example of inserting a new blog post:

    $id = Blog::post()->insert(array(
        'title' => 'Hello World',
        'body' => 'My first blog post!'
    ));

### Controllers

TODO

### Views

Views are simply PHP files with HTML in them. Views are located in the `sites/<site>/views/` directory.

### Sites

A site directory stores your "front end" HTML and assets such as images, CSS and JavaScript. The HTML files loaded are called "Views".

Caffeine allows you to run multiple sites on a single code base. It does this by matching the current domain to a directory within `sites/`. If a a match is found, that directory will be loaded, otherwise the `sites/default/` site will be used.

For example, if we had a site `sites/foo.com` and a user visited the url `foo.com` that site directory would be loaded. However, if the same server also ran `bar.com` and a user visited that url, the `sites/default/` directory would be used instead (since `sites/bar.com` doesnt exist).

Credits
-------

Below is a list of software and frameworks that have inpsired me along the way. A lot of what I've learned from
them has been implemented into Caffeine, with my own twist.

* [CodeIgniter]('http://codeigniter.com')
* [Drupal]('http://drupal.org')
* [Ruby on Rails]('http://rubyonrails.org')
* [Laravel]('http://laravel.com')

Gavin Vickery  
<gavin@geekforbrains.com>  
<http://geekforbrains.com>  

Change Log
----------
1.0.2

- Updated Url module to use SERVER_NAME instead of HTTP_HOST to determine current host.
- Updated Log module to ensure the files and log directory is writable before creating file.

1.0.1

- Updated getBaseHref method in View module to return full url.
