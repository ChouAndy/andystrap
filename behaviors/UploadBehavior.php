<?php

class UploadBehavior extends CActiveRecordBehavior
{
	public $beforeSave;
	public $afterSave;
	public $webroot;
	public $folder = 'uploads';
	public $modelName;
	public $attribute;
	public $useDB = TRUE;

	/* for saveOtherTable */
	public $rename = TRUE;
	public $foreignKey;

	public function afterSave($event)
	{
		if ($this->afterSave) {
			$this->saveOtherTable();
		}
	}

	public function getAbsoluteSavePath() {
		/* set webroot path */
		if (is_null($this->webroot)) {
			$this->webroot = YiiBase::getPathOfAlias('webroot');
		}
		$savePath = $this->webroot.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.strtolower(get_class($this->Owner));
		
		/* check if save directory is not exist, to make. */
		if (!is_dir($savePath)) mkdir($savePath, 0777, true);

		return $savePath;
	}

	public function saveOtherTable() {
		/* setup CUploadedFile */
		$uploadFile = CUploadedFile::getInstance($this->Owner, $this->attribute);
		/* when process uploading. */
		if (!empty($uploadFile)) {
			$savePath = $this->getAbsoluteSavePath();

			/* get fileName, if $this->rename is TRUE, to rename file. */
			$fileName = $uploadFile->getName();
			if ($this->rename) {
				$fileName = time().'.'.$uploadFile->getExtensionName();
			}

			/* save file */
			$uploadFile->saveAs($savePath.DIRECTORY_SEPARATOR.$fileName);

			/* if use database */
			if ($this->useDB) {
				if (empty($this->modelName)) {
					throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
				} else {
					if (empty($this->foreignKey)) {
						$this->foreignKey = strtolower(get_class($this->Owner)).'_id';
					}
					/* delete old file */
					$fileModel = call_user_func($this->modelName.'::model')->find(array(
						'condition' => $this->foreignKey.'=:foreignKey',
						'params' => array(':foreignKey' => $this->Owner->id),
					));
					if (count($fileModel) > 0) {
						$oldFilePath = $this->webroot.$fileModel->path;
						if (is_file($oldFilePath)) {
							array_map('unlink', glob($oldFilePath));
						}
					} else {
						/* if it is a new record */
						$fileModel = new $this->modelName;
					}

					/* set fileData info. */
					$fileData = array(
						$this->foreignKey => $this->Owner->id,
						'name' => $uploadFile->getName(),
						'ext' => $uploadFile->getExtensionName(),
						'type' => $uploadFile->getType(),
						'size' => $uploadFile->getSize(),
						'filesize' => Yii::app()->format->formatSize($uploadFile->getSize()),
						'path' => '/'.$this->folder.'/'.strtolower(get_class($this->Owner)).'/'.$fileName,
					);

					/* save file data */
					$fileModel->attributes = $fileData;
					$fileModel->save();
				}
			}
		}
	}
}