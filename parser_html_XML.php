<!doctype html>
<html lang="fr">
 <head>
 	
    <meta charset="UTF-8" />
    <title>PRESENTATION</title>

 </head>
 <body>
	
	<form method="post" action="parseur_HTML.php" name="form" enctype="multipart/form-data" >

		<h2 style="text-align: center;">SELECTIONNER VOTRE PRESENTATION HTML</h2>

		<label>PRESENTATION HTML</label><br>
		<input type="file"  name="presentation" id="presentation" style="width:500px;" />
		<br>
		
		<input type="submit" name="envoyer" id="envoyer" value="Convertir" onclick="return valider();"/> 						 				
	
	</form>	

	
 </body>
</html>

<?php

if(isset($_POST['envoyer']))
{
		if(isset($_FILES['presentation']))
		{		
				$extension = strrchr($_FILES['presentation']['type'], '/');
				
				if($extension=="/html")
				{
				
						$fichier = basename($_FILES['presentation']['name']); 
						
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
								
								libxml_use_internal_errors(true);
								$doc->loadHTMLFile($document);
								
								/*  Identification de la première balise */
								$elements = $doc->getElementsByTagName('div'); 
								
								/* variable qui stocke les balises EAST XML */
								$resultat_xml = '';
								
								/* récupère la première balise (parent) du fichier HTML */
								$arbre = $elements->item(0);
								
								/* éxecution de la fonction pour récupérer les balises enfants */
								$resultat_xml = parsage_enfant($arbre);

								$contenu .= $resultat_xml;
								
								$contenu .='
								</EAST>
								';
						 
								fputs($fichier, $contenu); 
								fclose($fichier);
								
								return $resultat_xml;
						}

						/* La fonction permet de remplacer les balises html par des balises XML EAST */
						function parsage_normal($noeud, $contenu_a_inserer='')
						{
							
							 $balise_1 = array('div' => '<SECTION>',
												'h1' => '<TITRE>',
												'h2' => '<TITRE>',
												'h3' => '<TITRE>',
												'h4' => '<TITRE>',
												'h5' => '<TITRE>',
												'h6' => '<TITRE>',
												'ul' => '<LISTE>',
												'li' => '<EL>',
												'a' => '<LIEN_INTERNE>',
												'a' => '<LIEN_EXTERNE>',
												'p' => '<PARAGRAPHE>',
												'img' => '<IMAGE/>',
												'svg' => '<FORME>',
												'table' => '<TABLEAU>',
												'td' => '',
												'tr' => '',
												'th' => '',
												'tt' => '',
												'tbody' => '',
												'tfoot' => '',
												'span' => '<TITRE>',
												'polygon' => '<POLYGON>',
												'embed' => '<VIDEO>',
												'link' => '<STYLE>',
												'script' => '',
												'em' => '',
												'button' => '',
												'#cdata-section' => '',
												'#text' => ''
														); // Tableau des balises ouvrantes
																		
							$balise_2 = array('div' => '</SECTION>',
													'h1' => '</TITRE>',
													'h2' => '</TITRE>',
													'h3' => '</TITRE>',
													'h4' => '</TITRE>',
													'h5' => '</TITRE>',
													'h6' => '</TITRE>',
													'ul' => '</LISTE>',
													'li' => '</EL>',
													'a' => '</LIEN_INTERNE>',
													'a' => '</LIEN_EXTERNE>',
													'p' => '</PARAGRAPHE>',
													'img' => '</IMAGE>',
													'svg' => '</FORME>',
													'table' => '</TABLEAU>',
													'td' => '',
													'tr' => '',
													'th' => '',
													'tt' => '',
													'tbody' => '',
													'tfoot' => '',
													'span' => '</TITRE>',
													'polygon' => '</POLYGON>',
													'embed' => '</VIDEO>',
													'link' => '</STYLE>',
													'script' => '',
													'em' => '',
													'button' => '',
													'#cdata-section' => '',
													'#text' => ''
													); // Tableau des balises fermantes
						 
							$attributs = array('position' => 'valeur',
												'flottant' => 'valeur',
												'taille' => 'valeur',
												'couleur' => 'nom',
												'police' => 'nom',
												'a' => '',
												'image' => 'legende',
												'citation' => 'auteur',
												'div' => '',
												'div' => '',
												'embed' => '',
												'embed' => '',
												'embed' => '',
												'embed' => '',
												'embed' => ''
												); // Tableau des attributs
																						
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
							
							
							if($noeud->hasAttributes() and $nom != 'img') // On remplace les attributs (sauf pour les images)
							{              
									
									if($nom == 'a' )
									{
										$un = ' href="'.$noeud->getAttribute('href').'"'; // Récupération de la valeur de l'attribut 
										$premiere_balise = str_replace("$1", $un, $premiere_balise); // On remplace la valeur $1 par celle de l'attribut
									}
									
									/*
									if($nom == 'div')
									{
										if($noeud->getAttribute('id') != "")
										{
											echo 'ID= '.$noeud->getAttribute('id').'<br>';
										}
										
										if($noeud->getAttribute('class') != "")
										{
											echo 'class= '.$noeud->getAttribute('class').'<br>';
										}
										
										
										
									
									}
									*/
									
									//$un = $noeud->attributes->getNamedItem($attributs[$nom])->nodeValue; // Récupération de la valeur de l'attribut 
									//$premiere_balise = str_replace("$1", $un, $premiere_balise); // On remplace la valeur $1 par celle de l'attribut
									
							}
								
								if($nom == 'img') // Cas particulier des images
										
								{
										$un = $contenu; // Dans ce cas, c'est $1 qui récupère le contenu du nœud (l'URL de l'image).
										$premiere_balise = str_replace("$1", $un, $premiere_balise);
										
										if($noeud->hasAttributes()) // Si l'image contient une légende (attribut $2)
										{
											$deux = ' src="'.$noeud->getAttribute('src').'"'; // Récupération de la valeur de l'attribut
											//$deux = $noeud->attributes->getNamedItem('legende')->nodeValue; // Récupération de l'attribut « legende »
										}
										else // Par défaut, la légende (alt) est « Image »
										{
												$deux = 'Image';
										}
								
										//$premiere_balise = str_replace("$2", $deux, $premiere_balise);
										$intermediaire = '<IMAGE '.$deux.'/>';
								
								}
								else // Cas général
								{
									   $intermediaire = $premiere_balise . $contenu . $balise_2[$nom]; // On assemble le tout
									 

										if($nom == 'a' )
										{
											$intermediaire='<LIEN_EXTERNE '.$un.'>'.$contenu . $balise_2[$nom];
										}
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


						echo '<br> <h2> Visualisation du fichier EAST XML : </h2><br>'.parsage($fichier); // Mettez le nom du fichier XML
						
						?>
							<script>

							alert("Le fichier EAST XML a ete cree avec succes !");

							</script>


						<?php
				}
				else
				{
					?>
							<script>

							alert("Veuillez selectionner un fichier HTML !");

							</script>

					<?php
				
				}
		}
}
else
{

?>
<script>

alert("Veuillez selectionner un fichier HTML !");

</script>


<?php
}


?>

