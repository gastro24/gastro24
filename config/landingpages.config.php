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
    
    'alleinkoch' => [
          'id' => 171,
		  'query' => [ 'q' => 'Alleinkoch'],
          'text' => 'Alleinkoch'
     ],
    
    
    
    
    
       'diaetkoch' => [
          'id' => 159,
		  'query' => [ 'q' => 'Diätkoch'],
          'tab' => 'Berufe von A - Z',
          'panel' => '',
          'text' => 'Diätkoch / Diätköchin'
     ],
     
     
	 'region-appenzell-ausserrhoden' => [
        'text' => 'Jobs in Appenzell Ausserrhoden',
        'id' => 316,
        'query' => [ 'region_MultiString' => ['Appenzell Ausserrhoden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-appenzell-innerrhoden' => [
        'text' => 'Jobs in Appenzell Innerrhoden',
        'id' => 318,
        'query' => [ 'region_MultiString' => ['Appenzell Innerrhoden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-glarus' => [
        'text' => 'Jobs in Glarus',
        'id' => 320,
        'query' => [ 'region_MultiString' => ['Glarus' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-liechtenstein' => [
        'text' => 'Jobs in Liechtenstein',
        'id' => 322,
        'query' => [ 'region_MultiString' => ['Liechtenstein' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-schaffhausen' => [
        'text' => 'Jobs in Schaffhausen',
        'id' => 325,
        'query' => [ 'region_MultiString' => ['Schaffhausen' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-st-gallen' => [
        'text' => 'Jobs in St. Gallen',
        'id' => 327,
        'query' => [ 'region_MultiString' => ['St. Gallen' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-thurgau' => [
        'text' => 'Jobs in Thurgau',
        'id' => 329,
        'query' => [ 'region_MultiString' => ['Thurgau' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-graubuenden' => [
        'text' => 'Jobs in Graubünden',
        'id' => 331,
        'query' => [ 'region_MultiString' => ['Graubünden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-aargau' => [
        'text' => 'Jobs in Aargau',
        'id' => 333,
        'query' => [ 'region_MultiString' => ['Aargau' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-basel-landschaft' => [
        'text' => 'Jobs in Basel-Landschaft',
        'id' => 335,
        'query' => [ 'region_MultiString' => ['Basel-Landschaft' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-basel-stadt' => [
        'text' => 'Jobs in Basel-Stadt',
        'id' => 337,
        'query' => [ 'region_MultiString' => ['Basel-Stadt' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-bern' => [
        'text' => 'Jobs in Bern',
        'id' => 339,
        'query' => [ 'region_MultiString' => ['Bern' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-jura' => [
        'text' => 'Jobs in Jura',
        'id' => 341,
        'query' => [ 'region_MultiString' => ['Jura' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-solothurn' => [
        'text' => 'Jobs in Solothurn',
        'id' => 343,
        'query' => [ 'region_MultiString' => ['Solothurn' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 
	  'region-freiburg' => [
        'text' => 'Jobs in Freiburg',
        'id' => 346,
        'query' => [ 'region_MultiString' => ['Freiburg' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-genf' => [
        'text' => 'Jobs in Genf',
        'id' => 348,
        'query' => [ 'region_MultiString' => ['Genf' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-neuenburg' => [
        'text' => 'Jobs in Neuenburg',
        'id' => 350,
        'query' => [ 'region_MultiString' => ['Neuenburg' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-waadt' => [
        'text' => 'Jobs in Waadt',
        'id' => 352,
        'query' => [ 'region_MultiString' => ['Waadt' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-wallis' => [
        'text' => 'Jobs in Wallis',
        'id' => 354,
        'query' => [ 'region_MultiString' => ['Wallis' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-luzern' => [
        'text' => 'Jobs in Luzern',
        'id' => 356,
        'query' => [ 'region_MultiString' => ['Luzern' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-nidwalden' => [
        'text' => 'Jobs in Nidwalden',
        'id' => 358,
        'query' => [ 'region_MultiString' => ['Nidwalden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-obwalden' => [
        'text' => 'Jobs in Obwalden',
        'id' => 360,
        'query' => [ 'region_MultiString' => ['Obwalden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-schwyz' => [
        'text' => 'Jobs in Schwyz',
        'id' => 362,
        'query' => [ 'region_MultiString' => ['Schwyz' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-uri' => [
        'text' => 'Jobs in Uri',
        'id' => 364,
        'query' => [ 'region_MultiString' => ['Uri' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-zug' => [
        'text' => 'Jobs in Zug',
        'id' => 366,
        'query' => [ 'region_MultiString' => ['Zug' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-zurich' => [
        'text' => 'Jobs in Zürich',
        'id' => 368,
        'query' => [ 'region_MultiString' => ['Zürich' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-tessin' => [
        'text' => 'Jobs in Tessin',
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
