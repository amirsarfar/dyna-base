

<p align="center">
  <img width="500" src="http://amirsarfarazi.ir/images/Dyna-Github-Banner-2.png">
</p>
<br>

## About Dyna

Dyna is a laravel based CMS. The idea behind Dyna is developing backend of websites using api only.

## Database Schema
<p align="center">
  <img width="600" src="http://amirsarfarazi.ir/images/database-schema.png">
</p>

The goal here is to get rid of database migrations and relations. To achive that we came with an idea that was to define models (former migrations) with a JSON object.

There are 5 main tables in this project: Types, Posts, Relations, Metas and LargeMetas.

Types table is responsible for holding definition (same as migrations) of post types. It has a unique key, a tilte and a JSON definition column.

Posts table holds an id and a type_id that defines which post it is and of which type it is.

Metas table has an id, a post_id, a key and a value that defines which post it belongs to, which attribute of the post it is (key) and what its value is.

LargeMetas table is the same as Metas table but its value column is longText instead of text to hold bigger data chunks.

Relations table has a parent_key, child_key, parent_id, child_id that defines which post/posts has/have which post/posts as parent/child and which attribute of the post it belongs to (keys).

## Recommended Approach

### Create your post types

You should send a POST request to `/api/dyna/v1/types` in order to create a type.
This end-point gets three parameters :

- key : It is a unique string that is used to refrence the type.
- title : It is a string.
- definition : It is a JSON array of type's attributes. Each attribute is a JSON object.

### Type's definition

Each attribute has at least two key-value pairs: `name` and `type`.<br>
The value of the `name` key is refrenced as attribute of posts of this type.<br>
The value of the `type` key determines what kind of data is stored for this attribute.<br>
Each attribute can also take an optional key that is `options`, the `options` key has an object value that describes the attribute's extended behavior. There are some examples of `definition` objects for different types below.
<br><br> 
Supported attribute types:
- text

```javascript
{
    "name" : "<anything>",
    "type" : "text",
    "options" :
    {
        // TODO .... 
    }
}
```

- relation

```javascript
{
    "name" : "<anything>",
    "type" : "relation",
    "options" :
    {
        "type_id" : "<id of related type / if external table it should be 0>",
        "type_key" : "<key of related type / if external table it should be the table's name>",
        "count" : "<one/many>",
        "relation" : "<child/parent>"
    }
}
```

### Type creation example
For example if you have a blog_post type and a blog_post_comment type then the data you send to the create type api (POST to `/api/dyna/v1/types`) would be something like this (if you are copy-pasting, remember to remove comments in JSON below):

For the blog_post type:

```javascript
{
    "key" : "blog_post",
    "title" : "Blog Post",
    "definition" : [
        {
            "name" : "title",
            "type" : "text"
        },
        {
            "name" : "comments",
            "type" : "relation",
            "options" :
            {
                "type_id" : "2",
                "type_key" : "blog_post_comment",
                "count" : "many", // a post has many comments
                "relation" : "child" // comments are children of the post
            }
        },
        {
            "name" : "author",
            "type" : "relation",
            "options" :
            {
                "type_id" : "0", // it is an external relations (different table from posts)
                "type_key" : "users", // table of the related entity
                "count" : "one", // a post has one author
                "relation" : "parent" // author is parent of the post
            }
        }
    ]
}
```

And for the blog_post_comment type:

```javascript
{
    "key" : "blog_post_comment",
    "title" : "Blog Post Comment",
    "definition" : [
        {
            "name" : "content",
            "type" : "text"
        },
        {
            "name" : "post",
            "type" : "relation",
            "options" :
            {
                "type_id" : "1",
                "type_key" : "blog_post",
                "count" : "one", // a comment belongs to one post
                "relation" : "parent" // post is parent of comments
            }
        },
        {
            "name" : "author",
            "type" : "relation",
            "options" :
            {
                "type_id" : "0", // it is an external relations (different table from posts)
                "type_key" : "users", // table of the related entity
                "count" : "one", // a comment has one author
                "relation" : "parent" // author is parent of the comment
            }
        }
    ]
}
```

### Dynamic endpoints for types 

After requests above were successfully done, you have access to new APIs that are dynamically created based on types table data. (you can check them by running `php artisan route:list`)

New API routes for this example would be:

Blog post simple operations (these operations only contain non-relation attributes):
```bash
GET /api/dyna/v1/blog_post # to get all of blog posts
POST /api/dyna/v1/blog_post # to store a blog post
GET /api/dyna/v1/blog_post/{blog_post} # to get a blog post by id
POST /api/dyna/v1/blog_post/{blog_post} # to update a blog post
DELETE /api/dyna/v1/blog_post/{blog_post} # to delete a blog post
```

Blog post internal relations operations:
```bash
GET /api/dyna/v1/blog_post/{blog_post}/comments # to get all comments of a post
GET /api/dyna/v1/blog_post/{blog_post}/comments/{comments} # to get one comment of a post
POST /api/dyna/v1/blog_post/{blog_post}/add-comments # to add comments to the post (gets array of comments ids to add)
POST /api/dyna/v1/blog_post/{blog_post}/remove-comments # to remove comments of the post (gets array of comments ids to remove)
POST /api/dyna/v1/blog_post/{blog_post}/sync-comments # to sync comments of the post (gets array of comments ids to sync)
```

Blog post external relations operations:
Not implemented yet !


And same goes for blog post comment type.


<br><br><br>

## Installation

Simply run:
```bash
composer require amirsarfar/dyna-base
```

## Usage

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in
becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

## Contributing

Thank you for considering contributing to the Dyna CMS! The contribution guide can be found in
the [Laravel documentation](https://laravel.com/docs/contributions).

## License

The Dyna CMS is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
