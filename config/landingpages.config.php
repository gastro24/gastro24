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
       'diaetkoch' => [
          'id' => 159,
		  'query' => [ 'q' => 'Diätkoch'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Küchen Jobs',
          'text' => 'Diätkoch / Diätköchin'
     ],
	'kuechenchef' => [
          'id' => 161,
		  'query' => [ 'q' => 'Küchenchef'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Küchen Jobs',
          'text' => 'Küchenchef/-in'
     ],
	'sous-chef' => [
          'id' => 163,
		  'query' => [ 'q' => '"Sous Chef"'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Küchen Jobs',
          'text' => 'Sous Chef'
     ],
	'jungkoch' => [
          'id' => 165,
		  'query' => [ 'q' => 'Jungkoch'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Küchen Jobs',
          'text' => 'Jungkoch / Jungköchin'
     ],
	'koch' => [
          'id' => 167,
		  'query' => [ 'q' => 'Koch'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Küchen Jobs',
          'text' => 'Koch / Köchin'
     ],
	'hilfskoch' => [
          'id' => 169,
		  'query' => [ 'q' => 'Hilfskoch'],
          'text' => 'Hilfskoch / Hilfsköchin'
     ],
	'alleinkoch' => [
          'id' => 171,
		  'query' => [ 'q' => 'Alleinkoch'],
          'text' => 'Alleinkoch / Alleinköchin'
     ],

	'konditor-confiseur' => [
          'id' => 175,
		  'query' => [ 'q' => 'Konditor Confiseur'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Lebensmittel Jobs',
          'text' => 'Konditor / Confiseur'
     ],
	'baecker' => [
          'id' => 177,
		  'query' => [ 'q' => 'Bäcker'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Lebensmittel Jobs',
          'text' => 'Bäcker/-in'
     ],	
	 'metzger' => [
          'id' => 179,
		  'query' => [ 'q' => 'Metzger'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Lebensmittel Jobs',
          'text' => 'Metzger/-in'
     ],	
	 'ernaehrungsberater' => [
          'id' => 181,
		  'query' => [ 'q' => 'Ernährungsberater'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Lebensmittel Jobs',
          'text' => 'Ernährungsberater/-in'
     ],	
	 'lebesmitteltechnologe' => [
          'id' => 183,
		  'query' => [ 'q' => 'Lebensmitteltechnologe'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Lebensmittel Jobs',
          'text' => 'Lebensmitteltechnologe / Lebensmitteltechnologin'
     ],	
	 'lebensmittelinspektor' => [
          'id' => 185,
		  'query' => [ 'q' => 'Lebensmittelinspektor'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Lebensmittel Jobs',
          'text' => 'Lebensmittelinspektor/-in'
     ],
	 'restaurationsfachmann' => [
          'id' => 189,
		  'query' => [ 'q' => 'Restaurationsfachmann Restaurationsfachfrau'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Restaurationsfachmann /-frau'
     ], 
	 'servicemitarbeiter' => [
          'id' => 191,
		  'query' => [ 'q' => 'Servicemitarbeiter'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Servicemitarbeiter/-in'
     ],
	  'chef-de-rang' => [
          'id' => 193,
		  'query' => [ 'q' => '"Chef de Rang"'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Chef de Rang'
     ],
	 'barkeeper' => [
          'id' => 195,
		  'query' => [ 'q' => 'Barkeeper'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Barkeeper'
     ],
	  'barista' => [
          'id' => 197,
		  'query' => [ 'q' => 'Barista'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Barista'
     ],
	  'restaurant-manager' => [
          'id' => 199,
		  'query' => [ 'q' => '"Restaurant Manager"'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Restaurant Manager/-in'
     ],
	  'sommelier' => [
          'id' => 201,
		  'query' => [ 'q' => 'Sommelier'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Service & Restaurant Jobs',
          'text' => 'Sommelier / Sommelière'
     ], 
	 'hotelfachmann' => [
          'id' => 205,
		  'query' => [ 'q' => 'Hotelfachmann Hotelfachfrau'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Hotelfachmann / Hotelfachfrau'
     ],
	  'wellness-spa-beauty' => [
          'id' => 209,
		  'query' => [ 'q' => 'Wellness Spa Beauty'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Wellness / Spa & Beauty'
     ],
	  'hoteldirektion' => [
          'id' => 211,
		  'query' => [ 'q' => 'Hoteldirektion Hotelleitung'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Hoteldirektion / Hotelleitung'
     ],
	  'concierge-room-service' => [
          'id' => 213,
		  'query' => [ 'q' => 'Concierge "Room Service"'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Concierge / Room Service'
     ],
	  'housekeeping-zimmermaedchen' => [
          'id' => 215,
		  'query' => [ 'q' => 'Housekeeping Zimmermädchen'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Housekeeping / Zimmermädchen'
     ],
	  'hauswirtschaft' => [
          'id' => 217,
		  'query' => [ 'q' => 'Hauswirtschaft Wäscherei'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Hauswirtschaft / Wäscherei'
     ],
	  'rezeption-hotelempfang' => [
          'id' => 219,
		  'query' => [ 'q' => 'Rezeption Hotelempfang'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Hotel Jobs',
          'text' => 'Rezeption / Hotelempfang'
     ],	 
	 'marketing-sales' => [
          'id' => 223,
		  'query' => [ 'q' => 'Marketing Sales'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Marketing / Sales'
     ],
	  'administration-back-office' => [
          'id' => 225,
		  'query' => [ 'q' => 'Administration Office'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Administration / Back Office'
     ],
	  'reisefuehrer-tourguide' => [
          'id' => 227,
		  'query' => [ 'q' => 'Reiseführer Tourguide'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Reiseführer / Tour Guide'
     ],
	  'kreuzfahrt' => [
          'id' => 229,
          'tab' => 'Jobs nach Berufsfeld',
		  'query' => [ 'q' => 'Kreuzfahrt'],
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Kreuzfahrt'
     ],
	  'animation-entertainment' => [
          'id' => 231,
		  'query' => [ 'q' => 'Animation Entertainment'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Animation / Entertainment'
     ],
	  'casino' => [
          'id' => 233,
		  'query' => [ 'q' => 'Casino'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Casino'
     ],
	  'fitness' => [
          'id' => 235,
		  'query' => [ 'q' => 'Fitness'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Tourismus & Administration Jobs',
          'text' => 'Fitness'
     ],
	  'studenten' => [
          'id' => 239,
		  'query' => [ 'q' => 'Studenten'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Aushilfe & Saison Jobs',
          'text' => 'Studenten'
     ],
	  'hilfskraefte' => [
          'id' => 241,
		  'query' => [ 'q' => 'Hilfskräfte'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Aushilfe & Saison Jobs',
          'text' => 'Hilfskräfte'
     ],
	  'teilzeit' => [
          'id' => 243,
		  'query' => [ 'q' => 'Teilzeit'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Aushilfe & Saison Jobs',
          'text' => 'Teilzeit'
     ],
	  'saison' => [
          'id' => 245,
		  'query' => [ 'q' => 'Saison'],
          'tab' => 'Jobs nach Berufsfeld',
          'panel' => 'Aushilfe & Saison Jobs',
          'text' => 'Saison'
     ],
	 'region-appenzell-ausserrhoden' => [
        'text' => 'Appenzell Ausserrhoden',
        'id' => 316,
        'query' => [ 'region_MultiString' => ['Appenzell Ausserrhoden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-appenzell-innerrhoden' => [
        'text' => 'Appenzell Innerrhoden',
        'id' => 318,
        'query' => [ 'region_MultiString' => ['Appenzell Innerrhoden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-glarus' => [
        'text' => 'Glarus',
        'id' => 320,
        'query' => [ 'region_MultiString' => ['Glarus' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-liechtenstein' => [
        'text' => 'Liechtenstein',
        'id' => 322,
        'query' => [ 'region_MultiString' => ['Liechtenstein' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-schaffhausen' => [
        'text' => 'Schaffhausen',
        'id' => 325,
        'query' => [ 'region_MultiString' => ['Schaffhausen' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-st-gallen' => [
        'text' => 'St. Gallen',
        'id' => 327,
        'query' => [ 'region_MultiString' => ['St. Gallen' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-thurgau' => [
        'text' => 'Thurgau',
        'id' => 329,
        'query' => [ 'region_MultiString' => ['Thurgau' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-graubuenden' => [
        'text' => 'Graubünden',
        'id' => 331,
        'query' => [ 'region_MultiString' => ['Graubünden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-aargau' => [
        'text' => 'Aargau',
        'id' => 333,
        'query' => [ 'region_MultiString' => ['Aargau' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-basel-landschaft' => [
        'text' => 'Basel-Landschaft',
        'id' => 335,
        'query' => [ 'region_MultiString' => ['Basel-Landschaft' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-basel-stadt' => [
        'text' => 'Basel-Stadt',
        'id' => 337,
        'query' => [ 'region_MultiString' => ['Basel-Stadt' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-bern' => [
        'text' => 'Bern',
        'id' => 339,
        'query' => [ 'region_MultiString' => ['Bern' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-jura' => [
        'text' => 'Jura',
        'id' => 341,
        'query' => [ 'region_MultiString' => ['Jura' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	   'region-solothurn' => [
        'text' => 'Solothurn',
        'id' => 343,
        'query' => [ 'region_MultiString' => ['Solothurn' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 
	  'region-freiburg' => [
        'text' => 'Freiburg',
        'id' => 346,
        'query' => [ 'region_MultiString' => ['Freiburg' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-genf' => [
        'text' => 'Genf',
        'id' => 348,
        'query' => [ 'region_MultiString' => ['Genf' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-neuenburg' => [
        'text' => 'Neuenburg',
        'id' => 350,
        'query' => [ 'region_MultiString' => ['Neuenburg' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-waadt' => [
        'text' => 'Waadt',
        'id' => 352,
        'query' => [ 'region_MultiString' => ['Waadt' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-wallis' => [
        'text' => 'Wallis',
        'id' => 354,
        'query' => [ 'region_MultiString' => ['Wallis' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	  'region-luzern' => [
        'text' => 'Luzern',
        'id' => 356,
        'query' => [ 'region_MultiString' => ['Luzern' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-nidwalden' => [
        'text' => 'Nidwalden',
        'id' => 358,
        'query' => [ 'region_MultiString' => ['Nidwalden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
		'region-obwalden' => [
        'text' => 'Obwalden',
        'id' => 360,
        'query' => [ 'region_MultiString' => ['Obwalden' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-schwyz' => [
        'text' => 'Schwyz',
        'id' => 362,
        'query' => [ 'region_MultiString' => ['Schwyz' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-uri' => [
        'text' => 'Uri',
        'id' => 364,
        'query' => [ 'region_MultiString' => ['Uri' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	 'region-zug' => [
        'text' => 'Zug',
        'id' => 366,
        'query' => [ 'region_MultiString' => ['Zug' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-zurich' => [
        'text' => 'Zürich',
        'id' => 368,
        'query' => [ 'region_MultiString' => ['Zürich' => 1]],
        'tab' => 'Jobs nach Region',
        'panel' => 'Schweiz',
    ],
	'region-tessin' => [
        'text' => 'Tessin',
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
	
	
	

     'coop-restaurant' => [
        'id' => 0,
        'company' => 'Coop',
        'query' => [ 'organizationTag' => ['Coop Restaurant' => 1]],
        'logo' => 'http://libs.coop.ch/logos/165x70/logo-coop-ohne-claim-165x70.svg',
     ],
     'marche' => [
        'id' => 0,
        'company' => 'Coop',
        'query' => [ 'organizationTag' => ['Marché' => 1]],
        'logo' => 'http://libs.coop.ch/logos/165x70/logo-marche-restaurant-165x70.svg',
     ],
	  'spruengli' => [
        'id' => 0,
        'company' => 'Sprüngli',
        'query' => [ 'organizationTag' => ['Spruengli' => 1]],
        'logo' => 'https://www.spruengli.ch/cms/typo3temp/_processed_/csm_logo2x_244299dfd3.png',
     ],
	  'sv-group' => [
        'id' => 0,
        'company' => 'SV Group',
        'query' => [ 'organizationTag' => ['SV Group' => 1]],
        'logo' => 'http://www.sv-group.ch/typo3conf/ext/netv_sv_template/Resources/Public/media/images/logo/svgroup/logo.svg',
     ],
	   'migros' => [
        'id' => 0,
        'company' => 'Migros',
        'query' => [ 'organizationTag' => ['Migros' => 1]],
        'logo' => 'https://www.migros.ch/dam/jcr:784c2e31-9dd8-42ec-8142-e75d1abb22d9/migrosx.svg',
     ],
	  'adecco' => [
        'id' => 0,
        'company' => 'ADECCO',
        'query' => [ 'organizationTag' => ['ADECCO' => 1]],
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/ab/Adecco_2016.png',
     ],
     'globus' => [
        'id' => 0,
        'company' => 'GLOBUS',
        'query' => [ 'organizationTag' => ['GLOBUS' => 1]],
        'logo' => 'https://image.migros.ch/jobs/original/e9b5646942ce52afed859b7e7b687bafdbfcb855/230-de.png',
     ],
              

];


/* Do not edit below this line */

return [ 'options' => [ \Gastro24\Options\Landingpages::class => [ $options ] ]];
