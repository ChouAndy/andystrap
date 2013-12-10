<?php
/*
	_Brush_                 :   _Language_
	applescript             :   AppleScript
	actionscript3 as3       :   AS3
	bash shell              :   Bash
	coldfusion cf           :   ColdFusion
	cpp c                   :   Cpp
	c# c-sharp csharp       :   CSharp
	css                     :   Css
	delphi pascal           :   Delphi
	diff patch pas          :   Diff
	erl erlang              :   Erlang
	groovy                  :   Groovy
	java                    :   Java
	jfx javafx              :   JavaFX
	js jscript javascript   :   JScript
	perl pl                 :   Perl
	php                     :   Php
	text plain              :   Plain
	py python               :   Python
	ruby rails ror rb       :   Ruby
	sass scss               :   Sass
	scala                   :   Scala
	sql                     :   Sql
	vb vbnet                :   Vb
	xml xhtml xslt html     :   Xml

	<?php $this->beginWidget('andystrap.widgets.AdHighlighter', array(
		'language' => 'html',
		'css' => 'fade_to_grey'
	)); ?>
	<p>Content Area</p>
	<?php $this->endWidget(); ?>
*/

Yii::import('andystrap.helpers.AdHtml');

class AdHighlighter extends COutputProcessor
{
	/**
	 * @var CClientScript Something which can register assets for later inclusion on page.
	 * For now it's just the `Yii::app()->clientScript`
	 */
	public $assetsRegistry;

	/**
	 * @var string holds the published assets
	 */
	protected $_assetsUrl;

	public $css;

	public $coreCSSFile;

	public $language;

	public $scriptFile;
	
	public function init()
	{
		parent::init();
		// if not informed will generate Yii defaut generated id, since version 1.6
		if (!isset($this->id)) {
			$this->id = $this->getId();
		}
		/* assetsRegistry */
		if (!$this->assetsRegistry) {
			$this->assetsRegistry = Yii::app()->getClientScript();
		}
		// publish the required assets
		$this->publishAssets();
		$this->registerJSCode();
	}
	
	// function to run the widget
	public function run()
	{
		$content = ob_get_clean();

		$output = AdHtml::openTag('pre', array('class' => 'brush: '.$this->language));
		$output .= CHtml::encode($content);
		$output .= AdHtml::closeTag('pre');

		$this->processOutput($output);
	}
	
	public function getAssetsUrl()
	{
		if (isset($this->_assetsUrl)) {
			return $this->_assetsUrl;
		} else {
			return $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
				Yii::getPathOfAlias('andystrap.assets'),
				false,
				-1,
				false
			);
		}
	}

	// function to publish and register assets on page 
	public function publishAssets()
	{
		if (!Yii::app()->params['highlighter.publishAssets']) {
			$this->assetsRegistry->registerCssFile($this->getAssetsUrl().'/highlighter/css/'.$this->getCssFile());
			$this->assetsRegistry->registerScriptFile($this->getAssetsUrl().'/highlighter/js/shCore.js', CClientScript::POS_END);
			$this->assetsRegistry->registerScriptFile($this->getAssetsUrl().'/highlighter/js/'.$this->getScriptFile(), CClientScript::POS_END);

			Yii::app()->params['highlighter.publishAssets'] = TRUE;
			$languages = array($this->language);
			Yii::app()->params['highlighter.language'] = $languages;
		} else {
			$languages = Yii::app()->params['highlighter.language'];
			if (!in_array($this->language, $languages)) {
				$this->assetsRegistry->registerScriptFile($this->getAssetsUrl().'/highlighter/js/'.$this->getScriptFile(), CClientScript::POS_END);
				$languages[] = $this->language;
				Yii::app()->params['highlighter.language'] = $languages;
			}
		}
	}

	public function registerJSCode()
	{
		if (empty(Yii::app()->params['highlighter.jscode'])) {
			$script = "SyntaxHighlighter.defaults['toolbar'] = false;\n";
			$script .= "SyntaxHighlighter.all();";
			Yii::app()->clientScript->registerScript($this->id, $script, CClientScript::POS_END);

			Yii::app()->params['highlighter.jscode'] = TRUE;
		}
	}

	public function getCssFile()
	{
		$this->css = empty($this->css) ? 'default' : $this->css;

		switch ($this->css) {
			case 'default':
				$this->coreCSSFile = 'shCoreDefault.css';
				break;

			case 'django':
				$this->coreCSSFile = 'shCoreDjango.css';
				break;

			case 'elipse':
				$this->coreCSSFile = 'shCoreElipse.css';
				break;

			case 'emacs':
				$this->coreCSSFile = 'shCoreEmacs.css';
				break;

			case 'fade_to_grey':
				$this->coreCSSFile = 'shCoreFadeToGrey.css';
				break;

			case 'md_ultra':
				$this->coreCSSFile = 'shCoreMDUltra.css';
				break;

			case 'midnight':
				$this->coreCSSFile = 'shCoreMidnight.css';
				break;

			case 'r_dark':
				$this->coreCSSFile = 'shCoreRDark.css';
				break;

			default:
				$this->coreCSSFile = 'shCoreDefault.css';
				break;
		}

		return $this->coreCSSFile;
	}

	public function getScriptFile()
	{
		$this->language = empty($this->language) ? 'php' : $this->language;

		switch ($this->language) {
			case 'applescript':
				$this->scriptFile = 'shBrushAppleScript.js';
				break;

			case 'actionscript3':
			case 'as3':
				$this->scriptFile = 'shBrushAS3.js';
				break;

			case 'bash':
			case 'shell':
				$this->scriptFile = 'shBrushBash.js';
				break;

			case 'coldfusion':
			case 'cf':
				$this->scriptFile = 'shBrushColdFusion.js';
				break;

			case 'cpp':
			case 'c':
				$this->scriptFile = 'shBrushCpp.js';
				break;

			case 'c#':
			case 'c-sharp':
			case 'csharp':
				$this->scriptFile = 'shBrushCSharp.js';
				break;

			case 'css':
				$this->scriptFile = 'shBrushCss.js';
				break;

			case 'delphi':
			case 'pascal':
				$this->scriptFile = 'shBrushDelphi.js';
				break;

			case 'diff':
			case 'patch':
			case 'pas':
				$this->scriptFile = 'shBrushDiff.js';
				break;

			case 'erl':
			case 'erlang':
				$this->scriptFile = 'shBrushErlang.js';
				break;

			case 'groovy':
				$this->scriptFile = 'shBrushGroovy.js';
				break;

			case 'java':
				$this->scriptFile = 'shBrushJava.js';
				break;

			case 'jfx':
			case 'javafx':
				$this->scriptFile = 'shBrushJavaFX.js';
				break;

			case 'js':
			case 'jscript':
			case 'javascript':
				$this->scriptFile = 'shBrushJScript.js';
				break;

			case 'perl':
			case 'pl':
				$this->scriptFile = 'shBrushPerl.js';
				break;

			case 'php':
				$this->scriptFile = 'shBrushPhp.js';
				break;

			case 'text':
			case 'plain':
				$this->scriptFile = 'shBrushPlain.js';
				break;

			case 'py':
			case 'python':
				$this->scriptFile = 'shBrushPython.js';
				break;

			case 'ruby':
			case 'rails':
			case 'ror':
			case 'rb':
				$this->scriptFile = 'shBrushRuby.js';
				break;

			case 'sass':
			case 'scss':
				$this->scriptFile = 'shBrushSass.js';
				break;

			case 'scala':
				$this->scriptFile = 'shBrushScala.js';
				break;

			case 'sql':
				$this->scriptFile = 'shBrushSql.js';
				break;

			case 'vb':
			case 'vbnet':
				$this->scriptFile = 'shBrushVb.js';
				break;

			case 'xml':
			case 'xhtml':
			case 'xslt':
			case 'html':
				$this->scriptFile = 'shBrushXml.js';
				break;

			default:
				$this->scriptFile = 'shBrushPhp.js';
				break;
		}

		return $this->scriptFile;
	}
}