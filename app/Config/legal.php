<?php
/**
 * Legal / privacy settings used by privacy.php, terms.php, cookie-policy.php
 * and the consent checkboxes.
 *
 * >>> EDIT THIS FILE. Any value that still starts with "[" is a placeholder
 * >>> and is shown highlighted on the public pages until you replace it.
 */
return [
    'org_name'      => 'Barangay San Pedro',
    'org_full'      => 'Barangay San Pedro, Iriga City, Camarines Sur',
    'system_name'   => 'Barangay San Pedro Management Information System (BMIS)',
    'site_url'      => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'your-site'),
    'effective'     => 'October 29, 2026',
    'last_updated'  => 'October 29, 2026',

    // Privacy contact person. A barangay does not need its own Data Protection Officer: under NPC Advisory 2017-01
    // and DILG circulars it may designate a "Compliance Officer for Privacy" (COP) who works under the
    // Data Protection Officer of the city/municipality. Put the COP's details below.
    'dpo_name'      => '[Name of Compliance Officer for Privacy]',
    'dpo_email'     => '[dpo-email@example.com]',
    'dpo_phone'     => '[Contact number]',
    // Optional line shown under the contact details. Edit or set to '' to hide it.
    'city_dpo_line' => 'Our Compliance Officer for Privacy works under the supervision of the Data Protection Officer of the City Government of Iriga.',
    'office_address'=> '[Barangay Hall address], Barangay San Pedro, Iriga City, Camarines Sur',

    // How long records are kept
    'retention_residents'  => '[number of years / "as long as the resident record is active"]',
    'retention_requests'   => '[number of years]',
    'retention_complaints' => '[number of years]',
    'retention_logs'       => '[number of days/months]',
];
