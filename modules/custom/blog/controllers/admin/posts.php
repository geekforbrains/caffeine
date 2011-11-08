<?php

class Blog_AdminPostsController extends Controller {

    public static function _permissions()
    {
        return array(
            'manage' => array('blog.manage_posts', 'blog.manage_my_posts'),
            'edit' => array('blog.edit_posts', 'blog.edit_my_posts')
        );
    }

    // example.com/admin/blog/posts => /admin/blog/posts/manage
    public static function _default() {
        self::manage();
    }

    public static function manage()
    {
        $rows = array();
        $posts = Blog::post()->all();

        // Build rows
        if($posts)
        {
            foreach($posts as $post)
            {
                $rows[] = array(
                    Html::a()->get($post->title, 'admin/blog/post/edit/'  $post->id),
                    date('M jS Y', $post->created_at),
                    array(
                        Html::a()->get('Delete', 'admin/blog/post/delete/' . $post->id),
                        'attributes' => array(
                            'align' => 'right'
                        )
                    )
                );
            }
        }
        else
        {
            $rows[] = array(
                array(
                    '<em>No blog posts.</em>',
                    'attributes' => array(
                        'colspan' => 3
                    )
                )
            );
        }

        // Build table and return
        return Html::table()->build(array(
            'headers' => array(
                'Title',
                array(
                    'Created On',
                    'attributes' => array(
                        'colspan' => 2
                    )
                )
            ),

            'rows' => $rows
        ));
    }

    // example.com/admin/blog/posts/create
    public static function create()
    {
        if($_POST)
        {
            if(Validate::passed())
            {
                $post = Blog::post();
                $post->user = User::current();
                $post->category = Input::post()->category_id;
                $post->title = Input::post()->title;
                $post->body = Input::post()->body;

                if($post->save())
                {
                    Message::ok('Post created successfully.');
                    Url::redirect('admin/blog/posts/manage');
                }
                else
                    Message::error('Error creating post. Please try again.');
            }
            else
                Validate::setError('Custom error instead of default');
        }

        return Html::form()->build(array(
            'category_id' => array(
                'type' => 'select',
                'title' => 'Category',
                'description' => 'Optional description',
                'validate' => array('required'),
                'options' => Blog::category()->all() // Or key value array
            ),
            'title' => array(
                'type' => 'text',
                'title' => 'Title',
                'description' => 'Optional description',
                'validate' => array( // Example of validation checks
                    'required', 
                    'is_email', 
                    'is_slug',
                    'min_len:3', 
                    'max_len:255', // after ":" is param - ex: function max_len($length) $length = 255
                    'matches:field'
                ),
                'default_value' => 'Some Value' // If select field, select option
            ),
            'body' => array(
                'type' => 'textarea',
                'title' => 'Post Body',
                'description' => 'Optional description',
                'validate' => array('required')
            )
        ));
    }

    // example.com/admin/blog/posts/edit/<id>
    public static function edit($id)
    {

    }

}
