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
     
        'seilbahn' => [
          'id' => 1714,
		  'query' => [ 'q' => 'Seilbahn'],
		  'tab' => '',
		   'panel' => '',
          'text' => 'Seilbahn Jobs',
          'parent' => 'touristik'
       ],
         'reiseberatung' => [
          'id' => 1716,
		  'query' => [ 'q' => 'Reiseberatung'],
		  'tab' => '',
		  'panel' => '',
          'text' => 'Reiseberatung Jobs',
          'parent' => 'touristik'
       ],
    
      'reisebuero' => [
          'id' => 1718,
		  'query' => [ 'q' => 'Reisebüro'],
		  'tab' => '',
		  'panel' => '',
          'text' => 'Reisebüro Jobs',
          'parent' => 'touristik'
       ],
    
      'gaestebetreuung' => [
          'id' => 1720,
		  'query' => [ 'q' => 'Gästebetreuung'],
		  'tab' => '',
		  'panel' => '',
          'text' => 'Gästebetreuung Jobs',
          'parent' => 'touristik'
       ],
    
      'reiseleitung' => [
          'id' => 1722,
		  'query' => [ 'q' => 'Reiseleitung'],
		  'tab' => '',
		  'panel' => '',
          'text' => 'Reiseleitung Jobs',
          'parent' => 'touristik'
       ],
    
      'campingplatz' => [
          'id' => 1724,
		  'query' => [ 'q' => 'Camping'],
		  'tab' => '',
		  'panel' => '',
          'text' => 'Campingplatz Jobs',
          'parent' => 'touristik'
       ],
    
      'kreuzfahrtschiff' => [
          'id' => 1727,
		  'query' => [ 'q' => 'Kreuzfahrt'],
		  'tab' => '',
		  'panel' => '',
          'text' => 'Kreuzfahrtschiff Jobs',
          'parent' => 'touristik'
       ],
    
      'touristik' => [
          'id' => 1709,
		  'query' => [ 'q' => 'Tourismus'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Tourismus Jobs'
       ],
     'food' => [
          'id' => 1648,
		  'query' => [ 'q' => 'Food'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Food Jobs'
       ],
    
    'administration-verkauf' => [
          'id' => 1642,
		  'query' => [ 'q' => 'Administration'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Administration Jobs'
       ],

      'manager' => [
          'id' => 1667,
		  'query' => [ 'q' => 'Manager'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Management Jobs'
       ],
       
    'night-auditor' => [
          'id' => 1638,
		  'query' => [ 'q' => 'Night Auditor'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Night Auditor Jobs',
          'parent' => 'hotel'
       ],
     'reservation' => [
          'id' => 1640,
		  'query' => [ 'q' => 'Reservation'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Reservation Jobs',
          'parent' => 'hotel'
       ],
    'hotel' => [
          'id' => 1629,
		  'query' => [ 'q' => 'Hotel'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Hotel Jobs'
       ],
     'service' => [
          'id' => 1603,
		  'query' => [ 'q' => 'Service'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Service Jobs'
       ],
     'kueche' => [
          'id' => 157,
		  'query' => [ 'q' => 'Küche'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Küche Jobs',
       ],
  
     'hausdame' => [
          'id' => 1588,
		  'query' => [ 'q' => 'Hausdame'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Hausdame Jobs',
          'parent' => 'housekeeping'
       ],
  
  'zimmerreinigung' => [
          'id' => 1586,
		  'query' => [ 'q' => 'Zimmerreinigung'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Zimmerreinigung Jobs',
          'parent' => 'housekeeping'
       ],
  
    'reinigungskraft' => [
          'id' => 1584,
		  'query' => [ 'q' => 'Reinigung'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Reinigungskraft Jobs',
          'parent' => 'housekeeping'
       ],
    
    'housekeeping' => [
          'id' => 1582,
		  'query' => [ 'q' => 'Hauswirtschaft'],
		   'tab' => '',
		   'panel' => '',
          'text' => 'Hauswirtschaft Jobs'
       ],
    
      'stadt-zug' => [
        'text' => 'Stadt Zug',
        'id' => 1499,
        'query' => [ 'city_MultiString' => ['Zug' => 1]],
        'tab' => '',
        'panel' => '',
    ],
      'stadt-genf' => [
        'text' => 'Stadt Genf',
        'id' => 1497,
        'query' => [ 'city_MultiString' => ['Genf' => 1]],
        'tab' => '',
        'panel' => '',
    ],
      'stadt-aarau' => [
        'text' => 'Aarau',
        'id' => 1495,
        'query' => [ 'city_MultiString' => ['Aarau' => 1]],
        'tab' => '',
        'panel' => '',
    ],
     'stadt-horgen' => [
        'text' => 'Kloten',
        'id' => 1493,
        'query' => [ 'city_MultiString' => ['Horgen' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
    ],
     'stadt-kloten' => [
        'text' => 'Kloten',
        'id' => 1491,
        'query' => [ 'city_MultiString' => ['Kloten' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
    ],
   'stadt-dietikon' => [
        'text' => 'Dietikon',
        'id' => 1489,
        'query' => [ 'city_MultiString' => ['Dietikon' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
    ],
    
     'stadt-winterthur' => [
        'text' => 'Winterthur',
        'id' => 1487,
        'query' => [ 'city_MultiString' => ['Winterthur' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
    ],
    
      'stadt-chur' => [
        'text' => 'Chur',
        'id' => 1485,
        'query' => [ 'city_MultiString' => ['Chur' => 1]],
        'tab' => '',
        'panel' => '',
    ],
    
     'stadt-lausanne' => [
        'text' => 'Lausanne',
        'id' => 1483,
        'query' => [ 'city_MultiString' => ['Lausanne' => 1]],
        'tab' => '',
        'panel' => '',
    ],
    
     'stadt-duebendorf' => [
        'text' => 'Dübendorf',
        'id' => 1481,
        'query' => [ 'city_MultiString' => ['Dübendorf' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
    ],
    
     'stadt-luzern' => [
        'text' => 'Stadt Luzern',
        'id' => 1479,
        'query' => [ 'city_MultiString' => ['Luzern' => 1]],
        'tab' => '',
        'panel' => '',
    ],
    
     'stadt-sankt-gallen' => [
        'text' => 'Stadt St. Gallen',
        'id' => 1477,
        'query' => [ 'city_MultiString' => ['St. Gallen' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-st-gallen'
    ],
     'stadt-basel' => [
        'text' => 'Stadt Basel',
        'id' => 1475,
        'query' => [ 'city_MultiString' => ['Basel' => 1]],
        'tab' => '',
        'panel' => '',
    ],

  'stadt-zuerich' => [
        'text' => 'Stadt Zürich',
        'id' => 1473,
        'query' => [ 'city_MultiString' => ['Zürich' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
    ],

    
    'stadt-bern' => [
        'text' => 'Stadt Bern',
        'id' => 1469,
        'query' => [ 'city_MultiString' => ['Bern' => 1]],
        'tab' => '',
        'panel' => '',
    ],


      'saucier' => [
          'id' => 1457,
		  'query' => [ 'q' => 'Saucier'],
		  'tab' => 'Jobs von A - Z',
		  'panel' => '',
          'text' => 'Saucier Jobs',
          'parent' => 'kueche'
       ],
    
    'concierge' => [
          'id' => 1461,
		  'query' => [ 'q' => 'Concierge'],
		  'tab' => 'Jobs von A - Z',
		  'panel' => '',
          'text' => 'Concierge Jobs',
          'parent' => 'hotel'
       ],
    
      'barmaid' => [
          'id' => 1459,
		  'query' => [ 'q' => 'Barmaid'],
		  'tab' => 'Jobs von A - Z',
		  'panel' => '',
          'text' => 'Barmaid Jobs',
          'parent' => 'service'
       ],
      
       'schichtleiter' => [
          'id' => 1463,
		  'query' => [ 'q' => 'Schichtleiter'],
		  'tab' => 'Jobs von A - Z',
		  'panel' => '',
          'text' => 'Schichtleiter Jobs',
          'parent' => 'manager'
       ],
    
      'leitung' => [
          'id' => 1432,
		  'query' => [ 'q' => 'Leitung'],
		   'tab' => 'Jobs von A - Z',
		   'panel' => '',
          'text' => 'Leitung Jobs',
          'parent' => 'manager'
       ],
      
    'casserolier' => [
          'id' => 1429,
		  'query' => [ 'q' => 'Casserolier'],
		   'tab' => 'Jobs von A - Z',
		     'panel' => '',
          'text' => 'Casserolier Jobs',
          'parent' => 'kueche'
       ],
    
    'kuechenhilfe' => [
          'id' => 1427,
		  'query' => [ 'q' => 'Küchenhilfe'],
		   'tab' => 'Jobs von A - Z',
		     'panel' => '',
          'text' => 'Küchenhilfe Jobs',
          'parent' => 'kueche'
       ],
      'betriebsleitung' => [
          'id' => 1424,
		  'query' => [ 'q' => 'Betriebsleitung'],
		   'tab' => 'Jobs von A - Z',
		     'panel' => '',
          'text' => 'Betriebsleiter Jobs'
       ],
       
       'stellvertreter' => [
          'id' => 1401,
		  'query' => [ 'q' => 'Stellvertretung'],
		   'tab' => 'Jobs von A - Z',
		     'panel' => '',
          'text' => 'Stellvertretung Jobs',
          'parent' => 'teilzeit'
     ],
       'alleinkoch' => [
          'id' => 1403,
		  'query' => [ 'q' => 'Alleinkoch'],
		    'panel' => '',
		   'tab' => 'Jobs von A - Z',
          'text' => 'Alleinkoch Jobs',
          'parent' => 'kueche'
     ],
       'allrounder' => [
          'id' => 1405,
		  'query' => [ 'q' => 'Allrounder'],
		      'panel' => '',
		   'tab' => 'Jobs von A - Z',
          'text' => 'Allrounder Jobs',
          'parent' => 'teilzeit'
     ],
       'chef-de-service' => [
          'id' => 1407,
		  'query' => [ 'q' => 'Chef de Service'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Chef de Service Jobs',
          'parent' => 'service'
     ],

    'fitness' => [
          'id' => 1363,
		  'query' => [ 'q' => 'Fitness'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Fitness Jobs'
     ],
    
    
    'teilzeit' => [
          'id' => 1330,
		  'query' => [ 'q' => 'Teilzeit'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Teilzeit Jobs'
     ],
    
    'restaurationsfachmann' => [
          'id' => 1325,
		  'query' => [ 'q' => 'Restaurationsfachmann'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Restaurationsfachmann Jobs',
          'parent' => 'service'
     ],
    'marketing-sales' => [
          'id' => 1327,
		  'query' => [ 'q' => 'Marketing'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Marketing Jobs',
          'parent' => 'administration-verkauf'
     ],

     'verkauf' => [
          'id' => 1312,
		  'query' => [ 'q' => 'Verkauf'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Jobs Verkauf',
          'parent' => 'administration-verkauf'
     ],
    
      'hotelfachfrau' => [
          'id' => 1309,
		  'query' => [ 'q' => 'Hotelfachfrau '],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Hotelfachfrau Jobs',
          'parent' => 'hotel'
     ],
     
     'saison' => [
          'id' => 1298,
		  'query' => [ 'q' => 'Saison'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Saison Jobs',
          'parent' => 'teilzeit'
     ],
    
       'sushi-koch' => [
          'id' => 1288,
		  'query' => [ 'q' => 'Sushi Koch'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Sushi Koch Jobs',
          'parent' => 'kueche'
     ],
    
      'ferienaushilfe' => [
          'id' => 1285,
		  'query' => [ 'q' => 'Ferienaushilfe'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Ferienjobs',
          'parent' => 'teilzeit'
     ],
    
      'take-away' => [
          'id' => 1282,
		  'query' => [ 'q' => 'Take Away'],
		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Take Away Jobs',
          'parent' => 'teilzeit'
     ],
    
      'catering' => [
          'id' => 1275,
		  'query' => [ 'q' => 'Catering'],
		  		   'tab' => 'Jobs von A - Z',
		       'panel' => '',
          'text' => 'Catering Jobs',
          'parent' => 'teilzeit'
     ],
       'promotion' => [
          'id' => 1279,
		  'query' => [ 'q' => 'Promotion'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Promotion Jobs',
          'parent' => 'teilzeit'
     ],
    
      'gouvernante' => [
          'id' => 1269,
		  'query' => [ 'q' => 'Gouvernante'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Gouvernante Jobs',
          'parent' => 'housekeeping'
     ],
     'kaeser-in' => [
          'id' => 1264,
		  'query' => [ 'q' => 'Käser'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Käser Käserin Jobs'
     ],
     'bereichsleiter' => [
          'id' => 1266,
		  'query' => [ 'q' => 'Bereichsleiter'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Bereichsleiter Jobs',
          'parent' => 'manager'
     ],
    'buffet-mitarbeiterin' => [
          'id' => 1260,
		  'query' => [ 'q' => 'Buffet'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Buffet Mitarbeiterin',
          'parent' => 'teilzeit'
     ],
    'commis-de-cuisine' => [
          'id' => 1220,
		  'query' => [ 'q' => 'Commis de Cuisine'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Commis de Cuisine Jobs',
          'parent' => 'kueche'
     ],
     'studenten' => [
          'id' => 1218,
		  'query' => [ 'q' => 'Studenten'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Studenten Jobs',
          'parent' => 'teilzeit'
     ],
    
    'chef-de-bar' => [
          'id' => 1216,
		  'query' => [ 'q' => 'Chef de Bar'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Chef de Bar Jobs',
          'parent' => 'service'
     ],
    
    'general-manager' => [
          'id' => 1214,
		  'query' => [ 'q' => 'General Manager'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'General Manager Jobs',
          'parent' => 'manager'
     ],
     'front-office' => [
          'id' => 1212,
		  'query' => [ 'q' => 'Front Office'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Front Office Jobs',
          'parent' => 'hotel'
     ],
     'lehrstellen-gastronomie' => [
          'id' => 1210,
		  'query' => [ 'q' => 'Lehrstelle'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Lehrstellen'
     ],
    'hilfskoch' => [
          'id' => 169,
		  'query' => [ 'q' => 'Hilfskoch'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Hilfskoch Jobs',
          'parent' => 'kueche'
     ],
    'alleinkoch' => [
          'id' => 171,
		  'query' => [ 'q' => 'Alleinkoch'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Alleinkoch Jobs',
          'parent' => 'kueche'
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
		  'query' => [ 'q' => 'Chef de Rang'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Chef de Rang Jobs',
          'parent' => 'service'
     ],
     'diaetkoch' => [
          'id' => 159,
		  'query' => [ 'q' => 'Diätkoch'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Diätkoch Jobs',
          'parent' => 'kueche'
     ],
     'entremetier' => [
          'id' => 936,
		  'query' => [ 'q' => 'Entremetier'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Entremetier Jobs',
          'parent' => 'kueche'
     ],
      'filialleiter' => [
          'id' => 235,
		  'query' => [ 'q' => 'Filialleiter'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Filialleiter Jobs',
          'parent' => 'manager'
     ],
      'garde-manger' => [
          'id' => 213,
		  'query' => [ 'q' => 'Garde Manger'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Garde Manger Jobs',
          'parent' => 'kueche'
     ],
      'hotelfachmann' => [
          'id' => 205,
		  'query' => [ 'q' => 'Hotelfachmann'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Hotelfachmann Jobs',
          'parent' => 'hotel'
     ],
     'jungkoch' => [
          'id' => 165,
		  'query' => [ 'q' => 'Jungkoch'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Jungkoch Jobs',
          'parent' => 'kueche'
     ],
     'koch' => [
          'id' => 167,
		  'query' => [ 'q' => 'Koch'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Koch Jobs',
         'parent' => 'kueche'
     ],
     'lebensmitteltechnologe' => [
          'id' => 183,
		  'query' => [ 'q' => 'Lebensmitteltechnologe'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Lebensmitteltechnologe Jobs',
           'parent' => 'food'
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
          'text' => 'Office Mitarbeiter Jobs',
          'parent' => 'administration-verkauf'
     ],
     'patissier' => [
          'id' => 217,
		  'query' => [ 'q' => 'Patissier'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Patissier Jobs',
          'parent' => 'kueche'
     ],
     'restauration' => [
          'id' => 189,
		  'query' => [ 'q' => 'Restauration'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Restaurationsfachfrau Jobs',
          'parent' => 'service'
     ], 
      'servicemitarbeiterin' => [
          'id' => 191,
		  'query' => [ 'q' => 'Servicemitarbeiterin'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Servicemitarbeiterin Jobs',
          'parent' => 'service',
          
     ],
      'zimmermaedchen' => [
          'id' => 215,
		  'query' => [ 'q' => 'Zimmermädchen'],
          'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Zimmermädchen Jobs',
          'parent' => 'housekeeping'
     ],
     
     'kuechenchef' => [
          'id' => 161,
		  'query' => [ 'q' => 'Küchenchef'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Küchenchef Jobs',
          'parent' => 'kueche'
     ],

     'kantine' => [
          'id' => 1977,
		  'query' => [ 'q' => 'Kantine'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Kantine Jobs',
          'parent' => 'kueche'
     ],
     
     'sous-chef' => [
          'id' => 163,
		  'query' => [ 'q' => 'Sous Chef'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Sous Chef Jobs',
          'parent' => 'kueche'
     ],
       'systemgastronomie' => [
          'id' => 1045,
		  'query' => [ 'q' => 'Systemgastronomie'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Systemgastronomie Jobs',
          'parent' => 'kueche'
     ],
       'chef-de-partie' => [
          'id' => 1052,
		  'query' => [ 'q' => 'Chef de Partie'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Chef de Partie Jobs',
          'parent' => 'kueche'
     ],
      'tournant' => [
          'id' => 1048,
		  'query' => [ 'q' => 'Tournant'],
		   'tab' => 'Jobs von A - Z',
          'panel' => '',
          'text' => 'Tournant Jobs',
          'parent' => 'kueche'
     ],
      'servicemitarbeiter' => [
          'id' => 1096,
		  'query' => [ 'q' => 'Servicemitarbeiter'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Servicemitarbeiter Jobs',
          'parent' => 'service'
     ],
	 'pizzaiolo' => [
          'id' => 1098,
		  'query' => [ 'q' => 'Pizzaiolo'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Pizzaiolo Jobs',
          'parent' => 'kueche'
     ],
	 'rezeptionistin' => [
          'id' => 219,
		  'query' => [ 'q' => 'Rezeptionistin'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Rezeptionistin Jobs',
          'parent' => 'hotel'
     ],	 
	  'konditor-confiseur' => [
          'id' => 175,
		  'query' => [ 'q' => 'Konditor'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Konditor Confiseur Jobs'
     ],
	   'restaurant-manager' => [
          'id' => 199,
		  'query' => [ 'q' => 'Restaurant Manager'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Restaurant Manager Jobs',
          'parent' => 'manager'
     ],
	 'ernaehrungsberater' => [
          'id' => 181,
		  'query' => [ 'q' => 'Ernährungsberater'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Ernährungsberater Jobs',
          'parent' => 'food'
     ],	
	 'barkeeper' => [
          'id' => 195,
		  'query' => [ 'q' => 'Barkeeper'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Barkeeper Jobs',
          'parent' => 'service'
     ],
	  'barista' => [
          'id' => 197,
		  'query' => [ 'q' => 'Barista'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Barista Jobs',
          'parent' => 'service'
     ], 
	  'sommelier' => [
          'id' => 201,
		  'query' => [ 'q' => 'Sommelier'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Sommelier Jobs',
          'parent' => 'service'
     ],
	 
	  'assistant' => [
          'id' => 231,
		  'query' => [ 'q' => 'Assistant'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Assistant Jobs',
          'parent' => 'manager'
     ],
	
	  'teilzeitverkaeuferin' => [
          'id' => 243,
		  'query' => [ 'q' => 'Teilzeitverkäuferin'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Teilzeitverkäuferin Jobs',
          'parent' => 'teilzeit'
     ],
	 'kosmetikerin' => [
          'id' => 209,
		  'query' => [ 'q' => 'Kosmetikerin'],
		   'tab' => 'Jobs von A - Z',
		    'panel' => '',
          'text' => 'Kosmetikerin Jobs'
     ],
    
	 'region-appenzell-ausserrhoden' => [
        'text' => 'Appenzell Ausserrhoden',
        'id' => 316,
        'query' => [ 'region_MultiString' => ['Appenzell Ausserrhoden' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	 'region-appenzell-innerrhoden' => [
        'text' => 'Appenzell Innerrhoden',
        'id' => 318,
        'query' => [ 'region_MultiString' => ['Appenzell Innerrhoden' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
		'region-glarus' => [
        'text' => 'Glarus',
        'id' => 320,
        'query' => [ 'region_MultiString' => ['Glarus' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],

		'region-schaffhausen' => [
        'text' => 'Schaffhausen',
        'id' => 325,
        'query' => [ 'region_MultiString' => ['Schaffhausen' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
		'region-st-gallen' => [
        'text' => 'St. Gallen',
        'id' => 327,
        'query' => [ 'region_MultiString' => ['Sankt Gallen' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-thurgau' => [
        'text' => 'Thurgau',
        'id' => 329,
        'query' => [ 'region_MultiString' => ['Thurgau' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-graubuenden' => [
        'text' => 'Graubünden',
        'id' => 331,
        'query' => [ 'region_MultiString' => ['Graubünden' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-aargau' => [
        'text' => 'Aargau',
        'id' => 333,
        'query' => [ 'region_MultiString' => ['Aargau' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-basel-landschaft' => [
        'text' => 'asel-Landschaft',
        'id' => 335,
        'query' => [ 'region_MultiString' => ['Basel-Landschaft' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-basel-stadt' => [
        'text' => 'Basel-Stadt',
        'id' => 337,
        'query' => [ 'region_MultiString' => ['Basel-Stadt' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-bern' => [
        'text' => 'Bern',
        'id' => 339,
        'query' => [ 'region_MultiString' => ['Bern' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-jura' => [
        'text' => 'Jura',
        'id' => 341,
        'query' => [ 'region_MultiString' => ['Jura' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	   'region-solothurn' => [
        'text' => 'Solothurn',
        'id' => 343,
        'query' => [ 'region_MultiString' => ['Solothurn' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	 
	  'region-freiburg' => [
        'text' => 'Freiburg',
        'id' => 346,
        'query' => [ 'region_MultiString' => ['Freiburg' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	  'region-genf' => [
        'text' => 'Genf',
        'id' => 348,
        'query' => [ 'region_MultiString' => ['Genf' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	  'region-neuenburg' => [
        'text' => 'Neuenburg',
        'id' => 350,
        'query' => [ 'region_MultiString' => ['Neuenburg' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	  'region-waadt' => [
        'text' => 'Waadt',
        'id' => 352,
        'query' => [ 'region_MultiString' => ['Waadt' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	  'region-wallis' => [
        'text' => 'Wallis',
        'id' => 354,
        'query' => [ 'region_MultiString' => ['Wallis' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	  'region-luzern' => [
        'text' => 'Luzern',
        'id' => 356,
        'query' => [ 'region_MultiString' => ['Luzern' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	 'region-nidwalden' => [
        'text' => 'JNidwalden',
        'id' => 358,
        'query' => [ 'region_MultiString' => ['Nidwalden' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
		'region-obwalden' => [
        'text' => 'Obwalden',
        'id' => 360,
        'query' => [ 'region_MultiString' => ['Obwalden' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	'region-schwyz' => [
        'text' => 'Schwyz',
        'id' => 362,
        'query' => [ 'region_MultiString' => ['Schwyz' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	 'region-uri' => [
        'text' => 'Uri',
        'id' => 364,
        'query' => [ 'region_MultiString' => ['Uri' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	 'region-zug' => [
        'text' => 'Zug',
        'id' => 366,
        'query' => [ 'region_MultiString' => ['Zug' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],
	'region-zurich' => [
        'text' => 'Zürich',
        'id' => 368,
        'query' => [ 'region_MultiString' => ['Zürich' => 1]],
        'tab' => '',
        'panel' => '',
    ],
	'region-tessin' => [
        'text' => 'Tessin',
        'id' => 370,
        'query' => [ 'region_MultiString' => ['Tessin' => 1]],
        'tab' => 'Jobs nach Kanton',
        'panel' => 'Schweiz',
    ],

	
	/*
    'stadt-zuerich' => [
        'text' => 'Stadt Zürich',
        'id' => 2,
        'query' => [ 'city_MultiString' => ['Zürich' => 1]],
        'tab' => '',
        'panel' => '',
        'parent' => 'region-zurich'
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
