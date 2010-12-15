<?php
class Blog_Categories extends Database {

    public static function get_all()
    {
        self::query('SELECT * FROM {blog_categories} ORDER BY name ASC');
        return self::fetch_all();
    }
    
    public static function get_by_id($id)
    {
        self::query('SELECT * FROM {blog_categories} WHERE id = %s', $id);
        return self::fetch_array();
    }
    
    public static function get_by_slug($slug)
    {
        self::query('SELECT * FROM {blog_categories} WHERE slug like %s',
            $slug);
        return self::fetch_array();
    }
    
    public static function exists($name)
    {   
        self::query('SELECT id FROM {blog_categories} WHERE name LIKE %s', $name);
        if(self::num_rows() > 0)
            return true;
        return false;
    }
    
    public static function create($name, $slug)
    {
        self::query('INSERT INTO {blog_categories} (name, slug) VALUES (%s, %s)', 
            $name, $slug);
    }
    
    public static function delete($id)
    {
        self::query('DELETE FROM {blog_categories} WHERE id = %s', $id);
    }
    
    public static function update($id, $name, $slug)
    {
        self::query('
            UPDATE {blog_categories} SET
                name = %s,
                slug = %s
            WHERE
                id = %s
            ',
            $name, $slug, $id
        );
    }

}
