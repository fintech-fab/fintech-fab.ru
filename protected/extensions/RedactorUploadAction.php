<?php
/**
 * Action to handle image uploads from imperavi-redactor-widget
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 *
 * For examples see image_upload_readme.md
 */
class RedactorUploadAction extends CAction
{
	/**
	 * Path to directory where to save uploaded files(relative path from webroot)
	 * Should be either string or callback that will return it
	 *
	 * @var string
	 */
	public $directory;
	/**
	 * @var string путь к директории для уменьшенных копий изображений
	 */
	public $thumb_directory;

	/**
	 * Callback for function to implement own saving mechanism
	 * The only argument passed to callback is CUploadedFile, callback should return url to file
	 *
	 * @var callable
	 */
	public $saveCallback;

	private $_validator = array( // default options
	);

	public function getValidator()
	{
		return $this->_validator;
	}

	public function setValidator($v)
	{
		$this->_validator = array_merge($this->_validator, $v);
	}

	/**
	 * Function used to save image by default
	 *
	 * @param CUploadedFile $file
	 *
	 * @return string[] url to uploaded file and file name to insert in redactor by default
	 * @throws CException
	 */
	public function save($file)
	{
		if (is_string($this->directory)) {
			$dir = $this->directory;
		} elseif (is_callable($this->directory, true)) {
			$dir = call_user_func($this->directory);
		} else {
			throw new CException(Yii::t('imperavi-redactor-widget.main', '$directory property, should be either string or callable'));
		}
		if (is_string($this->thumb_directory)) {
			$thumbDir = $this->thumb_directory;
		} elseif (is_callable($this->thumb_directory, true)) {
			$thumbDir = call_user_func($this->thumb_directory);
		} else {
			throw new CException(Yii::t('imperavi-redactor-widget.main', '$thumb_directory property, should be either string or callable'));
		}

		$webroot = Yii::getPathOfAlias('webroot');

		$id = time();
		$sub = substr($id, -2);
		$id = substr($id, 0, -2);
		$dstDir = '/' . $dir . '/' . $sub . '/';
		$dstThumbDir = '/' . $thumbDir . '/' . $sub . '/';
		if (!is_dir($webroot . $dstDir)) {
			mkdir($webroot . $dstDir, 0775, true);
		}
		if (!is_dir($webroot . $dstThumbDir)) {
			mkdir($webroot . $dstThumbDir, 0775, true);
		}

		$ext = $file->getExtensionName();
		$name = trim($file->name);

		if (strlen($ext) > 0) {
			$name = substr($name, 0, -1 - strlen($ext));
		}

		// удаление повторных пробелов
		$name = preg_replace('/\s\s+/', '_', $name);

		// если в имени файла есть запрещённые символы, то заменяем имя на хэш
		if (preg_match("/^[a-z0-9\-_]+$/ui", $name) == 0) {
			$name = substr(md5($name), 0, strlen($name));
		}

		$fileThumbPath = $dstThumbDir . $name . '.' . $ext;
		for ($i = 1, $filePath = $dstDir . $name . '.' . $ext; file_exists($webroot . $filePath); $i++) {
			$filePath = $dstDir . $name . "_$i." . $ext;
			$fileThumbPath = $dstThumbDir . $name . "_$i." . $ext;
		}

		$file->saveAs($webroot . $filePath, false);
		/**
		 * если удалось получить размер картинки (т.е. это изображение),
		 * то пересохраняем файл в папку $thumb_directory и уменьшаем его размер
		 * до 100 пикселей по ширине (если картинка больше этого размера)
		 */
		$imgSize = getimagesize($webroot . $filePath);

		if ($imgSize) {
			$file->saveAs($webroot . $fileThumbPath, false);
			if ($imgSize > 100) {
				$iFactor = $imgSize[1] / $imgSize[0];
				$image = new Image($webroot . $fileThumbPath);
				$image->resize(100, (int)(100 * $iFactor));
				$image->save();
			}
		}
		$file->saveAs($webroot . $filePath);

		return array(Yii::app()->baseUrl . $filePath, $file->name);
	}

	public
	function run()
	{
		if (isset($this->saveCallback) && is_callable($this->saveCallback)) {
			$save = $this->saveCallback;
		} elseif (isset($this->directory)) {
			$save = array($this, 'save');
		} else {
			throw new CException(Yii::t('imperavi-redactor-widget.main', 'Either $directory property, or $saveCallback should be set'));
		}
		$uploadModel = new UploadedImage($this->validator);
		$uploadModel->file = CUploadedFile::getInstanceByName('file');

		if ($uploadModel->validate()) {
			list($fileLink, $fileName) = call_user_func($save, $uploadModel->file);
			echo CJSON::encode(array(
				'filelink' => $fileLink,
				'filename' => $fileName,
			));
		} else {
			echo CJSON::encode(array(
				"error" => $uploadModel->getErrors('file'),
			));
		}
	}
}

class UploadedImage extends CModel
{
	protected $validator;

	/** @var CUploadedFile */
	public $file;

	/**
	 * Returns the list of attribute names of the model.
	 *
	 * @return array list of attribute names.
	 */
	public function attributeNames()
	{
		return array(
			'file' => Yii::t('imperavi-redactor-widget.main', "File"),
		);
	}

	function __construct($validator = array())
	{
		$this->validator = $validator;
	}

	public function rules()
	{
		$validator = array('file', 'file') + $this->validator;

		return array(
			$validator,
		);
	}
}
