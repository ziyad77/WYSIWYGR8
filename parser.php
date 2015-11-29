<?php

require_once 'simple_html_dom.php'; 

$contenu='';

$contenu='
<?xml version="1.0" encoding="ISO-8859-1"?>
<EAST>
	<PREFERENCES>
			<PUCE colorpuce="orange" level="1" type="square"/>
			<PUCE colorpuce="green" level="2" type="disc"/>
			<PUCE colorpuce="orange" level="3" type="circle"/>
			<PUCE colorpuce="green" level="4" type="square"/>
			<PAGE backcolor="#0033FF" font="Comic sans MS" linkcolor="yellow" textcolor="white"/>
			<TITLES textcolor="white"/>
	</PREFERENCES>';
$filename="east.xml";

//supprimer fichier existant
if( file_exists ( $filename))
  unlink( $filename ) ;

// 1 : on ouvre le fichier
$fichier = fopen($filename,"a+");


$html1 = new simple_html_dom(); 
$html1->load_file('test.html'); 
//$titre = $html1->find('h2',0)->innertext;

/*
$title = $html1->find('.pagetitre');
$contenu .='
<PAGE_TITRE>
	<TITRE>'.$title->plaintext.'</TITRE><AUTEUR></AUTEUR>
</PAGE_TITRE>';
*/


/****************************************/
//H

for($i=1; $i<7; $i++)
{
	$titres='h'.$i;
	
	$titre = $html1->find("".$titres."");

	foreach( $titre as $t)
	{
		echo $t->plaintext;
		$contenu .='
		<TITRE>'.$t->plaintext.'</TITRE>';
	}	
}

/***************************************/
//UL

$ul = $html1->find("ul");

foreach( $ul as $liste)
{
	$contenu .='
	<LISTE>';
}

/**************************************/
//LI

$li = $html1->find("li");

foreach( $li as $el)
{
	echo $el->plaintext;
	$contenu .='
	<EL>'.$el->plaintext.'</EL>';
}

/**************************************/
//UL

$ul = $html1->find("ul");

foreach( $ul as $liste)
{
	$contenu .='
	</LISTE>';
}

/**************************************/
//LIEN

 echo '<br><br>';

foreach($html1->find('a') as $element) 
{
	echo $element->href . '<br>';
	echo $element->plaintext . '<br>';
	$contenu .='
		<LIEN_EXTERNE href="'.$element->href.'">'.$element->plaintext.'</LIEN_EXTERNE>';
}
       
/************************************/
//IMAGE

 echo '<br><br>';	   
foreach($html1->find('img') as $element) 
{
	echo $element->src . '<br>';
	$contenu .='
		<IMAGE href="'.$element->src.'"></IMAGE>';
}

/************************************/
//PARTIE -> PARTIE $ret = $html->find('div[id=foo]'); 
$i=1;
 echo '<br><br>';	   
foreach($html1->find("div[id=partie".$i."]") as $element) 
{
	$contenu .='
		<PARTIE></PARTIE>';
	$i++;
}       

/***********************************/
//SLIDE -> SECTION
$i=1;
 echo '<br><br>';
foreach($html1->find("div[id=slide".$i."]") as $element) 
{
	
	$contenu .='
		<SECTION></SECTION>';	
	$i++;
} 	

/***********************************/
//PARAGRAPHE 

 echo '<br><br>';
foreach($html1->find("p") as $element) 
{
	
	$contenu .='
		<PARAGRAPHE>'.$element->plaintext.'</PARAGRAPHE>';	
	
}    
	 
/************************************/
//FORME

echo '<br><br>';
foreach($html1->find(".overlay") as $element) 
{
	
	$contenu .='
		<FORME></FORME>';	
	
}    

/**************************************/
//VIDEO

 echo '<br><br>';

foreach($html1->find('embed') as $element) 
{
	echo $element->href . '<br>';
	$contenu .='
		<VIDEO href="'.$element->href.'"></VIDEO>';
}

 echo '<br><br>'; echo '<br><br>';
 echo '<br><br>'; echo '<br><br>';
/*************************************************************************/
$enfant=$html1->find('#presentation',0)->children();
foreach($enfant as $element) 
{
	//echo $element; 
	echo $element;
	echo $element->tag;
	foreach($element->find('ul') as $ul)
	{
		echo $ul->tag;
	}
}








/*************************************************************************/	 
$contenu .='
</EAST>
';


fputs($fichier, $contenu); 
fclose($fichier);



?>
