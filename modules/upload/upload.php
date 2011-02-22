<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Upload
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @author 1.0
 * =============================================================================
 */
class Upload {

	// Stores errors, if any
	private static $_error = null;

	// Reference to common mime types
	private static $_mime_types = array(
		'323' => 'text/h323',
		'acx' => 'application/internet-property-stream',
		'ai' => 'application/postscript',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'asf' => 'video/x-ms-asf',
		'asr' => 'video/x-ms-asf',
		'asx' => 'video/x-ms-asf',
		'au' => 'audio/basic',
		'avi' => 'video/x-msvideo',
		'axs' => 'application/olescript',
		'bas' => 'text/plain',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/octet-stream',
		'bmp' => 'image/bmp',
		'c' => 'text/plain',
		'cat' => 'application/vnd.ms-pkiseccat',
		'cdf' => 'application/x-cdf',
		'cer' => 'application/x-x509-ca-cert',
		'class' => 'application/octet-stream',
		'clp' => 'application/x-msclip',
		'cmx' => 'image/x-cmx',
		'cod' => 'image/cis-cod',
		'cpio' => 'application/x-cpio',
		'crd' => 'application/x-mscardfile',
		'crl' => 'application/pkix-crl',
		'crt' => 'application/x-x509-ca-cert',
		'csh' => 'application/x-csh',
		'css' => 'text/css',
		'dcr' => 'application/x-director',
		'der' => 'application/x-x509-ca-cert',
		'dir' => 'application/x-director',
		'dll' => 'application/x-msdownload',
		'dms' => 'application/octet-stream',
		'doc' => 'application/msword',
		'dot' => 'application/msword',
		'dvi' => 'application/x-dvi',
		'dxr' => 'application/x-director',
		'eps' => 'application/postscript',
		'etx' => 'text/x-setext',
		'evy' => 'application/envoy',
		'exe' => 'application/octet-stream',
		'fif' => 'application/fractals',
		'flr' => 'x-world/x-vrml',
		'gif' => 'image/gif',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-gzip',
		'h' => 'text/plain',
		'hdf' => 'application/x-hdf',
		'hlp' => 'application/winhlp',
		'hqx' => 'application/mac-binhex40',
		'hta' => 'application/hta',
		'htc' => 'text/x-component',
		'htm' => 'text/html',
		'html' => 'text/html',
		'htt' => 'text/webviewhtml',
		'ico' => 'image/x-icon',
		'ief' => 'image/ief',
		'iii' => 'application/x-iphone',
		'ins' => 'application/x-internet-signup',
		'isp' => 'application/x-internet-signup',
		'jfif' => 'image/pipeg',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'js' => 'application/x-javascript',
		'latex' => 'application/x-latex',
		'lha' => 'application/octet-stream',
		'lsf' => 'video/x-la-asf',
		'lsx' => 'video/x-la-asf',
		'lzh' => 'application/octet-stream',
		'm13' => 'application/x-msmediaview',
		'm14' => 'application/x-msmediaviw',
		'm3u' => 'audio/x-mpegurl',
		'man' => 'application/x-troff-man',
		'mdb' => 'application/x-msaccess',
		'me' => 'application/x-troff-me',
		'mht' => 'message/rfc822',
		'mhtml' => 'message/rfc822',
		'mid' => 'audio/mid',
		'mny' => 'application/x-msmoney',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mp2' => 'video/mpeg',
		'mp3' => 'audio/mpeg',
		'mpa' => 'video/mpeg',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'mpp' => 'application/vnd.ms-project',
		'mpv2' => 'video/mpeg',
		'ms' => 'application/x-troff-ms',
		'mvb' => 'application/x-msmediaview',
		'nws' => 'message/rfc822',
		'oda' => 'application/oda',
		'p10' => 'application/pkcs10',
		'p12' => 'application/x-pkcs12',
		'p7b' => 'application/x-pkcs7-certificates',
		'p7c' => 'application/x-pkcs7-mime',
		'p7m' => 'application/x-pkcs7-mime',
		'p7r' => 'application/x-pkcs7-certreqresp',
		'p7s' => 'application/x-pkcs7-signature',
		'pbm' => 'image/x-portable-bitmap',
		'pdf' => 'application/pdf',
		'pfx' => 'application/x-pkcs12',
		'pgm' => 'image/x-portable-graymap',
		'pko' => 'application/ynd.ms-pkipko',
		'pma' => 'application/x-perfmon',
		'pmc' => 'application/x-perfmon',
		'pml' => 'application/x-perfmon',
		'pmr' => 'application/x-perfmon',
		'pmw' => 'application/x-perfmon',
		'png' => 'image/png',
		'pnm' => 'image/x-portable-anymap',
		'pot' => 'application/vnd.ms-powerpoint',
		'ppm' => 'image/x-portable-pixmap',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'prf' => 'application/pics-rules',
		'ps' => 'application/postscript',
		'pub' => 'application/x-mspublisher',
		'qt' => 'video/quicktime',
		'ra' => 'audio/x-pn-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'ras' => 'image/x-cmu-raster',
		'rgb' => 'image/x-rgb',
		'rmi' => 'audio/mid',
		'roff' => 'application/x-troff',
		'rtf' => 'application/rtf',
		'rtx' => 'text/richtext',
		'scd' => 'application/x-msschedule',
		'sct' => 'text/scriptlet',
		'setpay' => 'application/set-payment-initiation',
		'setreg' => 'application/set-registration-initiation',
		'sh' => 'application/x-sh',
		'shar' => 'application/x-shar',
		'sit' => 'application/x-stuffit',
		'snd' => 'audio/basic',
		'spc' => 'application/x-pkcs7-certificates',
		'spl' => 'application/futuresplash',
		'src' => 'application/x-wais-source',
		'sst' => 'application/vnd.ms-pkicertstore',
		'stl' => 'application/vnd.ms-pkistl',
		'stm' => 'text/html',
		'svg' => 'image/svg+xml',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'swf' => 'application/x-shockwave-flash',
		't' => 'application/x-troff',
		'tar' => 'application/x-tar',
		'tcl' => 'application/x-tcl',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tgz' => 'application/x-compressed',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'tr' => 'application/x-troff',
		'trm' => 'application/x-msterminal',
		'tsv' => 'text/tab-separated-values',
		'txt' => 'text/plain',
		'uls' => 'text/iuls',
		'ustar' => 'application/x-ustar',
		'vcf' => 'text/x-vcard',
		'vrml' => 'x-world/x-vrml',
		'wav' => 'audio/x-wav',
		'wcm' => 'application/vnd.ms-works',
		'wdb' => 'application/vnd.ms-works',
		'wks' => 'application/vnd.ms-works',
		'wmf' => 'application/x-msmetafile',
		'wps' => 'application/vnd.ms-works',
		'wri' => 'application/x-mswrite',
		'wrl' => 'x-world/x-vrml',
		'wrz' => 'x-world/x-vrml',
		'xaf' => 'x-world/x-vrml',
		'xbm' => 'image/x-xbitmap',
		'xla' => 'application/vnd.ms-excel',
		'xlc' => 'application/vnd.ms-excel',
		'xlm' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlt' => 'application/vnd.ms-excel',
		'xlw' => 'application/vnd.ms-excel',
		'xof' => 'x-world/x-vrml',
		'xpm' => 'image/x-xpixmap',
		'xwd' => 'image/x-xwindowdump',
		'z' => 'application/x-compress',
		'zip' => 'application/zip'
	);

	/**
	 * -------------------------------------------------------------------------
	 * Determines the full upload path for a given file array.
	 *
	 * Can determine the full path if hash is given, or just the upload
	 * upload directory if the file path is only given.
	 * -------------------------------------------------------------------------
	 */
	public static function path($file_path, $file_hash = null) {
		return Caffeine::files_path() . UPLOAD_DIR . $file_path . $file_hash;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function error() {
		return self::$_error;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used for saving uploaded files to the "uploads" directory determined by
	 * the current site.
	 *
	 * @param $file 
	 *		The $_FILE array of the file to be uploaded.
	 *
	 * @return mixed
	 *		If the upload was successful, the file name and path is returned
	 *		in an array. Otherwise boolean false is returned.
	 * ------------------------------------------------------------------------
	 */
	public static function save($file)
	{
		if($file['error'] > 0)
		{
			self::$_error = self::_set_error($file['error']);
			return false;
		}
		
		$upload_path = self::_determine_upload_path();

		if($upload_path)
		{
			$file_hash = md5(uniqid($file['tmp_name'], true));
			$file_path = $upload_path; // Relative upload path YYYY/MM/<hash>

			// If couldn't determine file type, make plain
			if(!strlen($file['type']))
				$file['type'] = 'text/plain';
			
			if(move_uploaded_file($file['tmp_name'], self::path($file_path, $file_hash)))
			{
				return array(
					'name' => $file['name'],
					'hash' => $file_hash,
					'path' => $file_path,
					'type' => $file['type'],
					'size' => $file['size']
				);
			}
			else
				self::$_error = 'There was an error uploading the file.
					Please try again.';
		}

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Uploads and extracts a zip file and returns its file contents.
     *
	 * @param $file
	 *		The zip file uploaded, contained in the $_FILES array.
	 *
	 * @return
	 *		If the upload and unzip is successful, a multi-dimensional array
	 *		of files contained within the zip file are returned. Otherwise
	 *		boolean false is returned.
	 *
	 * TODO: Handle sub-direcotires
	 * -------------------------------------------------------------------------
	 */
	public static function unzip($file)
	{
		$file = self::save($file);

		if($file)
		{
			$zipfiles = array();

			// Check if zip functionality exists
			if(!class_exists('ZipArchive'))
			{
				self::$_error = 'Error unzipping archive. No zip library installed.';
				return false;
			}

			$za = new ZipArchive();
			$za->open(self::path($file['path'], $file['hash']));
			$za->extractTo(self::path($file['path'])); // Extract zip files to same path as zip
			
			for($i = 0; $i < $za->numFiles; $i++)
			{
				$zf = $za->statIndex($i);
				//$zf_info = new finfo(FILEINFO_MIME);

				$zfpath = self::path($file['path'], $zf['name']);
				$zfhash = md5_file($zfpath);
				$zfhashpath = self::path($file['path'], $zfhash);

				$zipfiles[] = array(
					'name' => $zf['name'],
					'hash' => $zfhash,
					'path' => $file['path'],
					'type' => self::_determine_mime_type($zfpath),
					'size' => filesize($zfpath)
				);

				// Rename file to hash
				rename($zfpath, self::path($file['path'], $zfhash));
			}

			$file['files'] = $zipfiles;
			return $file;
		}
		else
			return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Determines a files mime type based on its extension.
	 *
	 * @param $path
	 *		The filename or path that you want a mime type for.
	 *
	 * @return string
	 *		Returns the mime type for the given file
	 * -------------------------------------------------------------------------
	 */
	private static function _determine_mime_type($path)
	{
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		if(isset(self::$_mime_types[$ext]))
			return self::$_mime_types[$ext];

		return 'application/octet-stream';
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	private static function _determine_upload_path()
	{
		$upload_path = Caffeine::files_path() . UPLOAD_DIR;

		// Make sure the uploads dir exists
		if(!file_exists($upload_path))
			if(!mkdir($upload_path))
				die('Unable to create uploads directory: ' . $upload_path);

		// Make sure uploads is writable
		if(!is_writable($upload_path))
			die('The uploads directory isn\'t writable: ' . $upload_path);

		// Set path into current year/month sub directory
		$y = date('Y');
		$m = date('m');

		// Make sure year dir exists
		if(!file_exists($upload_path . $y))
			mkdir($upload_path . $y);

		// Make sure month dir exists
		if(!file_exists($upload_path . $y . '/' . $m))
			mkdir($upload_path . $y . '/' . $m);

		// Only return relative path, incase site name changes etc.
		return $y . '/' .$m . '/';
	}

	/**
	 * -------------------------------------------------------------------------
	 * Sets error messages based on PHP error constants.
	 * -------------------------------------------------------------------------
	 */
	private static function _set_error($error)
	{
		switch($error)
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the maximum size.';

			case UPLOAD_ERR_PARTIAL:
				return 'The file was only partially uploaded.';

			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded.';

			case UPLOAD_ERR_NO_TMP_DIR:
				return 'No temporary directory exists for file uploads.';

			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write upload to disk.';

			default:
				return 'Unkown upload error.';
		}
	}

}
