Caffeine v1.0
=============

A simple PHP framework that combines modules through the use of routes and events to form an application.

Requirements
------------
* PHP 5.2
* MySQL (more databases coming soon)
* Web server (Apache, Nginx, etc) with mod rewrite

Overview
--------

### Application Flow

Below is an outline of how a typical Caffeine process works.

1. User vists application
2. Caffeine loads core modules and functionality
3. Caffeine looks for a route defined that matches the current URL
4. The module's controller associated with the matches route is loaded and its method called
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
Ctaffeine that the end of the URL must end with an id (which basically means it must be a number).

Visiting the URL `http://example.com/blog/post/hello` would return a 404. The reason is "hello" is not a valid parameter. However, 
the URL `http://example.com/blog/post/23` is valid and would result in Caffeine calling that routes controller.

Any number of parameters can be defined in a route. For example:

    photo-gallery/album/:id/photo/:id

The above URL defines two parameters. As you may have guest, the first param is the ID of the album and the second param is the photo ID. 
Parameters are passed to the routes controller method automatically. Controllers are described in more details below.

There are number of route tags that can be used when defining parameters. They are:

    :id - Matches number only
    :num - Same as :id
    :abc - Matches letters only
    :slug - Matches letters, numbers and dashes
    :any - Matches anything, including forward slashes

### Modules

TODO

### Models

TODO

### Controllers

TODO

### Views

TODO

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
