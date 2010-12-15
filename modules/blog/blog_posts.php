<?php
class Blog_Posts extends Database {

    public static function get_all()
    {
        self::query('
            SELECT 
                p.*,
                c.name AS category
            FROM {blog_posts} p
                LEFT JOIN {blog_categories} c ON c.id = p.category_id
            ORDER BY
                p.created
            DESC
        ');
        
        return self::fetch_all();;
    }

    public static function get_all_by_category_slug($slug)
    {
        self::query('
            SELECT 
                p.*,
                c.name AS category
            FROM {blog_posts} p
                LEFT JOIN {blog_categories} c ON c.id = p.category_id
            WHERE
                c.slug LIKE %s
            ORDER BY
                p.created
            DESC
        ', $slug);
        
        return self::fetch_all();
    }

    public static function get_by_slug($slug)
    {
        self::query('SELECT * FROM {blog_posts} WHERE slug = %s', $slug);
        return self::fetch_array();
    }
    
    public static function get_by_id($id)
    {
        self::query('SELECT * FROM {blog_posts} WHERE id = %s', $id);
        return self::fetch_array();
    }
    
    public static function get_comments($slug)
    {
        self::query('
            SELECT c.*
            FROM {blog_comments} c
                LEFT JOIN {blog_posts} p ON p.id = c.post_id
            WHERE
                p.slug LIKE %s
            ORDER BY
                c.created
            ASC
        ', $slug);
        return self::fetch_all();
    }
    
    public static function create($site_id, $user_id, $category_id, $title, $content, $slug)
    {
        self::query('
            INSERT INTO {blog_posts} (
				site_id,
				user_id,
                category_id, 
                title, 
                content, 
                slug,
                created
            ) VALUES (%s, %s, %s, %s, %s, %s, %s)', 
            $site_id, $user_id, $category_id, $title, $content, $slug, time()
        );
    }
    
    public static function update($id, $title, $content, $slug)
    {
        self::query('
            UPDATE {blog_posts} SET
                title = %s,
                content = %s,
                slug = %s
            WHERE
                id = %s
            ', 
            $title, $content, $slug, $id
        );
    }
    
    public static function delete($id)
    {
        self::query('DELETE FROM {blog_posts} WHERE id = %s', $id);
    }

}
