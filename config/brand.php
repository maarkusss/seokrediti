<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vietnes Zīmola un Identitātes Konfigurācija (White-Label Brand Config)
    |--------------------------------------------------------------------------
    |
    | Šeit vari vienuviet nomainīt mājaslapas nosaukumu, saukli, logotipu un
    | kontaktinformāciju. Šīs vērtības var pārrakstīt arī Admin panelī:
    | /lv/admincp/settings -> sadaļā "Zīmols un identitāte" vai .env failā!
    |
    */

    // Mājaslapas galvenais nosaukums (ja vēl nav izdomāts, strādā neitrāls noklusējums)
    'name' => env('BRAND_NAME', env('APP_NAME', 'Credito.lv')),

    // Īss apakšvirsraksts / sauklis zem vai blakus logotipam
    'tagline' => env('BRAND_TAGLINE', 'Neatkarīgs kredītu salīdzinājums'),

    // Ceļš uz pielāgotu logotipa attēlu (piem. '/images/brand/logo.svg' vai '/images/brand/logo.png').
    // Atstāj tukšu (''), lai automātiski izmantotu moderno SVG ikonu + teksta logotipu!
    'logo_url' => env('BRAND_LOGO_URL', ''),

    // Vai rādīt arī teksta nosaukumu blakus attēla logotipam (ja logo_url ir norādīts)
    'show_name_with_logo' => env('BRAND_SHOW_NAME_WITH_LOGO', false),

    // Noklusējuma OpenGraph / sociālo tīklu priekšskatījuma attēls
    'og_image' => env('BRAND_OG_IMAGE', '/images/brand/og-cover.svg'),

    // Kontaktinformācija (lietota kājenē, kontaktu lapā un Schema.org strukturētajos datos)
    'email' => env('BRAND_EMAIL', 'info@credito.lv'),

    // Atbildīgas aizņemšanās brīdinājums (PTAC prasībām atbilstošs informatīvs teksts)
    'disclaimer' => env(
        'BRAND_DISCLAIMER',
        'Aizņemies atbildīgi, izvērtējot savas iespējas atmaksāt kredītu! Vietnē apkopotā informācija ir informatīva un nav uzskatāma par finanšu konsultāciju.'
    ),

    // Juridiskā / redakcijas informācija kājenē (neobligāta)
    'editorial_note' => env(
        'BRAND_EDITORIAL_NOTE',
        'Neatkarīga finanšu un patēriņa kredītu salīdzināšanas platforma Latvijā. Salīdzināšanas pakalpojums apmeklētājiem ir 100% bez maksas.'
    ),

];
