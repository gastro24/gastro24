<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */

/*
 * This file is used to create the professions initially. If you want to modify professions copy this file into
 * the config/autoload directory and adjust the categories.
 *
 * The categories are imported, if there is no "jobs.categories" collection. So after you've modifies the categories
 * drop your "jobs.categories" and reload a YAWIK page, which accesses categories.
 *
 * This file ist only used, if there ist no "config/autoload/jobs.categories.professions.php" available.
 *
 * Format:
 * [
 *      'name' => <name>, [required]
 *      'value' => <value>, [optional]
 *      'children' => [ // optional
 *          <name> // strings will be treated as ['name' => <name>]
 *          [
 *              'name' => <name>, [required]
 *              'value' => <value>, [optional]
 *              'children' => [ ... ]
 *       ]
 * ]
 */

return [
    'name' => 'Professions',
    'children' => [
        'Animateur',
        'Area Manager Service & Reception',
        'Assistent / Assistentin',
        'Bäcker / Bäckerin',
        'Bäckereifachverkäufer',
        'Bankettassistent',
        'Barista',
        'Bereichsleiter',
        'Betriebsleiter',
        'Branch Manager',
        'Brauer',
        'Buchhalter',
        'Bürokauffrau / Bürokaufmann',
        'Call Center Agent',
        'Casino Dealer',
        'Chauffeur',
        'Chef de Cuisine',
        'Chef de Partie',
        'Coiffeur',
        'Commis de Cuisine',
        'Concierge',
        'Dekorateur',
        'Demichef de Rang',
        'Diätkoch / Diätköchin',
        'Direktor',
        'Einkäufer',
        'Entremetier',
        'Eventmanager',
        'F&B Manager',
        'Fachmann /-frau Systemgastronomie',
        'Fitness-Trainer',
        'Florist / Floristin',
        'Franchisepartner',
        'Friseur / Friseurin',
        'Gärtner / Gärtnerin',
        'Gebäudereiniger',
        'Geschäftsführer',
        'Hausdame / Haushälterin',
        'Hausmeister',
        'Hoteldirektor',
        'Hotelfachmann / Hotelfachfrau',
        'Hotelkaufmann / Hotelkauffrau',
        'Housekeeper',
        'Jungkoch',
        'Junior Chef',
        'Kantinenkoch',
        'Kapitän',
        'Kaufmann / Kauffrau',
        'Key Account Manager',
        'Kindergärtner',
        'Konditormeister',
        'Kosmetiker / Kosmetikerin',
        'Küchenchef',
        'Küchenhilfe',
        'Kundenberater',
        'Lagermitarbeiter',
        'Lehrling',
        'Logistiker',
        'Masseur / Masseurin',
        'Medizinischer Masseur / Masseurin',
        'Metzger',
        'Musiker',
        'Nachtpage',
        'Oberkellner',
        'Office Manager',
        'Pächter',
        'Patissier',
        'Physiotherapeut',
        'Pizzabäcker',
        'Postenchef',
        'Projektmanager',
        'Putzfrau',
        'Receptionist',
        'Reservierungsmanager',
        'Restaurantfachfrau / Restaurantfachmann',
        'Restaurantleiter',
        'Rezeptionist',
        'Sachbearbeiter',
        'Saunameister',
        'Schneider',
        'Sekretär',
        'Servicemitarbeiter',
        'Spüler',
        'Stellv. Küchenchef',
        'Studentische Aushilfe',
        'Surflehrer',
        'Sushi Chef',
        'Tänzer',
        'Tanzlehrer',
        'Tauchlehrer',
        'Techniker',
        'Türsteher',
        'Weinfachberater',
        'Wellness- / Fitnesstrainer',
        'Zimmermädchen / Roomboy',
        'Sonstiges'
    ]
];

