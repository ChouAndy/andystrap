yii-fancybox
============

fancybox v2.1.5 for Yii Framework

## 安裝

 * 將目錄 assets 及檔案 FancyBox.php 放到目錄 protected/extensions/fancybox 底下
 * 將底下程式碼放到 views 中

	    $this->widget('application.extensions.fancybox.FancyBox', array(
	        'target' => 'a[rel=gallery]',
	        'config' => array(),
	    ));
