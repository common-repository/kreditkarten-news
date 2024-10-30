<?php
/*
Plugin Name: Kreditkarten News
Plugin URI: http://www.turnstange.org/
Description: Adds a customizeable widget which displays the latest Kreditkarten news by http://www.kreditkarte.org/
Version: 1.0
Author: Stefan Spirkl
Author URI: http://spacequadrat.de/
License: GPL3
*/

function kreditkartennews()
{
  $options = get_option("widget_kreditkartennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Kreditkarten News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.kreditkarte.org/feed'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_kreditkartennews($args)
{
  extract($args);
  
  $options = get_option("widget_kreditkartennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Kreditkarten News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  kreditkartennews();
  echo $after_widget;
}

function kreditkartennews_control()
{
  $options = get_option("widget_kreditkartennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Kreditkarten News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['kreditkartennews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['kreditkartennews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['kreditkartennews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['kreditkartennews-CharCount']);
    update_option("widget_kreditkartennews", $options);
  }
?> 
  <p>
    <label for="kreditkartennews-WidgetTitle">Widget Title: </label>
    <input type="text" id="kreditkartennews-WidgetTitle" name="kreditkartennews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="kreditkartennews-NewsCount">Max. News: </label>
    <input type="text" id="kreditkartennews-NewsCount" name="kreditkartennews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="kreditkartennews-CharCount">Max. Characters: </label>
    <input type="text" id="kreditkartennews-CharCount" name="kreditkartennews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="kreditkartennews-Submit"  name="kreditkartennews-Submit" value="1" />
  </p>
  
<?php
}

function kreditkartennews_init()
{
  register_sidebar_widget(__('Kreditkarten News'), 'widget_kreditkartennews');    
  register_widget_control('Kreditkarten News', 'kreditkartennews_control', 300, 200);
}
add_action("plugins_loaded", "kreditkartennews_init");
?>