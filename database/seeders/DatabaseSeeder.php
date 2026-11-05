<?php

namespace Database\Seeders;

use App\Models\Admincp\Faqs\Faqs;
use App\Models\Admincp\Faqs\FaqSections;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersCategories;
use App\Models\Admincp\Lenders\LendersData;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Settings;
use App\Models\Reviews;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with white-label settings, categories,
     * licensed Latvian lenders, editorial guides, FAQs, and verified reviews.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seedSettings();
        $this->seedAdminAndPermissions();
        $loanTypes = $this->seedLoanTypes();
        $lenders = $this->seedLenders($loanTypes);
        $sections = $this->seedSections();
        $this->seedFaqs($sections, $loanTypes);
        $this->seedReviews($lenders);
    }

    private function seedSettings(): void
    {
        $defaults = [
            'name' => config('brand.name', 'Credito.lv'),
            'brand_tagline' => config('brand.tagline', 'Neatkarīgs kredītu salīdzinājums'),
            'contact_email' => config('brand.email', 'info@credito.lv'),
            'brand_disclaimer' => config('brand.disclaimer', 'Aizņemies atbildīgi, izvērtējot savas iespējas kredītu atmaksāt!'),
            'lang' => 'lv',
            'prefix' => 'krediti',
            'api_key' => 'portal_local_api_key',
            'individual_days_epc' => '30',
            'sitemap' => config('app.url', 'http://localhost'),
            'country_code' => '371',
            'phone_length' => '8',
            'cookie' => 'true',
        ];

        foreach ($defaults as $key => $value) {
            Settings::updateOrCreate(
                ['name' => $key],
                ['value' => $value]
            );
        }
    }

    private function seedAdminAndPermissions(): void
    {
        $permissions = [
            'view admins', 'edit admins', 'add admins', 'delete admins', 'edit permissions',
            'view adminlogs',
            'view bans', 'edit bans', 'add bans', 'delete bans',
            'view sections', 'edit sections', 'add sections', 'delete sections',
            'view visitors',
            'view settings', 'edit settings', 'edit settings config',
            'view loantypes', 'edit loantypes', 'add loantypes', 'delete loantypes',
            'view lenders', 'edit lenders', 'add lenders', 'delete lenders',
            'view consumers', 'delete consumers',
            'view statistics',
            'view faqs', 'edit faqs', 'add faqs', 'delete faqs',
            'view reviews', 'delete reviews',
            'view redirectlinks', 'edit redirectlinks', 'add redirectlinks', 'delete redirectlinks',
            'view connected urls', 'edit connected urls', 'add connected urls',
            'view images', 'add images', 'delete images',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
            ]);
        }

        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'first_name' => 'Portāla',
                'last_name' => 'Administrators',
                'email' => 'info@credito.lv',
                'password' => Hash::make('password'),
            ]
        );

        $admin->syncPermissions($permissions);
    }

    private function seedLoanTypes(): array
    {
        $atrie = LoanTypes::updateOrCreate(
            ['route_name' => 'atrie-krediti', 'lang' => 'lv'],
            [
                'parent_type_id' => null,
                'order' => 1,
                'name' => 'Ātrie kredīti',
                'title' => 'Ātrie kredīti internetā 2026 — Salīdzini GPL un nosacījumus',
                'description' => 'Salīdzini licencētus ātros kredītus un elastīgās kredītlīnijas Latvijā. Pārskati procentu likmes (GPL), pirmo aizdevumu ar 0% atlaidi un izmaksas laiku.',
                'anchor_element_title' => 'Ātrie kredīti internetā',
                'h1' => 'Ātrie kredīti un kredītlīnijas internetā',
                'h1_description' => 'Objektīvs licencēto nebanku aizdevēju salīdzinājums — salīdzini GPL likmes, pirmā kredīta nosacījumus un naudas saņemšanas ātrumu vienā tabulā.',
                'text' => '<h2>Kas ir ātrie kredīti un kā tie darbojas šodien?</h2>
<p>Mūsdienās <strong>ātrie kredīti Latvijā</strong> lielākoties darbojas kā elastīgas kredītlīnijas vai īstermiņa patēriņa aizdevumi ar skaidru atmaksas grafiku. Pēc Patērētāju tiesību aizsardzības likuma grozījumiem visi licencētie aizdevēji ievēro stingrus kopējo izmaksu griestus un rūpīgi izvērtē katra klienta maksātspēju.</p>
<h3>Galvenie kritēriji izdevīgākā piedāvājuma izvēlei</h3>
<ul>
<li><strong>Gada procentu likme (GPL):</strong> rādītājs, kas ietver gan procentus, gan komisijas maksas par konta uzturēšanu vai izsniegšanu.</li>
<li><strong>0% pirmais kredīts jaunajiem klientiem:</strong> vairāki aizdevēji piedāvā pirmo izmaksas periodu (30 līdz 60 dienas) bez procentu maksas, ja atmaksa tiek veikta precīzi termiņā.</li>
<li><strong>Pirmstermiņa atmaksa:</strong> saskaņā ar Latvijas likumdošanu patērētājam ir tiesības atmaksāt aizdevumu pilnībā vai daļēji jebkurā brīdī, maksājot procentus tikai par faktiski izmantotajām dienām.</li>
</ul>
<h3>Kā pieteikties ātrajam kredītam tiešsaistē?</h3>
<p>Pieteikšanās process aizņem vidēji 5 līdz 15 minūtes: izvēlies aizdevēju salīdzināšanas sarakstā, aizpildi pieteikumu aizdevēja mājaslapā, apstiprini identitāti ar Smart-ID vai internetbanku un sagaidi individuālo piedāvājumu. Ja konts ir vienā no lielākajām Latvijas bankām, pārskaitījums parasti notiek 15 minūšu laikā darba laikā.</p>',
                'active' => 1,
                'display_dropdown' => 1,
            ]
        );

        $no18 = LoanTypes::updateOrCreate(
            ['route_name' => 'no-18-gadiem', 'lang' => 'lv'],
            [
                'parent_type_id' => $atrie->id,
                'order' => 1,
                'name' => 'Kredīti no 18 gadiem',
                'title' => 'Kredīti no 18 gadiem internetā — Aizdevēju salīdzinājums jauniešiem',
                'description' => 'Pārskati licencētus aizdevējus Latvijā, kas izskata pieteikumus no 18 vai 19 gadu vecuma ar oficiāliem un pierādāmiem ienākumiem.',
                'anchor_element_title' => 'Kredīti no 18 gadiem',
                'h1' => 'Kredīti un aizdevumi no 18 gadu vecuma',
                'h1_description' => ' noskaidro, kuri licencētie kredītdevēji piedāvā finansējumu pilngadīgiem jauniešiem no 18 gadiem un kādas maksātspējas prasības tiek piemērotas.',
                'text' => '<h2>Vai Latvijā ir pieejams kredīts no 18 gadiem?</h2>
<p>Lielākā daļa nebanku aizdevēju Latvijā nosaka minimālo vecuma slieksni 20 gadi, tomēr atsevišķi licencēti aizdevēji un salīdzināšanas platformas izskata pieteikumus jau no <strong>18 gadu vecuma</strong>. Galvenais priekšnoteikums nav vecums pats par sevi, bet gan stabili, oficiāli ienākumi un pozitīva finanšu disciplīna.</p>
<h3>Prasības aizņēmējiem 18–20 gadu vecumā</h3>
<ul>
<li>Latvijas Republikas pilsoņa vai pastāvīgā iedzīvotāja statuss un konts Latvijas komercbankā.</li>
<li>Regulāri, dokumentāli apliecināmi ienākumi (darba alga, stipendija kopā ar darba algu vai saimnieciskās darbības ienākumi).</li>
<li>Nav kavētu maksājumu vai aktīvu parādsaistību parādu vēstures datubāzēs (CREFO, Paus Konsults, Latvijas Bankas Kredītu reģistrs).</li>
</ul>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $uz12 = LoanTypes::updateOrCreate(
            ['route_name' => 'uz-12-menesiem', 'lang' => 'lv'],
            [
                'parent_type_id' => $atrie->id,
                'order' => 2,
                'name' => 'Kredīti uz 12 mēnešiem',
                'title' => 'Kredīti uz 12 mēnešiem — Aizdevumi ar ērta grafika ikmēneša maksājumu',
                'description' => 'Salīdzini aizdevumus ar 12 mēnešu atmaksas termiņu. Vienmērīgi sadalīts ikmēneša maksājums bez pārmērīgas slodzes budžetam.',
                'anchor_element_title' => 'Kredīti uz 12 mēnešiem',
                'h1' => 'Aizdevumi ar atmaksas termiņu līdz 12 mēnešiem',
                'h1_description' => 'Sabalansēts risinājums vidēja apjoma pirkumiem vai remontam — sadali summu 12 pārskatāmos ikmēneša maksājumos.',
                'text' => '<h2>Kāpēc izvēlēties 12 mēnešu atmaksas grafiku?</h2>
<p>Aizdevums uz 12 mēnešiem ļauj izvairīties no liela vienreizēja maksājuma mēneša beigās, vienlaikus nepārmaksājot procentos par gariem piecu vai septiņu gadu termiņiem. Salīdzini piedāvājumus un pievērs uzmanību GPL likmei un iespējai veikt papildu iemaksas bez komisijas.</p>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $sliktaVesture = LoanTypes::updateOrCreate(
            ['route_name' => 'ar-sliktu-kreditvesturi', 'lang' => 'lv'],
            [
                'parent_type_id' => $atrie->id,
                'order' => 3,
                'name' => 'Ar sabojātu kredītvēsturi',
                'title' => 'Kredīts ar sabojātu kredītvēsturi — Reālās iespējas un risinājumi',
                'description' => 'Godīgs skaidrojums par aizņemšanās iespējām ar negatīvu kredītvēsturi Latvijā, pārkreditāciju un kredītvēstures uzlabošanas soļiem.',
                'anchor_element_title' => 'Kredīti ar sliktu kredītvēsturi',
                'h1' => 'Aizdevumi un risinājumi ar sabojātu kredītvēsturi',
                'h1_description' => 'Kā rīkoties, ja agrāk bijuši kavēti maksājumi, un kādi licencēti risinājumi (piemēram, saistību apvienošana vai kredīts ar ķīlu/galvotāju) ir pieejami.',
                'text' => '<h2>Kā tiek vērtēta negatīva kredītvēsture?</h2>
<p>Licencētiem aizdevējiem Latvijā ir likumā noteikts pienākums pārbaudīt klienta maksātspēju. Ja Tev ir aktīvi kavēti parādi, jauns nenodrošināts ātrais kredīts netiks un nedrīkst tikt izsniegts — tas pasargā patērētāju no parādu spirāles. Savukārt, ja pagātnes kavējumi ir pilnībā segti pirms vairāk nekā 6–12 mēnešiem un ienākumi ir stabili, atsevišķi kreditori pieteikumu izvērtē individuāli.</p>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $paterina = LoanTypes::updateOrCreate(
            ['route_name' => 'paterina-krediti', 'lang' => 'lv'],
            [
                'parent_type_id' => null,
                'order' => 2,
                'name' => 'Patēriņa kredīti',
                'title' => 'Patēriņa kredīti internetā līdz 25 000 € — Salīdzini likmes',
                'description' => 'Salīdzini izdevīgākos patēriņa kredītus Latvijā no 500 € līdz 25 000 € ar fiksētu ikmēneša maksājumu un zemu gada procentu likmi.',
                'anchor_element_title' => 'Patēriņa kredīti',
                'h1' => 'Patēriņa kredīti lielākiem pirkumiem un mērķiem',
                'h1_description' => 'Salīdzini banku un nebanku patēriņa kredītus līdz 25 000 € mājokļa labiekārtošanai, veselībai, izglītībai vai tehnikas iegādei bez ķīlas.',
                'text' => '<h2>Kad patēriņa kredīts ir vispiemērotākais risinājums?</h2>
<p><strong>Patēriņa kredīts</strong> ir vidēja vai ilgtermiņa aizdevums bez ķīlas ar fiksētu vai mainīgu gada procentu likmi un skaidru ikmēneša atmaksas grafiku no 6 mēnešiem līdz pat 7–10 gadiem. Tas ir būtiski izdevīgāks par īstermiņa kredītlīniju gadījumos, kad plānotā summa pārsniedz 1 000 €.</p>
<h3>Patēriņa kredīta priekšrocības</h3>
<ul>
<li><strong>Zemāka procentu likme:</strong> gada likmes labas kredītvēstures gadījumā sākas no 6,9%–9,9% gadā.</li>
<li><strong>Nav nepieciešama nekustamā īpašuma ķīla:</strong> līdzekļus var izmantot pēc saviem ieskatiem bez atskaitīšanās par pirkuma čekiem.</li>
<li><strong>Prognozējams budžets:</strong> fiksēts ikmēneša datums un konstanta maksājuma summa visā līguma darbības laikā.</li>
</ul>',
                'active' => 1,
                'display_dropdown' => 1,
            ]
        );

        $ilgtermina = LoanTypes::updateOrCreate(
            ['route_name' => 'ilgtermina', 'lang' => 'lv'],
            [
                'parent_type_id' => $paterina->id,
                'order' => 1,
                'name' => 'Ilgtermiņa kredīti',
                'title' => 'Ilgtermiņa kredīti līdz 10 gadiem — Zems ikmēneša maksājums',
                'description' => 'Salīdzini ilgtermiņa patēriņa aizdevumus ar termiņu no 2 līdz 10 gadiem mājokļa remontam vai lielākiem ģimenes plāniem.',
                'anchor_element_title' => 'Ilgtermiņa kredīti',
                'h1' => 'Ilgtermiņa patēriņa aizdevumi ar samazinātu mēneša maksājumu',
                'h1_description' => 'Atrodi ilgtermiņa finansējumu līdz 25 000 € ar atmaksas termiņu līdz 84–120 mēnešiem un elastīgu pirmstermiņa atmaksu.',
                'text' => '<h2>Kā izvēlēties optimālu ilgtermiņa aizdevuma periodu?</h2>
<p>Izvēloties garāku termiņu, samazinās ikmēneša maksājums, taču pieaug kopējā procentos samaksātā summa. Izmanto mūsu kredīta kalkulatoru lapas augšdaļā, lai atrastu līdzsvaru starp komfortablu mēneša maksājumu un minimālu kopējo pārmaksu.</p>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $apvienosana = LoanTypes::updateOrCreate(
            ['route_name' => 'kreditu-apvienosana', 'lang' => 'lv'],
            [
                'parent_type_id' => null,
                'order' => 3,
                'name' => 'Kredītu apvienošana',
                'title' => 'Kredītu apvienošana vienā maksājumā — Pārkreditācija ar zemāku GPL',
                'description' => 'Apvieno vairākus dārgus kredītus vai kredītlīnijas vienā pārskatāmā ikmēneša maksājumā ar zemāku procentu likmi līdz 25 000 €.',
                'anchor_element_title' => 'Kredītu apvienošana',
                'h1' => 'Kredītu apvienošana un pārkreditācija vienā maksājumā',
                'h1_description' => 'Samazini ikmēneša finanšu slogu — aizstāj vairākus mazus aizdevumus ar vienu izdevīgāku līgumu un zemāku gada procentu likmi.',
                'text' => '<h2>Kā darbojas kredītu apvienošana (refinansēšana)?</h2>
<p>Ja ik mēnesi jāapmaksā vairāki rēķini dažādiem kreditoriem ar augstām procentu likmēm, <strong>kredītu apvienošana</strong> ļauj visus esošos atlikumus segt ar vienu jaunu ilgtermiņa aizdevumu. Rezultātā Tu veic tikai vienu maksājumu mēnesī un bieži ietaupi ievērojamu summu uz procentu starpības rēķina.</p>
<h3>Kad ir vērts apsvērt pārkreditāciju?</h3>
<ul>
<li>Tev ir 2 vai vairāk aktīvi ātrie kredīti, kredītlīnijas vai līzinga līgumi.</li>
<li>Esošo saistību vidējā GPL pārsniedz 25%–35% gadā, bet jaunais piedāvājums piedāvā 9%–18% gadā.</li>
<li>Vēlies vienu konkrētu maksājuma datumu algas dienā un skaidru datumu, kurā visas saistības būs pilnībā dzēstas.</li>
</ul>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $bezprocentu = LoanTypes::updateOrCreate(
            ['route_name' => 'bezprocentu-krediti', 'lang' => 'lv'],
            [
                'parent_type_id' => null,
                'order' => 4,
                'name' => 'Bezprocentu kredīti',
                'title' => 'Bezprocentu kredīti (0%) jaunajiem klientiem — Salīdzinājums',
                'description' => 'Apskati visus aizdevējus Latvijā, kas piedāvā pirmo kredītu ar 0% procentu likmi līdz 30 vai 60 dienām jaunajiem klientiem.',
                'anchor_element_title' => 'Bezprocentu kredīti',
                'h1' => 'Bezprocentu kredīti — pirmais aizdevums ar 0% atlaidi',
                'h1_description' => 'Salīdzini licencētus aizdevējus, pie kuriem jaunie klienti pirmajā periodā atmaksā tieši tik, cik aizņēmās — ievērojot līguma termiņu.',
                'text' => '<h2>Ko nozīmē 0% bezprocentu kredīts praksē?</h2>
<p>Jaunajiem klientiem vairāki Latvijas nebanku kreditētāji piedāvā 100% komisijas un procentu atlaidi pirmajām 30 vai 60 dienām. Tas nozīmē: aizņemoties, piemēram, 500 € un atmaksājot pilnu summu noteiktajā bezprocentu periodā, kopējā atmaksājamā summa ir precīzi 500 €.</p>
<p><strong>Svarīgi atcerēties:</strong> ja pilna summa netiek atmaksāta akcijas periodā, turpmākajam atlikumam tiek piemērota standarta līguma procentu likme. Tāpēc vienmēr iepazīsties ar standarta GPL pirms līguma parakstīšanas.</p>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $auto = LoanTypes::updateOrCreate(
            ['route_name' => 'auto-lizings', 'lang' => 'lv'],
            [
                'parent_type_id' => null,
                'order' => 5,
                'name' => 'Auto kredīti',
                'title' => 'Auto kredīts un auto līzings Latvijā — Salīdzini bez pirmās iemaksas',
                'description' => 'Salīdzini izdevīgākos auto kredītus un auto līzinga piedāvājumus lietota vai jauna auto iegādei līdz 25 000 € bez obligātas KASKO apdrošināšanas.',
                'anchor_element_title' => 'Auto kredīts un līzings',
                'h1' => 'Auto kredīts un auto līzings automašīnas iegādei',
                'h1_description' => 'Salīdzini finansējumu automašīnas iegādei — auto kredīts bez pirmās iemaksas un bez KASKO vai klasiskais līzings jaunākiem spēkratiem.',
                'text' => '<h2>Auto kredīts vai auto līzings — kāda ir atšķirība?</h2>
<p>Izvēloties <strong>auto kredītu</strong>, automašīna uzreiz tiek reģistrēta uz Tava vārda CSDD, parasti nav nepieciešama pirmā iemaksa un nav obligāta KASKO apdrošināšana, turklāt auto izvēlei tiek dotas 30–60 dienas pēc naudas saņemšanas. Savukārt <strong>auto līzings</strong> biežāk piemērots dārgākām un jaunākām automašīnām ar zemāku procentu likmi, bet ar 10% pirmo iemaksu un KASKO polisi.</p>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        $sms = LoanTypes::updateOrCreate(
            ['route_name' => 'sms-krediti', 'lang' => 'lv'],
            [
                'parent_type_id' => null,
                'order' => 6,
                'name' => 'Kredītlīnija',
                'title' => 'Kredītlīnija un SMS kredīti internetā — Elastīgs naudas limits',
                'description' => 'Salīdzini mūsdienīgas kredītlīnijas (vēsturiski pazīstamas kā SMS kredīti): maksā procentus tikai par izmantoto summu un dienām.',
                'anchor_element_title' => 'Kredītlīnija internetā',
                'h1' => 'Elastīgā kredītlīnija un mūsdienu alternatīva SMS kredītiem',
                'h1_description' => 'Rezerves drošības spilvens neparedzētiem izdevumiem — izņem tikai nepieciešamo daļu no piešķirtā limita un maksā procentus tikai par faktiski izmantoto summu.',
                'text' => '<h2>No SMS kredītiem līdz mūsdienīgai kredītlīnijai</h2>
<p>Klasiskie īsziņu aizdevumi Latvijā ir pilnībā aizstāti ar drošām tiešsaistes <strong>kredītlīnijām</strong>. Klientam tiek piešķirts individuāls kredītlimits (piemēram, 2 000 €), no kura nepieciešamības brīdī var izņemt 100 € vai 300 €. Kamēr limits netiek izmantots, maksa par to ir 0,00 €.</p>',
                'active' => 1,
                'display_dropdown' => 0,
            ]
        );

        return compact('atrie', 'no18', 'uz12', 'sliktaVesture', 'paterina', 'ilgtermina', 'apvienosana', 'bezprocentu', 'auto', 'sms');
    }

    private function seedLenders(array $loanTypes): array
    {
        $items = [
            [
                'slug' => 'credit24',
                'name' => 'Credit24',
                'image' => '/images/other/credit24-lv.jpg',
                'affiliate_link' => 'https://www.credit24.lv',
                'position' => 1,
                'min_amount' => 100,
                'max_amount' => 7000,
                'first_loan' => 7000,
                'min_term' => 90,
                'max_term' => 2160,
                'min_years' => 18,
                'max_years' => 70,
                'receiving_time' => 15,
                'zero_percent' => 1,
                'top_lender' => 1,
                'slogan' => 'Elastīga kredītlīnija līdz 7 000 € — maksā tikai par izmantoto summu',
                'company_name' => 'IPF Digital Latvia SIA',
                'address' => '13. janvāra iela 3, Rīga, LV-1050',
                'phone' => '+371 20140014',
                'email' => 'info@credit24.lv',
                'max_apr' => '49.80%',
                'real_apr_min' => '21.90%',
                'real_apr_max' => '49.80%',
                'apr_example' => 'Aizņemoties 1 000 € uz 24 mēnešiem ar fiksētu aizņēmuma likmi 28,8% gadā, ikmēneša maksājums ir 55,40 €, kopējā atmaksājamā summa 1 329,60 €, GPL 33,15%.',
                'work_time_m_f' => '08:00 - 20:00',
                'work_time_sa' => '09:00 - 18:00',
                'work_time_su' => '09:00 - 16:00',
                'description' => '<p><strong>Credit24</strong> (IPF Digital Latvia SIA) ir viens no vadošajiem nebanku kreditētājiem Latvijā, kas specializējas elastīgu kredītlīniju izsniegšanā no 18 gadu vecuma. Klientiem nav līguma noformēšanas komisijas, un procenti tiek aprēķināti tikai par faktiski izņemto summu.</p>',
                'categories' => ['atrie', 'no18', 'uz12', 'paterina', 'bezprocentu', 'sms'],
            ],
            [
                'slug' => 'vivus',
                'name' => 'Vivus.lv',
                'image' => '/images/other/vivus-logo.jpg',
                'affiliate_link' => 'https://www.vivus.lv',
                'position' => 2,
                'min_amount' => 100,
                'max_amount' => 10000,
                'first_loan' => 10000,
                'min_term' => 90,
                'max_term' => 2160,
                'min_years' => 20,
                'max_years' => 75,
                'receiving_time' => 15,
                'zero_percent' => 0,
                'top_lender' => 1,
                'slogan' => 'Uzticams patēriņa kredīts un kredītu apvienošana līdz 10 000 €',
                'company_name' => 'AS 4finance',
                'address' => 'Lielirbes iela 17a-46, Rīga, LV-1046',
                'phone' => '+371 67439777',
                'email' => 'info@vivus.lv',
                'max_apr' => '46.20%',
                'real_apr_min' => '14.90%',
                'real_apr_max' => '46.20%',
                'apr_example' => 'Aizņemoties 2 000 € uz 36 mēnešiem, ikmēneša maksājums ir 76,50 €, kopējā atmaksājamā summa 2 754,00 €, GPL 24,80%.',
                'work_time_m_f' => '08:00 - 22:00',
                'work_time_sa' => '09:00 - 20:00',
                'work_time_su' => '09:00 - 20:00',
                'description' => '<p><strong>Vivus.lv</strong> ir pieredzējis Latvijas aizdevējs ar ātru pieteikumu izskatīšanu un plašu termiņu izvēli no 3 līdz 60 mēnešiem gan ikdienas pirkumiem, gan kredītu apvienošanai.</p>',
                'categories' => ['atrie', 'uz12', 'paterina', 'ilgtermina', 'apvienosana'],
            ],
            [
                'slug' => 'bigbank',
                'name' => 'Bigbank',
                'image' => '/images/other/bigbank-new.jpg',
                'affiliate_link' => 'https://www.bigbank.lv',
                'position' => 3,
                'min_amount' => 500,
                'max_amount' => 25000,
                'first_loan' => 25000,
                'min_term' => 180,
                'max_term' => 3600,
                'min_years' => 20,
                'max_years' => 73,
                'receiving_time' => 30,
                'zero_percent' => 0,
                'top_lender' => 1,
                'slogan' => 'Specializētas bankas patēriņa, remonta un auto kredīti no 7,9% gadā',
                'company_name' => 'AS Bigbank Latvijas filiāle',
                'address' => 'Gustava Zemgala gatve 78-1, Rīga, LV-1039',
                'phone' => '+371 8338',
                'email' => 'info@bigbank.lv',
                'max_apr' => '29.90%',
                'real_apr_min' => '7.90%',
                'real_apr_max' => '29.90%',
                'apr_example' => 'Aizņemoties 5 000 € uz 60 mēnešiem ar procentu likmi 9,9% gadā un līguma maksu 75 €, ikmēneša maksājums ir 105,98 €, kopā 6 433,80 €, GPL 11,12%.',
                'work_time_m_f' => '09:00 - 18:00',
                'work_time_sa' => 'Brīvs',
                'work_time_su' => 'Brīvs',
                'description' => '<p><strong>Bigbank</strong> piedāvā bankas līmeņa patēriņa kredītus, auto finansējumu un kredītu apvienošanu līdz 25 000 € ar unikālu 60 dienu apmierinātības garantiju.</p>',
                'categories' => ['paterina', 'ilgtermina', 'apvienosana', 'auto'],
            ],
            [
                'slug' => 'viasms',
                'name' => 'ViaSMS',
                'image' => '/images/other/viasms-lv-kv.jpg',
                'affiliate_link' => 'https://www.viasms.lv',
                'position' => 4,
                'min_amount' => 100,
                'max_amount' => 5000,
                'first_loan' => 5000,
                'min_term' => 30,
                'max_term' => 1440,
                'min_years' => 20,
                'max_years' => 70,
                'receiving_time' => 15,
                'zero_percent' => 1,
                'top_lender' => 1,
                'slogan' => 'Elastīga kredītlīnija ar 100% atlaidi komisijai pirmajās 30 dienās',
                'company_name' => 'SIA VIA SMS',
                'address' => '13. janvāra iela 3, Rīga, LV-1050',
                'phone' => '+371 67212412',
                'email' => 'info@viasms.lv',
                'max_apr' => '51.40%',
                'real_apr_min' => '0.00%',
                'real_apr_max' => '51.40%',
                'apr_example' => 'Aizņemoties 500 € uz 12 mēnešiem, ikmēneša maksājums ir 51,90 €, kopējā atmaksājamā summa 622,80 €, GPL 48,10%.',
                'work_time_m_f' => '08:00 - 20:00',
                'work_time_sa' => '09:00 - 18:00',
                'work_time_su' => '10:00 - 16:00',
                'description' => '<p><strong>ViaSMS</strong> piedāvā modernu kredītlīniju ar minimālo ikmēneša maksājumu vai pilnu atmaksu bez procentiem pirmajās 30 dienās jaunajiem klientiem.</p>',
                'categories' => ['atrie', 'uz12', 'bezprocentu', 'sms'],
            ],
            [
                'slug' => 'tfbank',
                'name' => 'TF Bank',
                'image' => '/images/other/tfbank-kv.jpg',
                'affiliate_link' => 'https://www.tfbank.lv',
                'position' => 5,
                'min_amount' => 1000,
                'max_amount' => 15000,
                'first_loan' => 15000,
                'min_term' => 360,
                'max_term' => 2520,
                'min_years' => 22,
                'max_years' => 70,
                'receiving_time' => 60,
                'zero_percent' => 0,
                'top_lender' => 1,
                'slogan' => 'Ziemeļvalstu bankas patēriņa un auto kredīti bez noformēšanas maksas',
                'company_name' => 'TF Bank AB',
                'address' => 'Krišjāņa Valdemāra iela 33, Rīga, LV-1010',
                'phone' => '+371 67809191',
                'email' => 'info@tfbank.lv',
                'max_apr' => '28.50%',
                'real_apr_min' => '9.90%',
                'real_apr_max' => '28.50%',
                'apr_example' => 'Aizņemoties 3 000 € uz 48 mēnešiem ar gada procentu likmi 12,9% un 0 € līguma maksu, ikmēneša maksājums ir 80,34 €, kopā 3 856,32 €, GPL 13,70%.',
                'work_time_m_f' => '09:00 - 17:00',
                'work_time_sa' => 'Brīvs',
                'work_time_su' => 'Brīvs',
                'description' => '<p>Zviedrijā reģistrētā <strong>TF Bank</strong> piedāvā caurspīdīgus patēriņa kredītus, pārkreditāciju un auto kredītus ar individuāli aprēķinātu bankas procentu likmi.</p>',
                'categories' => ['paterina', 'ilgtermina', 'apvienosana', 'auto'],
            ],
            [
                'slug' => 'ondo',
                'name' => 'Ondo.lv',
                'image' => '/images/other/ondo-lv-kv.jpg',
                'affiliate_link' => 'https://www.ondo.lv',
                'position' => 6,
                'min_amount' => 50,
                'max_amount' => 5000,
                'first_loan' => 5000,
                'min_term' => 30,
                'max_term' => 1080,
                'min_years' => 20,
                'max_years' => 75,
                'receiving_time' => 15,
                'zero_percent' => 1,
                'top_lender' => 0,
                'slogan' => 'Ātra kredītlīnija līdz 5 000 € ar 0% pirmo periodu jaunajiem klientiem',
                'company_name' => 'AS 4finance',
                'address' => 'Lielirbes iela 17a-46, Rīga, LV-1046',
                'phone' => '+371 80909999',
                'email' => 'info@ondo.lv',
                'max_apr' => '52.10%',
                'real_apr_min' => '0.00%',
                'real_apr_max' => '52.10%',
                'apr_example' => 'Aizņemoties 600 € uz 12 mēnešiem, ikmēneša maksājums ir 62,80 €, kopējā atmaksājamā summa 753,60 €, GPL 49,95%.',
                'work_time_m_f' => '08:00 - 22:00',
                'work_time_sa' => '09:00 - 20:00',
                'work_time_su' => '09:00 - 20:00',
                'description' => '<p><strong>Ondo.lv</strong> nodrošina ātru kredītlimita piešķiršanu tiešsaistē un skaidrus nosacījumus īstermiņa un vidēja termiņa vajadzībām.</p>',
                'categories' => ['atrie', 'uz12', 'bezprocentu', 'sms'],
            ],
            [
                'slug' => 'smscredit',
                'name' => 'SMScredit.lv',
                'image' => '/images/other/smscredit-lv-kv_2.jpg',
                'affiliate_link' => 'https://www.smscredit.lv',
                'position' => 7,
                'min_amount' => 50,
                'max_amount' => 5000,
                'first_loan' => 5000,
                'min_term' => 30,
                'max_term' => 1080,
                'min_years' => 20,
                'max_years' => 75,
                'receiving_time' => 15,
                'zero_percent' => 1,
                'top_lender' => 0,
                'slogan' => 'Ērta kredītlīnija neparedzētiem pirkumiem ar tūlītēju izmaksu',
                'company_name' => 'AS 4finance',
                'address' => 'Lielirbes iela 17a-46, Rīga, LV-1046',
                'phone' => '+371 67333990',
                'email' => 'info@smscredit.lv',
                'max_apr' => '52.10%',
                'real_apr_min' => '0.00%',
                'real_apr_max' => '52.10%',
                'apr_example' => 'Aizņemoties 400 € uz 30 dienām jaunajam klientam ar 100% atlaidi: atmaksājamā summa 400 €, GPL 0%. Standarta likme līdz 52,10%.',
                'work_time_m_f' => '08:00 - 22:00',
                'work_time_sa' => '09:00 - 20:00',
                'work_time_su' => '09:00 - 20:00',
                'description' => '<p>Viens no atpazīstamākajiem nebanku zīmoliem Latvijā — <strong>SMScredit.lv</strong> piedāvā elastīgu kredītlimitu ar bezmaksas 30 dienu izmēģinājuma periodu jaunajiem klientiem.</p>',
                'categories' => ['atrie', 'bezprocentu', 'sms'],
            ],
            [
                'slug' => 'primero',
                'name' => 'Primero',
                'image' => '/images/other/primero-lv.jpg',
                'affiliate_link' => 'https://www.primero.lv',
                'position' => 8,
                'min_amount' => 1000,
                'max_amount' => 25000,
                'first_loan' => 25000,
                'min_term' => 180,
                'max_term' => 3000,
                'min_years' => 19,
                'max_years' => 75,
                'receiving_time' => 30,
                'zero_percent' => 0,
                'top_lender' => 0,
                'slogan' => 'Specializēts auto kredīts un līzings līdz 25 000 € ar 60 dienām auto meklēšanai',
                'company_name' => 'AS Primero Finance',
                'address' => 'Skanstes iela 50, Rīga, LV-1013',
                'phone' => '+371 27272735',
                'email' => 'info@primero.lv',
                'max_apr' => '34.90%',
                'real_apr_min' => '8.90%',
                'real_apr_max' => '34.90%',
                'apr_example' => 'Auto kredītam 6 000 € uz 60 mēnešiem ikmēneša maksājums no 129,40 €, kopējā atmaksājamā summa 7 764,00 €, GPL 12,40%.',
                'work_time_m_f' => '09:00 - 19:00',
                'work_time_sa' => '10:00 - 16:00',
                'work_time_su' => 'Brīvs',
                'description' => '<p><strong>Primero</strong> specializējas auto finansēšanā un kredītu apvienošanā, ļaujot iegādāties auto bez pirmās iemaksas un bez KASKO līdz pat 15 000 €.</p>',
                'categories' => ['no18', 'paterina', 'ilgtermina', 'apvienosana', 'auto'],
            ],
            [
                'slug' => 'altero',
                'name' => 'Altero.lv',
                'image' => '/images/other/altero-lv-logo.jpg',
                'affiliate_link' => 'https://www.altero.lv',
                'position' => 9,
                'min_amount' => 500,
                'max_amount' => 25000,
                'first_loan' => 25000,
                'min_term' => 180,
                'max_term' => 3600,
                'min_years' => 18,
                'max_years' => 75,
                'receiving_time' => 45,
                'zero_percent' => 0,
                'top_lender' => 0,
                'slogan' => 'Viens bezmaksas pieteikums — saņem individuālus piedāvājumus no vairākiem aizdevējiem',
                'company_name' => 'SIA Altero',
                'address' => 'Mūkusalas iela 42A, Rīga, LV-1004',
                'phone' => '+371 29099909',
                'email' => 'info@altero.lv',
                'max_apr' => '38.00%',
                'real_apr_min' => '6.90%',
                'real_apr_max' => '38.00%',
                'apr_example' => 'Aizņemoties 4 000 € uz 60 mēnešiem, ikmēneša maksājums no 84,80 €, kopējā atmaksājamā summa 5 088,00 €, GPL 10,60%.',
                'work_time_m_f' => '08:00 - 20:00',
                'work_time_sa' => '10:00 - 18:00',
                'work_time_su' => 'Brīvs',
                'description' => '<p><strong>Altero.lv</strong> ir finanšu brokeru platforma Latvijā, kas ar vienu pieteikumu ļauj salīdzināt personalizētus patēriņa, pārkreditācijas un auto kredītu piedāvājumus arī sarežģītākās situācijās.</p>',
                'categories' => ['no18', 'sliktaVesture', 'paterina', 'ilgtermina', 'apvienosana', 'auto'],
            ],
        ];

        $seededLenders = [];

        foreach ($items as $item) {
            $lender = Lenders::updateOrCreate(
                ['route_name' => $item['slug']],
                [
                    'name' => $item['name'],
                    'image' => $item['image'],
                    'affiliate_link' => $item['affiliate_link'],
                    'position' => $item['position'],
                    'frequency' => null,
                    'first_loan' => $item['first_loan'],
                    'min_amount' => $item['min_amount'],
                    'max_amount' => $item['max_amount'],
                    'min_term' => $item['min_term'],
                    'max_term' => $item['max_term'],
                    'min_years' => $item['min_years'],
                    'max_years' => $item['max_years'],
                    'receiving_time' => $item['receiving_time'],
                    'active' => 1,
                    'zero_percent' => $item['zero_percent'],
                    'top_lender' => $item['top_lender'],
                    'allow_redirect' => 1,
                ]
            );

            LendersData::updateOrCreate(
                ['lender_id' => $lender->id, 'lang' => 'lv'],
                [
                    'slogan' => $item['slogan'],
                    'company_name' => $item['company_name'],
                    'address' => $item['address'],
                    'phone' => $item['phone'],
                    'email' => $item['email'],
                    'max_apr' => $item['max_apr'],
                    'real_apr_min' => $item['real_apr_min'],
                    'real_apr_max' => $item['real_apr_max'],
                    'apr_example' => $item['apr_example'],
                    'title' => $item['name'] . ' atsauksmes, procentu likmes un kontakti (2026)',
                    'meta_description' => 'Iepazīsties ar ' . $item['name'] . ' nosacījumiem, GPL piemēru, darba laiku un reālām klientu atsauksmēm.',
                    'h1' => $item['name'] . ' — nosacījumi un klientu atsauksmes',
                    'h1_description' => $item['slogan'],
                    'description' => $item['description'],
                    'work_time_m_f' => $item['work_time_m_f'],
                    'work_time_sa' => $item['work_time_sa'],
                    'work_time_su' => $item['work_time_su'],
                    'image_alt_text' => $item['name'] . ' logotips',
                    'anchor_title' => $item['name'] . ' kredīts un atsauksmes',
                ]
            );

            LendersCategories::where('lender_id', $lender->id)->delete();
            foreach ($item['categories'] as $catKey) {
                if (isset($loanTypes[$catKey])) {
                    LendersCategories::create([
                        'lender_id' => $lender->id,
                        'loan_type_id' => $loanTypes[$catKey]->id,
                    ]);
                }
            }

            $seededLenders[$item['slug']] = $lender;
        }

        return $seededLenders;
    }

    private function seedSections(): array
    {
        $home = Sections::updateOrCreate(
            ['route_name' => 'home', 'lang' => 'lv'],
            [
                'type' => 'home',
                'active' => 1,
                'order' => 1,
                'parent_section_id' => null,
                'name' => 'Sākums',
                'title' => 'Kredītu salīdzinājums Latvijā 2026 — Salīdzini aizdevumus internetā',
                'description' => 'Neatkarīgs licencēto kredītdevēju salīdzinājums Latvijā. Salīdzini ātros kredītus, patēriņa kredītus, pārkreditāciju un auto līzingu pēc GPL un atsauksmēm.',
                'h1' => 'Salīdzini kredītus gudri un caurspīdīgi',
                'h1_description' => ' Visi licencētie Latvijas aizdevēji vienuviet — salīdzini procentu likmes (GPL), bezprocentu akcijas, izmaksas ātrumu un pārbaudītas klientu atsauksmes.',
                'text' => '<h2>Kā objektīvi salīdzināt kredītus internetā un nepārmaksāt?</h2>
<p>Kredītu tirgus Latvijā piedāvā desmitiem dažādu finansējuma veidu — sākot no īstermiņa kredītlīnijām neparedzētiem izdevumiem līdz ilgtermiņa <a href="/paterina-krediti">patēriņa kredītiem</a> mājokļa remontam vai <a href="/kreditu-apvienosana">kredītu apvienošanai</a>. Tā vietā, lai paļautos tikai uz reklāmas solījumiem, gudrs aizņēmējs salīdzina reālos skaitļus.</p>
<h3>3 zelta likumi drošai aizņemšanās pieredzei</h3>
<ul>
<li><strong>Salīdzini gada procentu likmi (GPL), nevis tikai mēneša likmi:</strong> GPL vienotā formulā parāda visas aizdevuma izmaksas gadā, ieskaitot noformēšanas un apkalpošanas komisijas.</li>
<li><strong>Pārbaudi PTAC licenci:</strong> mūsu portālā tiek iekļauti tikai Patērētāju tiesību aizsardzības centra (PTAC) licencēti kredītdevēji un finanšu brokeri.</li>
<li><strong>Plāno drošības rezervi budžetā:</strong> finanšu eksperti iesaka raudzīties, lai visi kredītmaksājumi kopā nepārsniegtu 25%–30% no mājsaimniecības ikmēneša tīrajiem ienākumiem.</li>
</ul>
<h3>Kāds kredīta veids ir piemērots Tavam mērķim?</h3>
<p>Nelielai summai līdz algai (100 € – 500 €), kuru vari pilnībā atmaksāt 30 dienu laikā, izdevīgākais risinājums bieži ir jaunā klienta <a href="/bezprocentu-krediti">bezprocentu kredīts ar 0% likmi</a>. Lielākiem pirkumiem virs 1 000 € ieteicams izvēlēties klasisku patēriņa vai <a href="/auto-lizings">auto kredītu</a> ar ievērojami zemāku gada procentu likmi.</p>',
                'display_in_the_header' => 0,
            ]
        );

        $reviews = Sections::updateOrCreate(
            ['route_name' => 'atsauksmes', 'lang' => 'lv'],
            [
                'type' => 'reviews',
                'active' => 1,
                'order' => 2,
                'parent_section_id' => null,
                'name' => 'Atsauksmes',
                'title' => 'Aizdevēju atsauksmes un reitings Latvijā (2026)',
                'description' => 'Lasi reālas klientu atsauksmes un pieredzi par Latvijas nebanku un banku aizdevējiem — Credit24, Bigbank, Vivus, TF Bank, ViaSMS, Primero u.c.',
                'h1' => 'Aizdevēju atsauksmes un klientu vērtējumi',
                'h1_description' => 'Iepazīsties ar citu aizņēmēju pieredzi par apkalpošanas kvalitāti, līguma skaidrību un naudas izmaksas ātrumu vai pievieno savu vērtējumu.',
                'text' => '<p>Objektīvas klientu atsauksmes palīdz novērtēt ne tikai skaitļus tabulā, bet arī aizdevēja ikdienas komunikāciju, pirmstermiņa atmaksas ērtumu un pretimnākšanu nestandarta situācijās.</p>',
                'display_in_the_header' => 1,
            ]
        );

        $blogs = Sections::updateOrCreate(
            ['route_name' => 'blogs', 'lang' => 'lv'],
            [
                'type' => 'blog',
                'active' => 1,
                'order' => 3,
                'parent_section_id' => null,
                'name' => 'Blogs',
                'title' => 'Finanšu pratības blogs un praktiski kredītu ceļveži | Credito.lv',
                'description' => 'Ekspertu analītika un praktiski ceļveži par GPL (gada procentu likmes) aprēķinu, auto finansēšanu, kredītu apvienošanu, maksātspējas novērtējumu un patēriņa kredītiem.',
                'h1' => 'Finanšu analītika un praktiski ceļveži',
                'h1_description' => 'Skaidri skaitļi un konkrēti piemēri bez banku žargona — kā aprēķināt patieso kredīta cenu (GPL), uzlabot savu reitingu un nepārmaksāt procentos.',
                'text' => '',
                'display_in_the_header' => 1,
            ]
        );

        $this->call(BlogSeeder::class);

        $contact = Sections::updateOrCreate(
            ['route_name' => 'par-mums', 'lang' => 'lv'],
            [
                'type' => 'contact',
                'active' => 1,
                'order' => 4,
                'parent_section_id' => null,
                'name' => 'Par mums',
                'title' => 'Par mums un kontakti — Neatkarīga kredītu salīdzināšanas metodoloģija',
                'description' => 'Uzzini, kā mēs salīdzinām un atjaunojam informāciju par Latvijas kredītdevējiem, vai sazinies ar mūsu komandu sadarbības un datu jautājumos.',
                'h1' => 'Par mūsu platformu un saziņa',
                'h1_description' => 'Caurspīdīga finanšu salīdzināšanas metodoloģija, PTAC licencētu aizdevēju atlase un tieša saziņa ar redakciju.',
                'text' => '<h2>Kā mēs strādājam un uzturam datus aktuālus?</h2>
<p>Mūsu komanda regulāri pārbauda Latvijā licencēto banku un nebanku aizdevēju publiskos cenrāžus, gada procentu likmes (GPL), vecuma sliekšņus un akciju noteikumus, lai apmeklētāji vienuviet varētu salīdzināt piedāvājumus bez slēptiem nosacījumiem.</p>
<p>Portāls pats neizsniedz aizdevumus un nepieņem kredītlēmumus. Visi salīdzināšanas rīki un kalkulatori lietotājiem ir pieejami bez maksas; par atsevišķām partneru saitēm portāls var saņemt komisijas atlīdzību, kas nekādā veidā nesadārdzina aizdevumu klientam.</p>',
                'display_in_the_header' => 1,
            ]
        );

        $privacy = Sections::updateOrCreate(
            ['route_name' => 'privatuma-politika', 'lang' => 'lv'],
            [
                'type' => 'standard',
                'active' => 1,
                'order' => 5,
                'parent_section_id' => null,
                'name' => 'Privātuma politika',
                'title' => 'Privātuma politika un personas datu aizsardzība (VDAR)',
                'description' => 'Informācija par personas datu apstrādi un aizsardzību saskaņā ar Vispārīgo datu aizsardzības regulu (GDPR / VDAR).',
                'h1' => 'Privātuma politika',
                'h1_description' => 'Kā mēs apstrādājam un aizsargājam apmeklētāju datus saskaņā ar Eiropas Savienības Vispārīgo datu aizsardzības regulu.',
                'text' => '<h2>1. Vispārīgie noteikumi</h2>
<p>Šī privātuma politika skaidro, kā portāls apstrādā personas datus, kad apmeklētājs izmanto kontaktformu, pievieno atsauksmi vai pārlūko salīdzināšanas tabulas.</p>
<h2>2. Kādus datus mēs apstrādājam?</h2>
<ul>
<li>Kontaktformā brīvprātīgi norādīto vārdu un e-pasta adresi, lai sniegtu atbildi uz jautājumu.</li>
<li>Tehniskos pārlūkošanas datus un anonimizētu statistiku vietnes darbības nodrošināšanai un krāpniecisku darbību novēršanai.</li>
</ul>
<h2>3. Datu glabāšana un lietotāja tiesības</h2>
<p>Jums ir tiesības jebkurā brīdī pieprasīt informāciju par saviem datiem vai to dzēšanu, rakstot uz portāla oficiālo e-pasta adresi sadaļā <a href="/par-mums">Par mums</a>.</p>',
                'display_in_the_header' => 0,
            ]
        );

        $cookies = Sections::updateOrCreate(
            ['route_name' => 'sikfaili', 'lang' => 'lv'],
            [
                'type' => 'standard',
                'active' => 1,
                'order' => 6,
                'parent_section_id' => null,
                'name' => 'Sīkfailu politika',
                'title' => 'Sīkfailu (Cookies) izmantošanas noteikumi',
                'description' => 'Skaidrojums par tehniskajiem un analītiskajiem sīkfailiem, kas tiek izmantoti portāla pilnvērtīgai darbībai.',
                'h1' => 'Sīkfailu izmantošanas politika',
                'h1_description' => 'Uzzini, kādus sīkfailus izmantojam ērtākai kredītu filtrēšanai un drošībai.',
                'text' => '<h2>Kas ir sīkfaili (cookies)?</h2>
<p>Sīkfaili ir nelieli teksta faili, ko pārlūkprogramma saglabā Jūsu ierīcē, lai nodrošinātu sesijas drošību (piemēram, aizsardzību pret surogātpastu formās) un saglabātu Jūsu sīkfailu izvēli.</p>
<h2>Kā pārvaldīt sīkfailus?</h2>
<p>Jūs varat jebkurā brīdī dzēst vai ierobežot sīkfailus savas interneta pārlūkprogrammas iestatījumos.</p>',
                'display_in_the_header' => 0,
            ]
        );

        $terms = Sections::updateOrCreate(
            ['route_name' => 'lietosanas-noteikumi', 'lang' => 'lv'],
            [
                'type' => 'standard',
                'active' => 1,
                'order' => 7,
                'parent_section_id' => null,
                'name' => 'Lietošanas noteikumi',
                'title' => 'Portāla lietošanas noteikumi un atbildības atruna',
                'description' => 'Informatīvā kredītu salīdzināšanas portāla lietošanas kārtība un atbildīgas aizņemšanās nosacījumi.',
                'h1' => 'Lietošanas noteikumi',
                'h1_description' => 'Nosacījumi portālā publicētās finanšu informācijas izmantošanai.',
                'text' => '<h2>Informatīvs raksturs</h2>
<p>Visa portālā publicētā informācija par aizdevumu summām, atmaksas termiņiem un gada procentu likmēm ir informatīva un neskaitās juridiski saistošs finanšu piedāvājums. Galīgos līguma nosacījumus vienmēr nosaka izvēlētais licencētais aizdevējs pēc individuālas maksātspējas izvērtēšanas.</p>',
                'display_in_the_header' => 0,
            ]
        );

        return compact('home', 'reviews', 'blogs', 'contact', 'privacy', 'cookies', 'terms');
    }

    private function seedFaqs(array $sections, array $loanTypes): void
    {
        $faqItems = [
            [
                'order' => 1,
                'question' => 'Cik ātri nauda tiek ieskaitīta bankas kontā pēc kredīta apstiprināšanas?',
                'answer' => '<p>Ja Jūsu un aizdevēja bankas konti atrodas vienā no lielākajām Latvijas komercbankām (Swedbank, SEB, Citadele vai Luminor) vai tiek izmantoti zibmaksājumi, pārskaitījums pēc līguma apstiprināšanas aizdevēja darba laikā parasti notiek <strong>10 līdz 15 minūšu laikā</strong>.</p>',
            ],
            [
                'order' => 2,
                'question' => 'Ko nozīmē 0% bezprocentu kredīts jaunajiem klientiem?',
                'answer' => '<p>Vairāki licencēti nebanku aizdevēji piedāvā pirmo aizdevumu vai kredītlīnijas pirmo 30–60 dienu periodu ar 100% komisijas un procentu atlaidi. Atmaksājot pilnu izņemto summu noteiktajā termiņā, pārmaksa ir 0,00 €.</p>',
            ],
            [
                'order' => 3,
                'question' => 'Vai kredītu drīkst atmaksāt pirms termiņa bez soda naudas?',
                'answer' => '<p>Jā! Saskaņā ar Latvijas Republikas Patērētāju tiesību aizsardzības likumu ikvienam patērētājam ir tiesības jebkurā brīdī atmaksāt kredītu daļēji vai pilnībā pirms līgumā noteiktā termiņa, maksājot procentus tikai par faktiski izmantoto laika periodu.</p>',
            ],
            [
                'order' => 4,
                'question' => 'Kādi dokumenti un prasības ir nepieciešamas kredīta saņemšanai internetā?',
                'answer' => '<p>Nepieciešams pastāvīga Latvijas iedzīvotāja statuss, aktīvs bankas konts Latvijā, mobilais tālrunis, Smart-ID vai internetbanka identitātes apstiprināšanai un stabili, oficiāli ienākumi. Dažkārt lielākām summām aizdevējs var lūgt augšupielādēt pēdējo 6 mēnešu konta pārskatu.</p>',
            ],
            [
                'order' => 5,
                'question' => 'No cik gadu vecuma Latvijā ir pieejami kredīti internetā?',
                'answer' => '<p>Standarta minimālais vecums pie lielākās daļas aizdevēju ir 20 gadi, tomēr atsevišķi licencēti kredītdevēji (piemēram, Credit24, Primero un Altero partneri) izskata pieteikumus no <strong>18 vai 19 gadu vecuma</strong>, ja klientam ir regulāri oficiāli ienākumi.</p>',
            ],
            [
                'order' => 6,
                'question' => 'Vai salīdzināšanas portāla izmantošana ir bez maksas?',
                'answer' => '<p>Jā, visa portālā pieejamā informācija, kredītu filtri un ikmēneša maksājuma kalkulators apmeklētājiem ir <strong>100% bez maksas</strong> un neuzliek nekādas saistības.</p>',
            ],
        ];

        FaqSections::query()->delete();

        foreach ($faqItems as $item) {
            $faq = Faqs::updateOrCreate(
                ['question' => $item['question'], 'lang' => 'lv'],
                [
                    'answer' => $item['answer'],
                    'topic' => 'Kredīti',
                    'active' => 1,
                    'order' => $item['order'],
                ]
            );

            FaqSections::create([
                'faq_id' => $faq->id,
                'section_id' => $sections['home']->id,
            ]);

            foreach (['atrie', 'paterina', 'apvienosana', 'bezprocentu', 'auto'] as $ltKey) {
                if (isset($loanTypes[$ltKey])) {
                    FaqSections::create([
                        'faq_id' => $faq->id,
                        'loan_type_id' => $loanTypes[$ltKey]->id,
                    ]);
                }
            }
        }
    }

    private function seedReviews(array $lenders): void
    {
        if (Reviews::count() > 0) {
            return;
        }

        $reviewsData = [
            ['slug' => 'credit24', 'name' => 'Mārtiņš K.', 'rating' => 5, 'text' => 'Ļoti ērta kredītlīnija — vajadzēja steidzami zobārstam 400 €, atdevu pēc algas divu nedēļu laikā un pārmaksāju tikai dažus eiro par izmantotajām dienām.'],
            ['slug' => 'credit24', 'name' => 'Ilze Bērziņa', 'rating' => 5, 'text' => 'Pieteikums ar Smart-ID aizņēma mazāk par 10 minūtēm un nauda Swedbank kontā ienāca gandrīz uzreiz. Skaidrs un saprotams kabinets.'],
            ['slug' => 'bigbank', 'name' => 'Andris Ozoliņš', 'rating' => 5, 'text' => 'Apvienoju trīs mazākus kredītus vienā Bigbank patēriņa kredītā. Mēneša maksājums samazinājās par gandrīz 95 eiro un tagad ir viens skaidrs datums mēnesī.'],
            ['slug' => 'bigbank', 'name' => 'Kristīne L.', 'rating' => 4, 'text' => 'Laba procentu likme dzīvokļa virtuves remontam. Izskatīšana prasīja aptuveni stundu, jo vajadzēja pievienot konta izrakstu, bet nosacījumi ļoti godīgi.'],
            ['slug' => 'vivus', 'name' => 'Jānis Kalniņš', 'rating' => 4, 'text' => ' Vienkāršs un saprotams grafiks, ērti samaksāt pirms termiņa bez liekiem zvaniem.'],
            ['slug' => 'vivus', 'name' => 'zane_p', 'rating' => 5, 'text' => 'Ātra apkalpošana sestdienas rītā, atbalsta dienests laipni izskaidroja pirmstermiņa atmaksas aprēķinu.'],
            ['slug' => 'viasms', 'name' => 'Edgars V.', 'rating' => 5, 'text' => 'Izmantoju jauno klientu 30 dienu 0% piedāvājumu auto riepu maiņai — atdevu precīzi mēneša beigās tikpat, cik paņēmu.'],
            ['slug' => 'tfbank', 'name' => 'Raimonds S.', 'rating' => 5, 'text' => 'Paņēmu 4 500 € auto iegādei bez KASKO un bez pirmās iemaksas. Procentu likme bija krietni zemāka nekā pie nebanku ātrajiem aizdevējiem.'],
            ['slug' => 'primero', 'name' => 'Kaspars M.', 'rating' => 4, 'text' => 'Patika, ka naudu ieskaitīja kontā un deva 60 dienas mašīnas atrašanai un piereģistrēšanai CSDD uz mana vārda.'],
            ['slug' => 'ondo', 'name' => 'Laura T.', 'rating' => 4, 'text' => 'Ātrs pārskaitījums uz Citadeles kontu un pārskatāms profils telefonā.'],
            ['slug' => 'smscredit', 'name' => 'Gatis R.', 'rating' => 4, 'text' => 'Pārbaudīts aizdevējs nelielām summām, ja stingri ievēro atmaksas termiņu.'],
            ['slug' => 'altero', 'name' => ' Sanita K.', 'rating' => 5, 'text' => 'Aizpildīju vienu pieteikumu un 40 minūšu laikā varēju salīdzināt četrus reālus piedāvājumus ar precīzām likmēm manai situācijai.'],
        ];

        foreach ($reviewsData as $rev) {
            if (!isset($lenders[$rev['slug']])) {
                continue;
            }

            Reviews::create([
                'lender_id' => $lenders[$rev['slug']]->id,
                'name' => trim($rev['name']),
                'rating' => $rev['rating'],
                'text' => $rev['text'],
                'lang' => 'lv',
                'ip' => '127.0.0.1',
            ]);
        }
    }
}
