<?php

class Courses_Admin {

    public static function manage()
    {
        View::load('Courses', 'admin/courses/manage', array(
            'courses' => Courses_Model::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            $check = array('required');
            Validate::check('category_cid', 'Category', $check);
            Validate::check('name', 'Name', $check);
            Validate::check('short_desc', 'Short Description', $check);
            Validate::check('long_desc', 'Long Description', $check);
            Validate::check('what_to_bring', 'What to Bring', $check);
            Validate::check('length', 'Course Length', $check);
            Validate::check('start_date', 'Start Date', $check);
            Validate::check('end_date', 'End Date', $check);
            Validate::check('price', 'Price', $check);

            if(Validate::passed())
            {
                $data = $_POST;
                $data['start_date'] = strtotime($data['start_date']);
                $data['end_date'] = strtotime($data['end_date']);

                $data['price'] = preg_replace('/[^0-9\.]+/', '', $data['price']);

                $today = strtotime('today');
                if($data['start_date'] < $today)
                    Message::set(MSG_ERR, 'Start date cannot be in the past.');

                elseif($data['end_date'] < $today)
                    Message::set(MSG_ERR, 'End date cannot be in the past.');

                elseif($data['end_date'] < $data['start_date'])
                    Message::set(MSG_ERR, 'End date cannot be before start date.');

                elseif($cid = Courses_Model::create($data))
                {
                    Message::store(MSG_OK, 'Course created successfully.');
                    Router::redirect('admin/courses/courses/edit/' . $cid);
                }
                else
                    Message::set(MSG_ERR, 'Error creating course. Please try again.');
            }
        }

        View::load('Courses', 'admin/courses/create', array(
            'categories' => Courses_Model_Categories::get_all()
        ));
    }

    public static function edit($cid)
    {
        if(isset($_POST['update_course']))
        {
            $check = array('required');
            Validate::check('category_cid', 'Category', $check);
            Validate::check('name', 'Name', $check);
            Validate::check('short_desc', 'Short Description', $check);
            Validate::check('long_desc', 'Long Description', $check);
            Validate::check('what_to_bring', 'What to Bring', $check);
            Validate::check('length', 'Course Length', $check);
            Validate::check('start_date', 'Start Date', $check);
            Validate::check('end_date', 'End Date', $check);
            Validate::check('price', 'Price', $check);

            if(Validate::passed())
            {
                $data = $_POST;
                $data['start_date'] = strtotime($data['start_date']);
                $data['end_date'] = strtotime($data['end_date']);

                $data['price'] = preg_replace('/[^0-9\.]+/', '', $data['price']);

                $today = strtotime('today');
                if($data['start_date'] < $today)
                    Message::set(MSG_ERR, 'Start date cannot be in the past.');

                elseif($data['end_date'] < $today)
                    Message::set(MSG_ERR, 'End date cannot be in the past.');

                elseif($data['end_date'] < $data['start_date'])
                    Message::set(MSG_ERR, 'End date cannot be before start date.');

                elseif(Courses_Model::update($cid, $data))
                    Message::set(MSG_OK, 'Course updated successfully.');

                else
                    Message::set(MSG_ERR, 'Error updating course. Please try again.');
            }
        }

        if(isset($_POST['upload_photo']))
        {
            if($media_cid = Media::add('photo'))
            {
                if(Courses_Model::add_photo($cid, $media_cid))
                    Message::set(MSG_OK, 'Photo uploaded successfully.');
                else
                    Message::set(MSG_ERR, 'Error adding photo. Please try again.');
            }
            else
                Message::set(MSG_ERR, Media::error());
        }

        View::load('Courses', 'admin/courses/edit', array(
            'categories' => Courses_Model_Categories::get_all(),
            'course' => Courses_Model::get_by_cid($cid),
            'photos' => Courses_Model::get_photos($cid)
        ));
    }

    public static function delete($cid)
    {
        if(Courses_Model::delete($cid))
            Message::store(MSG_OK, 'Course deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting course. Please try again.');

        Router::redirect('admin/courses/courses/manage');
    }

    public static function delete_photo($course_cid, $media_cid)
    {
        if(Courses_Model::delete_photo($course_cid, $media_cid))
            Message::store(MSG_OK, 'Photo deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting photo. Please try again.');

        Router::redirect('admin/courses/courses/edit/' . $course_cid);
    }

}
