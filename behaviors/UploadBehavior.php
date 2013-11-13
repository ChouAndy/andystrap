<?php

class UploadBehavior extends CActiveRecordBehavior
{
	public $webroot;
	public $savePath;
	public $webPath;
	public $folder = 'uploads';

	public $mode = 'one';
	public $attribute;
	public $deleteOptions;

	public $useDB = TRUE;
	public $modelName; // other model name
	public $foreignKey;

	/* for saveOtherTable */
	public $rename = TRUE;

	public function __construct()
	{
		$this->webroot = YiiBase::getPathOfAlias('webroot');
	}

	/**
	 * load default config
	 */
	public function loadConfig()
	{
		$this->savePath = $this->webroot.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.strtolower(get_class($this->Owner));
		$this->webPath = '/'.$this->folder.'/'.strtolower(get_class($this->Owner));
		/* check if save directory is not exist, to make. */
		if (!is_dir($this->savePath)) mkdir($this->savePath, 0777, true);
		/* set foreignKey */
		if ($this->mode == 'two') {
			$this->foreignKey = empty($this->foreignKey) ? strtolower(get_class($this->Owner)).'_id' : $this->foreignKey;
		}
	}

	public function beforeSave($event)
	{
		$this->loadConfig();

		switch ($this->mode) {
			case 'one':
				$this->loadModeOne();
				break;
		}
	}

	public function afterSave($event)
	{
		$this->loadConfig();

		switch ($this->mode) {
			case 'two':
				$this->loadModeTwo();
				break;
		}
	}

	public function beforeDelete($event)
	{
		$this->loadConfig();

		switch ($this->mode) {
			case 'one':
				
				break;
			case 'two':
				if (!empty($this->deleteOptions)) {
					$oldData = $this->Owner->findByPk($this->Owner->id);
					if (!empty($oldData->{$this->deleteOptions[0]}->{$this->deleteOptions[1]})) {
						$webroot = YiiBase::getPathOfAlias('webroot');
						$oldFilePath = $webroot.$oldData->{$this->deleteOptions[0]}->{$this->deleteOptions[1]};
						array_map('unlink', glob($oldFilePath));
					}
				}
				break;
		}
	}

	public function loadModeOne() {
		/* setup CUploadedFile */
		$uploadFile = CUploadedFile::getInstance($this->Owner, $this->attribute);
		/* when process uploading. */
		if (!empty($uploadFile)) {
			/* get fileName, if $this->rename is TRUE, to rename file. */
			$fileName = $uploadFile->getName();
			$fileName = $this->rename ? time().'.'.$uploadFile->getExtensionName() : $fileName;
			/* save file */
			$uploadFile->saveAs($this->savePath.DIRECTORY_SEPARATOR.$fileName);

			/* if use database */
			if ($this->useDB) {
				/* delete old file */
				if (!$this->Owner->isNewRecord) {
					$oldData = $this->Owner->findByPk($this->Owner->id);
					$oldFile = $oldData->getAttribute($this->attribute);
					if (!empty($oldFile)) {
						$oldFilePath = $this->webroot.$oldData->getAttribute($this->attribute);
						array_map('unlink', glob($oldFilePath));
					}
				}
				$this->Owner->setAttribute($this->attribute, $this->webPath.'/'.$fileName);
			}
		}
	}

	public function loadModeTwo() {
		/* setup CUploadedFile */
		$uploadFile = CUploadedFile::getInstance($this->Owner, $this->attribute);
		/* when process uploading. */
		if (!empty($uploadFile)) {
			/* get fileName, if $this->rename is TRUE, to rename file. */
			$fileName = $uploadFile->getName();
			$fileName = $this->rename ? time().'.'.$uploadFile->getExtensionName() : $fileName;
			/* save file */
			$uploadFile->saveAs($this->savePath.DIRECTORY_SEPARATOR.$fileName);

			/* if use database */
			if ($this->useDB) {
				if (empty($this->modelName)) {
					throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
				} else {
					$fileModel = NULL;
					if ($this->Owner->isNewRecord) {
						$fileModel = new $this->modelName;
					} else {
						$fileModel = call_user_func($this->modelName.'::model')->find(array(
							'condition' => $this->foreignKey.'=:foreignKey',
							'params' => array(':foreignKey' => $this->Owner->id),
						));
						/* delete old file */
						if (count($fileModel) > 0) {
							$oldFilePath = $this->webroot.$fileModel->path;
							if (is_file($oldFilePath)) {
								array_map('unlink', glob($oldFilePath));
							}
						} else {
							$fileModel = new $this->modelName;
						}
					}
					
					/* set fileData info. */
					$fileData = array(
						$this->foreignKey => $this->Owner->id,
						'name' => $uploadFile->getName(),
						'ext' => $uploadFile->getExtensionName(),
						'type' => $uploadFile->getType(),
						'size' => $uploadFile->getSize(),
						'filesize' => Yii::app()->format->formatSize($uploadFile->getSize()),
						'path' => $this->webPath.'/'.$fileName,
					);

					/* save file data */
					$fileModel->attributes = $fileData;
					$fileModel->save();
				}
			}
		}
	}
}