<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */

 /*
  * Konfiguration der Landingpages.
  *
  * Format is:
  * [ <term> => [spec] ]
  *
  * Available keys in [spec]:
  *
  * id:      Map to a wordpress page to get title, description and meta-data
  * text:    Text to show in the link
  * query:   [query]
  * tab:     Name of the Tab to show this link in
  * panel:   Name of the panel to show this link in
  * company: Name of a company. This link is shown in the slider underneath the tabs
  * logo:    Url to an company logo, which is then shown instead of the company name.
  *
  * [query]:
  *  q:     Search term for the freetext field.
  *  region_MultiString: Region-Facets.
  *  organizationTag:    Company-Facets.
  * if panel is empty, the link is not shown on homepage. But he is still avalaible.
  */



$options = [
      'gouvernante' => [
          'id' => 1269,
		  'query' => [ 'q' => 'Gouvernante'],
          'text' => 'Gouvernante Jobs'
     ],
     'kaeser-in' => [
          'id' => 1264,
		  'query' => [ 'q' => 'Käser'],
          'text' => 'Käser Käserin Jobs'
     ],
     'bereichsleiter' => [
          'id' => 1266,
		  'query' => [ 'q' => 'Bereichsleiter'],
          'text' => 'Bereichsleiter Jobs'
     ],
    'buffet-mitarbeiterin' => [
          'id' => 1260,
		  'query' => [ 'q' => 'Buffet'],
          'text' => 'Buffet Mitarbeiterin'
     ],
    'commis-de-cuisine' => [
          'id' => 1220,
		  'query' => [ 'q' => 'Commis de Cuisine'],
          'text' => 'Commis de Cuisine Jobs'
     ],
     'studenten' => [
          'id' => 1218,
		  'query' => [ 'q' => 'Studenten'],
          'text' => 'Studenten Jobs'
     ],
    
    'chef-de-bar' => [
          'id' => 1216,
		  'query' => [ 'q' => 'Chef de Bar'],
          'text' => 'Chef de Bar Jobs'
     ],
    
    'general-manager' => [
          'id' => 1214,
		  'query' => [ 'q' => 'General Manager'],
          'text' => 'General Manager Jobs'
     ],
     'front-office' => [
          'id' => 1212,
		  'query' => [ 'q' => 'Front Office'],
          'text' => 'Front Office Jobs'
     ],
     'lehrstellen-gastronomie' => [
          'id' => 1210,
		  'query' => [ 'q' => 'Lehrstelle'],
          'text' => 'Lehrstellen'
     ],
    'hilfskoch' => [
          'id' => 169,
		  'query' => [ 'q' => 'Hilfskoch'],
          'text' => 'Hilfskoch Jobs'
     ],
    'alleinkoch' => [
          'id' => 171,
		  'query' => [ 'q' => 'Alleinkoch'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Alleinkoch Jobs'
     ],
    
    'baecker' => [
          'id' => 177,
		  'query' => [ 'q' => 'Bäcker'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Bäcker Jobs'
     ],
      'chef-de-rang' => [
          'id' => 193,
		  'query' => [ 'q' => '"Chef de Rang"'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Chef de Rang Jobs'
     ],
     'diaetkoch' => [
          'id' => 159,
		  'query' => [ 'q' => 'Diätkoch'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Diätkoch Jobs'
     ],
     'entremetier' => [
          'id' => 936,
		  'query' => [ 'q' => 'Entremetier'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Entremetier Jobs'
     ],
      'filialleiter' => [
          'id' => 235,
		  'query' => [ 'q' => 'Filialleiter'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Filialleiter Jobs'
     ],
      'garde-manger' => [
          'id' => 213,
		  'query' => [ 'q' => 'Garde Manger'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Garde Manger Jobs'
     ],
      'hotelfachmann' => [
          'id' => 205,
		  'query' => [ 'q' => 'Hotelfachmann'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Hotelfachmann Jobs'
     ],
     'jungkoch' => [
          'id' => 165,
		  'query' => [ 'q' => 'Jungkoch'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Jungkoch Jobs'
     ],
     'koch' => [
          'id' => 167,
		  'query' => [ 'q' => 'Koch'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Koch Jobs'
     ],
     'lebensmitteltechnologe' => [
          'id' => 183,
		  'query' => [ 'q' => 'Lebensmitteltechnologe'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Lebensmitteltechnologe Jobs'
     ],	
      'metzger' => [
          'id' => 179,
		  'query' => [ 'q' => 'Metzger'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Metzger Jobs'
     ],	
     'office' => [
          'id' => 225,
		  'query' => [ 'q' => 'Office'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Office Mitarbeiter Jobs'
     ],
     'patissier' => [
          'id' => 217,
		  'query' => [ 'q' => 'Patissier'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Patissier Jobs'
     ],
     'restauration' => [
          'id' => 189,
		  'query' => [ 'q' => 'Restaurationsfachfrau'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Restaurationsfachfrau Jobs'
     ], 
      'servicemitarbeiterin' => [
          'id' => 191,
		  'query' => [ 'q' => 'Servicemitarbeiterin'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Servicemitarbeiterin Jobs'
     ],
      'zimmermaedchen' => [
          'id' => 215,
		  'query' => [ 'q' => 'Zimmermädchen'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Zimmermädchen Jobs'
     ],
     
     'kuechenchef' => [
          'id' => 161,
		  'query' => [ 'q' => 'Küchenchef'],
          'panel' => '',
          'text' => 'Küchenchef Jobs'
     ],
     
     'sous-chef' => [
          'id' => 163,
		  'query' => [ 'q' => 'Sous Chef'],
          'panel' => '',
          'text' => 'Sous Chef Jobs'
     ],
       'systemgastronomiefachmann' => [
          'id' => 1045,
		  'query' => [ 'q' => 'Systemgastronomiefachmann'],
          'panel' => '',
          'text' => 'Systemgastronomie Jobs'
     ],
       'chef-de-partie' => [
          'id' => 1052,
		  'query' => [ 'q' => 'Chef de Partie'],
          'panel' => '',
          'text' => 'Chef de Partie Jobs'
     ],
      'tournant' => [
          'id' => 1048,
		  'query' => [ 'q' => 'Tournant'],
          'panel' => '',
          'text' => 'Tournant Jobs'
     ],
      'kellner' => [
          'id' => 1096,
		  'query' => [ 'q' => 'Kellner'],
          'text' => 'Kellner Jobs'
     ],
	 'pizzaiolo' => [
          'id' => 1098,
		  'query' => [ 'q' => 'Pizzaiolo'],
          'text' => 'Pizzaiolo Jobs'
     ],
	 'rezeptionistin' => [
          'id' => 219,
		  'query' => [ 'q' => 'Rezeptionistin'],
          'text' => 'Rezeptionistin Jobs'
     ],	 
	  'konditor-confiseur' => [
          'id' => 175,
		  'query' => [ 'q' => 'Konditor'],
          'text' => 'Konditor Confiseur Jobs'
     ],
	   'restaurantmanager' => [
          'id' => 199,
		  'query' => [ 'q' => 'Restaurant Manager'],
          'text' => 'Restaurant Manager Jobs'
     ],
	 'ernaehrungsberater' => [
          'id' => 181,
		  'query' => [ 'q' => 'Ernährungsberater'],
          'text' => 'Ernährungsberater Jobs'
     ],	
	 'barkeeper' => [
          'id' => 195,
		  'query' => [ 'q' => 'Barkeeper'],
          'text' => 'Barkeeper Jobs'
     ],
	  'barista' => [
          'id' => 197,
		  'query' => [ 'q' => 'Barista'],
          'text' => 'Barista Jobs'
     ], 
	  'sommelier' => [
          'id' => 201,
		  'query' => [ 'q' => 'Sommelier'],
          'text' => 'Sommelier Jobs'
     ],
	 
	  'animation' => [
          'id' => 231,
		  'query' => [ 'q' => 'Animateur'],
          'text' => 'Animateur Jobs'
     ],
	
	  'teilzeitverkaeuferin' => [
          'id' => 243,
		  'query' => [ 'q' => 'Teilzeitverkäuferin'],
          'text' => 'Teilzeitverkäuferin Jobs'
     ],
	 'kosmetikerin' => [
          'id' => 209,
		  'query' => [ 'q' => 'Kosmetikerin'],
          'text' => 'Kosmetikerin Jobs'
     ],
     
     
     
     
     
     
	 'region-appenzell-ausserrhoden' => [
        'text' => 'Jobs Appenzell Ausserrhoden',
        'id' => 316,
        'query' => [ 'region_MultiString' => ['Appenzell Ausserrhoden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-appenzell-innerrhoden' => [
        'text' => 'Jobs Appenzell Innerrhoden',
        'id' => 318,
        'query' => [ 'region_MultiString' => ['Appenzell Innerrhoden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-glarus' => [
        'text' => 'Jobs Glarus',
        'id' => 320,
        'query' => [ 'region_MultiString' => ['Glarus' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-liechtenstein' => [
        'text' => 'Jobs Liechtenstein',
        'id' => 322,
        'query' => [ 'region_MultiString' => ['Liechtenstein' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-schaffhausen' => [
        'text' => 'Jobs Schaffhausen',
        'id' => 325,
        'query' => [ 'region_MultiString' => ['Schaffhausen' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-st-gallen' => [
        'text' => 'Jobs St. Gallen',
        'id' => 327,
        'query' => [ 'region_MultiString' => ['Sankt Gallen' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-thurgau' => [
        'text' => 'Jobs Thurgau',
        'id' => 329,
        'query' => [ 'region_MultiString' => ['Thurgau' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-graubuenden' => [
        'text' => 'Jobs Graubünden',
        'id' => 331,
        'query' => [ 'region_MultiString' => ['Graubünden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-aargau' => [
        'text' => 'Jobs Aargau',
        'id' => 333,
        'query' => [ 'region_MultiString' => ['Aargau' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-basel-landschaft' => [
        'text' => 'Jobs Basel-Landschaft',
        'id' => 335,
        'query' => [ 'region_MultiString' => ['Basel-Landschaft' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-basel-stadt' => [
        'text' => 'Jobs Basel-Stadt',
        'id' => 337,
        'query' => [ 'region_MultiString' => ['Basel-Stadt' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-bern' => [
        'text' => 'Jobs Bern',
        'id' => 339,
        'query' => [ 'region_MultiString' => ['Bern' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-jura' => [
        'text' => 'Jobs Jura',
        'id' => 341,
        'query' => [ 'region_MultiString' => ['Jura' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-solothurn' => [
        'text' => 'Jobs Solothurn',
        'id' => 343,
        'query' => [ 'region_MultiString' => ['Solothurn' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 
	  'region-freiburg' => [
        'text' => 'Jobs Freiburg',
        'id' => 346,
        'query' => [ 'region_MultiString' => ['Freiburg' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-genf' => [
        'text' => 'Jobs Genf',
        'id' => 348,
        'query' => [ 'region_MultiString' => ['Genf' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-neuenburg' => [
        'text' => 'Jobs Neuenburg',
        'id' => 350,
        'query' => [ 'region_MultiString' => ['Neuenburg' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-waadt' => [
        'text' => 'Jobs Waadt',
        'id' => 352,
        'query' => [ 'region_MultiString' => ['Waadt' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-wallis' => [
        'text' => 'Jobs Wallis',
        'id' => 354,
        'query' => [ 'region_MultiString' => ['Wallis' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-luzern' => [
        'text' => 'Jobs Luzern',
        'id' => 356,
        'query' => [ 'region_MultiString' => ['Luzern' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-nidwalden' => [
        'text' => 'Jobs Nidwalden',
        'id' => 358,
        'query' => [ 'region_MultiString' => ['Nidwalden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-obwalden' => [
        'text' => 'Jobs Obwalden',
        'id' => 360,
        'query' => [ 'region_MultiString' => ['Obwalden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-schwyz' => [
        'text' => 'Jobs Schwyz',
        'id' => 362,
        'query' => [ 'region_MultiString' => ['Schwyz' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-uri' => [
        'text' => 'Jobs Uri',
        'id' => 364,
        'query' => [ 'region_MultiString' => ['Uri' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-zug' => [
        'text' => 'Jobs Zug',
        'id' => 366,
        'query' => [ 'region_MultiString' => ['Zug' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-zurich' => [
        'text' => 'Jobs Zürich',
        'id' => 368,
        'query' => [ 'region_MultiString' => ['Zürich' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-tessin' => [
        'text' => 'Jobs Tessin',
        'id' => 370,
        'query' => [ 'region_MultiString' => ['Tessin' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'koch-in-zuerich' => [
        'text' => 'Zürich',
        'id' => 766,
        'query' => [ 'q' => 'Koch Zürich', 'region_MultiString' => ['Zürich' => 1]]
    ],
	'koch-in-wallisellen' => [
        'text' => 'Wallisellen',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Wallisellen' => 1]]
    ],
	'koch-in-basel' => [
        'text' => 'Basel',
        'id' => 770,
        'query' => [ 'q' => 'Koch Basel', 'region_MultiString' => ['Basel-Stadt' => 1]]
    ],	
	'koch-in-stgallen' => [
        'text' => 'Sankt Gallen',
        'id' => 764,
        'query' => [ 'q' => 'Koch St.Gallen', 'region_MultiString' => ['Sankt Gallen' => 1]]
    ],	
   'koch-in-bern' => [
        'text' => 'Bern',
        'id' => 768,
        'query' => [ 'q' => 'Koch Bern', 'region_MultiString' => ['Bern' => 1]]
    ],	
   'koch-in-chur' => [
        'text' => 'Chur',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Chur' => 1]]
    ],	
   'koch-in-kloten' => [
        'text' => 'Kloten',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Kloten' => 1]]
    ],	
   'koch-in-luzern' => [
        'text' => 'Luzern',
        'id' => 772,
        'query' => [ 'q' => 'Koch Luzern', 'region_MultiString' => ['Luzern' => 1]]
    ],	
   'koch-in-wohlen' => [
        'text' => 'Wohlen',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Wohlen' => 1]]
    ],							
   'koch-in-solothurn' => [
        'text' => 'Solothurn',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Solothurn' => 1]]
    ],							
   'koch-in-zug' => [
        'text' => 'Zug',
        'id' => 774,
        'query' => [ 'q' => 'Koch Zug', 'region_MultiString' => ['Zug' => 1]]
    ],							
   'koch-in-kriens' => [
        'text' => 'Kriens',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Kriens' => 1]]
    ],			
   'koch-in-winterthur' => [
        'text' => 'Winterthur',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Winterthur' => 1]]
    ],			
   'koch-in-waedendswil' => [
        'text' => 'Wädenswil',
        'id' => 370,
        'query' => [ 'q' => 'Koch', 'region_MultiString' => ['Wädenswil' => 1]]
    ],			
				
	
	
	
	
	/*
    'stadt-zuerich' => [
        'text' => 'Stadt Zürich',
        'id' => 2,
        'query' => [ 'city_MultiString' => ['Zürich' => 1]],
        'tab' => 'Jobs nach Stadt',
        'panel' => 'Städte Deutschschweiz',
    ],
    'stadt-genf' => [
        'text' => 'Stadt Genf',
        'id' => 2,
        'query' => [ 'city_MultiString' => ['Genf' => 1]],
        'tab' => 'Jobs nach Stadt',
        'panel' => 'Städte Romandie',
    ],	
    'stadt-lugano' => [
        'text' => 'Stadt Lugano und Bellinzona',
        'id' => 2,
        'query' => [ 'city_MultiString' => ['Lugano' => 1, 'Bellinzona' => 1 ]],
        'tab' => 'Jobs nach Stadt',
        'panel' => 'Städte Italienische Schweiz',
    ],*/
	
	
	
];


/* Do not edit below this line */

return [ 'options' => [ \Gastro24\Options\Landingpages::class => [ $options ] ]];