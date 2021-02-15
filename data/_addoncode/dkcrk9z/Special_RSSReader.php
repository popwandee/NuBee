<?php
defined('is_running') or die('Not an entry point...');

global $addonPathCode;

require_once($addonPathCode.'/include/Common.inc');


class Special_RSSReader extends  Common_RSSReader{
	var $start;
	var $feed;

	function Special_RSSReader(){
		$this->loadConfig();
		$feed = new SimplePie();
		$start = self::microtime_float();

	
		// Initial setup
		
			$feed->set_feed_url($this->mKeyEnabled);
			$feed->enable_order_by_date($this->mSortOrder);
			$feed->enable_cache($this->mUseCache); 
			$feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/data/_cache');
     		$feed->set_item_limit	( $this->mItemformSource );

//
			$feed->init();
		
                echo $feed->get_title();
		//$feed->handle_content_type();
		echo '<div id="sp_results">';
		$this->FeedOut($feed);
		echo '</div>';
	}
	
	function microtime_float(){
		if (version_compare(phpversion(), '5.0.0', '>=')){
			return microtime(true);
		}
		else{
			list($usec, $sec) = explode(' ', microtime());
			return ((float) $usec + (float) $sec);
		}
	}

	function FeedOut ($feed){
		if ($feed->data): 
			$items = $feed->get_items();
              $count_new_message = 0;
		foreach($items as $item):
	
                        if($item->get_date('ymd')>Date('ymd', strtotime("-".$this->mFilterLastDays." days"))):
                                $count_new_message++;
                        endif;
  
	        endforeach;


		if ($this->mDisplayFeatures)
		{
			echo '<p align="center"><span style="background-color:#ffc;">'.$this->RSSi18n['MESSAGEDISPLAYING'].' '. $count_new_message .' '.$this->RSSi18n['MESSAGEMOSTRECENT'].' '. $feed->get_item_quantity(). ' '.$this->RSSi18n['MESSAGEFRESHFEED'].'</span></p>';
		}
			foreach($items as $item):

                        if($item->get_date('ymd')>Date('ymd', strtotime("-".$this->mFilterLastDays." days"))):
				echo '<div class="chunk" style="padding:0 5px;">
					<h4 style="margin-bottom:0;"><a href="'; echo $item->get_permalink().'">'.$item->get_title().'</a></h4>
					<div style="font-size:small;">'.$this->RSSi18n['MESSAGEPOSTED'].' '.$item->get_date('j M Y').' ';
					if ($author = $item->get_author()) echo $this->RSSi18n['MESSAGEADDEDBY'].' '.$author->get_name();
				echo '</div>'.
					$item->get_content();
				echo '<p>';

 	                            $category_array = array();
					if ($item->get_categories())
					{
							foreach ($item->get_categories() as $category)
							{
								array_push($category_array,$category->get_label());
							}
					 echo '<p><span style="font-weight:bold;">'.$this->RSSi18n['MESSAGETAGED'].'</span> ';
        				 echo implode ( ", " , $category_array );
					 echo '</p>';
					}

				echo '</div>';
            		   endif;
  
			endforeach;
		endif;
	}

}
