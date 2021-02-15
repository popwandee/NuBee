<?php
defined('is_running') or die('Not an entry point...');

global $addonPathCode;

require_once($addonPathCode.'/include/Common.inc');



class Admin_RSSReader extends  Common_RSSReader
{
  const mVersion           ='1.0';


  function Admin_RSSReader()
  {

    $cmd                   = common::GetCommand();
    $this->loadConfig();

    switch($cmd){
      case 'saveConfig':
        $this->saveConfig();
        break;
      case 'newRSS':
	$this->CreateRSS();
        break;
      case 'delRSS':
	$this->DeleteRSS();
        break;

    }


		$this->showForm();
  }



  function showForm()
  {
    global $langmessage;

    echo '<h1>RSS show (v'.self::mVersion.')</h1>';

    echo '<h2>'.$this->RSSi18n['MESSAGEURLDESCRIPTION1'].'</h2>';

    echo '<form action="'.common::GetUrl('Admin_RSSReader').'" method="post">';

    echo '<p>'.$this->RSSi18n['MESSAGEURLDESCRIPTION2'].'</p>';

   $i = 0;
   echo '<table class="bordered">';
   echo '<tr><th>'.$this->RSSi18n['MESSAGEFEEDURL'].'</th><th>'.$this->RSSi18n['MESSAGEENABLED'].'</th><th>'.$this->RSSi18n['MESSAGEOPTION'].'</th></tr>';
   echo '<tbody class="sortable_table">';
   if (sizeof($this->mKey)<1)
		array_push($this->mKey,'');
   foreach($this->mKey as $val){
    echo '<tr VALIGN=MIDDLE ><td align="center" ><input type="text" name="key['.$i.']" value="'.$val['url'].'" class="gpinput" style="width:600px" /></td>';
    echo '<td align="center" ><input type="checkbox" name="enabled['.$i.']" value="'.$i++.'"  ';
    echo $val['enabled']?"checked":""; 
    echo ' class="gpinput" style="width:60" ></td><td align="center">';
    echo common::Link(common::GetUrl('Admin_RSSReader'),$langmessage['delete'],'cmd=delRSS&index='.$i,' name="postlink" class="gpconfirm" title="Delete this Feed?" ');
    echo '</td></tr>';
    } 
   echo '</tbody>';
   echo '</table>';
    echo '<p>'.$this->RSSi18n['MESSAGEITEMFROMSOURCE'].':';
    echo '<select name="ItemformSource">';
   $counter = 1;
   do{


      if( $this->mItemformSource == $counter) 
        echo '  <option value='.$counter.' selected="selected">'.$counter.'</option>';
      else 
        echo '  <option value='.$counter.' >'.$counter.'</option>';

        $counter++;

    }while($counter < 26);

    echo '</select></p>';


    echo '<p>'.$this->RSSi18n['MESSAGEFILTERLASTDAYS'].':';
    echo '<select name="FilterLastDays">';
   $counter = 1;
   do{


      if( $this->mFilterLastDays == $counter) 
        echo '  <option value='.$counter.' selected="selected">'.$counter.'</option>';
      else if ($this->between($counter,400,100000000)) 
        echo '  <option value='.$counter.' >'.$this->RSSi18n['MESSAGEINFINITY'].'</option>';
      else 
        echo '  <option value='.$counter.' >'.$counter.'</option>';


      if ($this->between($counter,1,9))
        $counter++;
   if ($this->between($counter,10,30))
        $counter+=5;
   if ($this->between($counter,30,80))
        $counter+=10;
   if ($this->between($counter,81,400))
        $counter+=100;
   if ($this->between($counter,401,100000000))
        $counter+=1000000;


    }while($counter < 1000);

    echo '</select></p>';

    echo '<p>'.$this->RSSi18n['MESSAGESORTORDER'].':';
    echo '<select name="SortOrder">';
        echo '  <option value=true'.($this->mSortOrder?'selected="selected"':'').'>'.$langmessage['True'].'</option>';
        echo '  <option value=false'.($this->mSortOrder?'':'selected="selected"').'>'.$langmessage['False'].'</option>';
    echo '</select></p>';


    echo '<p>'.$this->RSSi18n['MESSAGEUSECACHE'].':';
    echo '<select name="UseCache">';
        echo '  <option value=true'.($this->mUseCache?'selected="selected"':'').'>'.$langmessage['True'].'</option>';
        echo '  <option value=false'.($this->mSortOrder?'':'selected="selected"').'>'.$langmessage['False'].'</option>';
    echo '</select></p>';


    echo '<p>'.$this->RSSi18n['MESSAGEDISPLAYFEEDINFO'].':';
    echo '<select name="displayFeatures">';
        echo '  <option value=true'.($this->mdisplayFeatures?'selected="selected"':'').'>'.$langmessage['True'].'</option>';
        echo '  <option value=false'.($this->mdisplayFeatures?'':'selected="selected"').'>'.$langmessage['False'].'</option>';
    echo '</select></p>';



    echo '<input type="hidden" name="cmd" value="saveConfig" />';

    echo '<input type="submit" value="'.$langmessage['save_changes'].'" class="gpsubmit"/>';
    echo common::Link(common::GetUrl('Admin_RSSReader'),$langmessage['add'],'cmd=newRSS&newcount='.++$this->newcount,' name="postlink" class="gpconfirm" title="Add New RSS?" ');
    echo '</p>';
    echo '</form>';
  }

  function CreateRSS()
  {

		if( !isset($_POST['newcount']) ){
			$tmpnewcount = 0;
		}else {
                     $tmpnewcount = $_POST['newcount'];
  
               }
    
     $this->newcount = $tmpnewcount;

    echo "to jest: ".$this->newcount;
    do{
      array_push($this->mKey,'');
     }while($tmpnewcount-->1);
  }


  function DeleteRSS()
  {

		if( !isset($_POST['index']) ){
			message($langmessage['OOPS'].' (Invalid Index)');
			return false;
		}

    unset($this->mKey[$_POST['index']-1]);

  }

  function saveConfig()
  {

    global                   $addonPathData;
    global                   $langmessage;
    $config                   = array();
    $config['key']		  = array();

	$i = 0;
    while(isset($_POST['key'][$i]))
	{
		array_push($config['key'],array("url" => $_POST['key'][$i], "enabled" => isset($_POST['enabled'][$i])?true:false ));
		$i++;
	}




    $configFile            = $addonPathData.'/config.php';
    $config['displayFeatures'] = $_POST['displayFeatures'];
    $config['FilterLastDays'] = $_POST['FilterLastDays'];
    $config['ItemformSource'] = $_POST['ItemformSource'];
    $config['SortOrder'] = $_POST['SortOrder'];
    $config['UseCache'] = $_POST['UseCache'];




    $this->mKey             = $config['key'];
    $this->mDisplayFeatures = $config['displayFeatures'];
    $this->mItemformSource  = $config['ItemformSource'];
    $this->mFilterLastDays  = $config['FilterLastDays'];
    $this->mSortOrder 	= $config['SortOrder'];
    $this->mUseCache        = $config['UseCache'];

    if( !gpFiles::SaveArray($configFile,'config',$config) )
    {
      message($langmessage['OOPS']);
      return false;
    }

    message($langmessage['SAVED']);
    return true;
  }


}
// vim: set noai ts=2 sw=2: 
?>

