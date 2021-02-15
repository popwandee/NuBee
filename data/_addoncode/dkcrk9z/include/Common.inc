<?php


require_once('simplepie.inc');





class Common_RSSReader

{

	  var $mKey                = array();

	  var $newcount            = 0;

	  var $mItemformSource     = 10;

	  var $mFilterLastDays     = 10;

	  var $mSortOrder          = true;

	  var $mDisplayFeatures    = true;

	  var $mUseCache           = true;

	  var $mKeyEnabled		   = array();
         
	  var $RSSi18n		= array();




		function LoadLanguage()

		{

		global $addonPathCode, $config, $langmessage;



			if(isset($config['language'])){ $language = $config['language']; }

			else 				  { $language = 'en'; }



		$langFile = $addonPathCode.'/languages/'.$language.'.php';

			if (file_exists($langFile))

			{

				 include($langFile);

			}

			else

			{

				echo $language.' language not found.';

				include($addonPathCode.'/languages/en.php');

			}

		}

	

	

		function loadConfig()

		{

		global                   $addonPathData;



		$configFile            = $addonPathData.'/config.php';

		

		include_once $configFile;



				$this->LoadLanguage();

		

				$this->mKeyEnabled		= array();

			if (isset($config)) 

			{

				

				foreach ($config['key'] as $keyfeed)

				{

					if($keyfeed['enabled'])

						array_push($this->mKeyEnabled,$keyfeed['url']);

				}

				

				

				$this->mKey             = $config['key'];

				$this->mDisplayFeatures = $config['displayFeatures'];

				$this->mItemformSource  = $config['ItemformSource'];

				$this->mFilterLastDays  = $config['FilterLastDays'];

				$this->mSortOrder 		= $config['SortOrder'];

				$this->mUseCache        = $config['UseCache'];

			}

		}	



		function between($val, $min, $max) 

		{

			return ($val >= $min && $val <= $max);

		}	

}