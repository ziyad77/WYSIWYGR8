<?php

function parsage($document)
{
        /* chargement du fichier HTML */
		
		$contenu='';
			
		/*Entête du fichier XML EAST*/
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
		
		/* Fichier EAST .xml */
		$filename="east.xml";

		//supprimer fichier existant
		if( file_exists ( $filename))
		  unlink( $filename ) ;

		// 1 : on ouvre le fichier
		$fichier = fopen($filename,"a+");
		
		/* Ouverture du document avec LOAD HTML FILE */
		$doc = new DOMDocument();
		$doc->loadHTMLFile($document);
		
		/*  Identification de la première balise */
        $elements = $doc->getElementsByTagName('div'); 
		
		/* variable qui stocke les balises EAST XML */
        $resultat_xml = '';
		
		/* récupère la première balise (parent) du fichier HTML */
        $arbre = $elements->item(0);
		
		/* éxecution de la fonction pour récupérer les balises enfants */
        $resultat_xml = parsage_enfant($arbre);
 
		
        return $resultat_xml;
}


function parsage_normal($noeud, $contenu_a_inserer='')
{
     $balise_1 = array('div' => '<SECTION>',
						'h1' => '<TITRE>',
						'h2' => '<TITRE>',
						'ul' => '<LISTE>',
						'li' => '<EL>',
						'a' => '<LIEN_EXTERNE>',
						'p' => '<PARAGRAPHE>',
						'embed' => '<VIDEO>',
						'#text' => ''
                                ); // Tableau des balises ouvrantes
                                                
	$balise_2 = array('div' => '</SECTION>',
							'h1' => '</TITRE>',
							'h2' => '</TITRE>',
							'ul' => '</LISTE>',
							'li' => '</EL>',
							'a' => '</LIEN_EXTERNE>',
							'p' => '</PARAGRAPHE>',
							'embed' => '</VIDEO>',
							'#text' => ''
							); // Tableau des balises fermantes
 
	$attributs = array('position' => 'valeur',
									'flottant' => 'valeur',
									'taille' => 'valeur',
									'couleur' => 'nom',
									'police' => 'nom',
									'lien' => 'url',
									'image' => 'legende',
									'citation' => 'auteur'); // Tableau des attributs
                                                                
	$nom = $noeud->nodeName; // On récupère le nom du nœud	
	
	if(!empty($contenu_a_inserer)) // On détermine si on veut spécifier du contenu préparsé
	{
			$contenu = $contenu_a_inserer; // Si c'est le cas, on met la variable de fonction en contenu
	}
	else
	{
			$contenu = $noeud->nodeValue; // Sinon, le contenu du nœud
	}
	
	$premiere_balise = $balise_1[$nom];     // Première balise (ouvrante)
	
	/*
	if($noeud->hasAttributes() and $nom != 'img') // On remplace les attributs (sauf pour les images)
	{              
					
			$un = $noeud->attributes->getNamedItem($attributs[$nom])->nodeValue; // Récupération de la valeur de l'attribut 
			$premiere_balise = str_replace("$1", $un, $premiere_balise); // On remplace la valeur $1 par celle de l'attribut
			
	}*/
        
        if($nom == 'img') // Cas particulier des images
                
        {
                $un = $contenu; // Dans ce cas, c'est $1 qui récupère le contenu du nœud (l'URL de l'image).
                $premiere_balise = str_replace("$1", $un, $premiere_balise);
                
                if($noeud->hasAttributes()) // Si l'image contient une légende (attribut $2)
                
                {
                        $deux = $noeud->attributes->getNamedItem('legende')->nodeValue; // Récupération de l'attribut « legende »
                }
                else // Par défaut, la légende (alt) est « Image »
                {
                        $deux = 'Image';
                }
        
                $premiere_balise = str_replace("$2", $deux, $premiere_balise);
                $intermediaire = $premiere_balise;
        
        }
        else // Cas général
        {
               $intermediaire = $premiere_balise . $contenu . $balise_2[$nom]; // On assemble le tout
                if($nom == 'ul'  or $nom == 'li')
                {
                        $intermediaire = preg_replace("#<LISTE>(\s)*<EL>#sU", "<LISTE><EL>", $intermediaire);
                        $intermediaire = preg_replace("#</EL>(\s)*<EL>#sU", "</EL><EL>", $intermediaire);
                        $intermediaire = preg_replace("#</EL>(\s)*</LISTE>#sU", "</EL></LISTE>", $intermediaire);
                }
                
                if($nom == 'body')
                {
                        $intermediaire = nl2br($intermediaire); // On saute des lignes au résultat final
                }
        }
        return $intermediaire; // On renvoie le texte parsé
}


function parsage_enfant($noeud)// Fonction de parsage d'enfants
{
        if(!isset($accumulation)) // Si c'est la première balise, on initialise $accumulation
        {
                $accumulation = '';
        }
        
        $enfants_niv1 = $noeud->childNodes; // Les enfants du nœud traité
        
        foreach($enfants_niv1 as $enfant) // Pour chaque enfant, on vérifie…
        {
                if($enfant->hasChildNodes() == true) // …s'il a lui-même des enfants
                {
                        $accumulation .= parsage_enfant($enfant); // Dans ce cas, on revient sur parsage_enfant
                }
                else // ... s'il n'en a plus !
                {
                        $accumulation .= parsage_normal($enfant); // On parse comme un nœud normal
                }
        }
        return parsage_normal($noeud, $accumulation);
}


echo parsage('test.html'); // Mettez le nom du fichier XML

?>
