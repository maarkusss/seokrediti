# Kredītu Salīdzināšanas Platforma (SEO Fintech CMS)

Moderna, ātra un SEO optimizēta kredītu salīdzināšanas mājaslapa, kas izstrādāta uz **Laravel 10**, **Tailwind CSS** un **Alpine.js** bāzes.

## Kā ātri nomainīt mājaslapas nosaukumu un logotipu (White-Label)

Visas zīmola vērtības ir centralizētas un viegli maināmas divos veidos (bez koda pārrakstīšanas šablonos):

### 1. variants: Admin panelī (`/lv/admincp/settings`)
Atveriet **Settings -> Zīmols un identitāte**:
- **Mājaslapas nosaukums (`name`)** — automātiski atjaunojas navigācijā, kājenē, SEO `<title>` birkās, Schema.org strukturētajos datos, `llms.txt` un pāradresācijas (`/go`) ekrānā.
- **Zīmola sauklis (`brand_tagline`)** — īss paraksts pie logotipa un kājenē.
- **Logotipa augšupielāde vai ceļš (`brand_logo`)** — augšupielādējiet savu `.svg`, `.png` vai `.webp` logotipu. Ja logotips vēl nav izveidots (atstāts tukšs), sistēma automātiski ģenerē modernu vektorgrafikas (SVG) finanšu emblēmu kopā ar vietnes nosaukumu.
- **Kontaktinformācijas e-pasts (`contact_email`)** — publiskais saziņas e-pasts.

### 2. variants: `.env` vai `config/brand.php` failā
```env
APP_NAME="TavsJaunaisNosaukums.lv"
BRAND_NAME="TavsJaunaisNosaukums.lv"
BRAND_TAGLINE="Neatkarīgs kredītu salīdzinājums"
BRAND_LOGO_URL="/images/brand/mans-logo.svg"
BRAND_EMAIL="info@tavslapaspasts.lv"
```

## Lokālā palaišana

```bash
composer install
npm install
npm run build
php artisan migrate --seed
php artisan serve
```

## SEO arhitektūra
- Tīra semantiska HTML5 struktūra bez Bootstrap 3 / jQuery atkarībām.
- Automātiski `Schema.org` JSON-LD strukturētie dati (`WebSite`, `Organization`, `BreadcrumbList`, `ItemList` ar `LoanOrCredit`, `FAQPage`, `Blog`, `BlogPosting`, `FinancialService` + `AggregateRating`).
- Dinamisks `/sitemap.xml` un `php artisan sitemap:generate` komanda, `/robots.txt` un `/llms.txt`.
- Pilns Core Web Vitals fokuss un YMYL / PTAC atbildīgas aizņemšanās vadlīniju ievērošana (GPL, reprezentatīvie piemēri, redakcionālā metodoloģija).
