<?php

class Media_FileController extends Controller {

    /**
     * Forces a download of the file based on the given id. If the file doesn't
     * exist, 404 is shown.
     */
    public static function download($id)
    {
        $file = Media::m('file')->find($id);

		if($file)
		{
            $filePath = Media::getFilesPath() . $file->path . $file->name;

			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $file->name);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . $file->size);

			ob_clean();
			flush();

			readfile($file_path);
			exit;
		}

		return ERROR_404;
    }


}
