Andystrap for Yii Framework
===========================

## 安裝

 * 將底下程式碼放到 protected/config/main.php 中

	    'aliases' => array(
	        // for andystrap
	        'andystrap' => 'ext.andystrap',
	    ),
	    // autoloading model and component classes
	    'import' => array(
	        // for andystrap
	        'andystrap.helpers.*',
	    ),
	    // application components
	    'components' => array(
	        // for Andystrap
	        'andystrap' => array(
	            'class' => 'andystrap.Andystrap',
	        ),
	    ),
