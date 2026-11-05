<?php

namespace Database\Seeders;

use App\Models\Admincp\Faqs\FaqSections;
use App\Models\Admincp\Sections\Sections;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Clears all existing blog articles and seeds 5 modern editorial financial guides
     * (including a comprehensive guide on GPL / APR).
     */
    public function run(): void
    {
        // 1. Ensure the root Blog section exists
        $blogs = Sections::updateOrCreate(
            ['route_name' => 'blogs', 'lang' => 'lv', 'parent_section_id' => null],
            [
                'type' => 'blog',
                'active' => 1,
                'order' => 3,
                'name' => 'Blogs',
                'title' => 'Finanšu pratības blogs un praktiski kredītu ceļveži | Credito.lv',
                'description' => 'Ekspertu analītika un praktiski ceļveži par GPL (gada procentu likmes) aprēķinu, auto finansēšanu, kredītu apvienošanu, maksātspējas novērtējumu un patēriņa kredītiem.',
                'h1' => 'Finanšu analītika un praktiski ceļveži',
                'h1_description' => 'Skaidri skaitļi un konkrēti piemēri bez banku žargona — kā aprēķināt patieso kredīta cenu (GPL), uzlabot savu reitingu un nepārmaksāt procentos.',
                'text' => '',
                'display_in_the_header' => 1,
            ]
        );

        // 2. Clean up all existing blog articles (and their FAQ pivot links if any)
        $existingPostIds = Sections::where(function ($query) use ($blogs) {
            $query->where('parent_section_id', $blogs->id)
                ->orWhere(function ($q) {
                    $q->where('type', 'blog')->whereNotNull('parent_section_id');
                });
        })->pluck('id');

        if ($existingPostIds->isNotEmpty()) {
            FaqSections::whereIn('section_id', $existingPostIds)->delete();
            Sections::whereIn('id', $existingPostIds)->delete();
        }

        // 3. Seed 5 modern, in-depth credit & financial literacy articles
        $blogPostsData = [
            [
                'slug' => 'kas-ir-gada-procentu-likme-gpl',
                'order' => 1,
                'name' => 'Kas ir gada procentu likme (GPL) un kāpēc ar nominālo likmi vien nepietiek?',
                'title' => 'Kas ir GPL (Gada procentu likme)? Aprēķina formula un salīdzinājums 2026',
                'description' => 'Praktisks skaidrojums par gada procentu likmi (GPL): kas tajā ietilpst, kā atšķiras nominālā likme no GPL un kā ar vienu skaitli atklāt slēptās komisijas maksas.',
                'image' => '/images/blog/editorial-gpl.svg',
                'image_alt_text' => 'Gada procentu likmes GPL struktūra un salīdzinājums',
                'h1_description' => 'Kāpēc aizdevums ar 9% nominālo likmi reizēm izmaksā dārgāk nekā piedāvājums ar 13% likmi? Atbilde vienmēr ir redzama GPL rādītājā.',
                'source' => '<p>Izvēloties patēriņa kredītu, auto kredītu vai kredītlīniju, reklāmās visbiežāk tiek izcelta bāzes jeb nominālā aizņēmuma likme. Tomēr vienīgais likumā standartizētais rādītājs, kas atklāj pilnīgi visas aizdevuma izmaksas, ir <strong>gada procentu likme (GPL)</strong>.</p>',
                'text' => '<h2>Nominālā procentu likme pret GPL: kur rodas starpība?</h2>
<p><strong>Nominālā aizņēmuma likme</strong> ir tikai maksa par paša aizdotā kapitāla lietošanu. Savukārt <strong>gada procentu likme (GPL jeb angliski APR)</strong> ir Eiropas Savienības direktīvā un Latvijas Patērētāju tiesību aizsardzības likumā noteikta formula, kas izsaka visas obligātās kredīta izmaksas procentos uz gadu.</p>
<p>GPL aprēķinā obligāti tiek iekļauti šādi komponenti:</p>
<ul>
<li><strong>Aizņēmuma procentu likme</strong> (fiksētā likme vai mainīgā EURIBOR + pievienotā likme);</li>
<li><strong>Līguma noformēšanas jeb izsniegšanas komisija</strong> (gan fiksēta eiro summa, gan procentuāla komisija no kredīta summas);</li>
<li><strong>Ikmēneša administrēšanas vai konta apkalpošanas maksa</strong>, ja tāda ir paredzēta līgumā;</li>
<li><strong>Citas obligātās izmaksas</strong>, kas ir priekšnosacījums kredīta saņemšanai ar izsludinātajiem nosacījumiem.</li>
</ul>

<h2>Reāls salīdzinājums skaitļos: 2 000 € uz 24 mēnešiem</h2>
<p>Apskatīsim situāciju, kurā divi licencēti aizdevēji piedāvā 2 000 € patēriņa kredītu uz 2 gadiem. Pirmajā acumirklī Piedāvājums A šķiet pievilcīgāks zemākas bāzes likmes dēļ, taču tabula atklāj patieso ainu:</p>
<table>
<thead>
<tr>
<th>Rādītājs</th>
<th>Piedāvājums A (ar komisiju)</th>
<th>Piedāvājums B (bez komisijas)</th>
</tr>
</thead>
<tbody>
<tr>
<td>Nominālā gada likme</td>
<td>11,9% gadā</td>
<td>15,2% gadā</td>
</tr>
<tr>
<td>Līguma noformēšanas maksa</td>
<td>140 € (7% no summas)</td>
<td>0 €</td>
</tr>
<tr>
<td>Ikmēneša administrēšanas maksa</td>
<td>3,50 € / mēn.</td>
<td>0 €</td>
</tr>
<tr>
<td><strong>Faktiskā GPL</strong></td>
<td><strong>24,8%</strong></td>
<td><strong>16,4%</strong></td>
</tr>
<tr>
<td><strong>Kopā atmaksājamā summa</strong></td>
<td><strong>2 536,00 €</strong></td>
<td><strong>2 354,00 €</strong></td>
</tr>
</tbody>
</table>
<p>Kā redzams, salīdzinot piedāvājumus pēc <strong>GPL</strong>, aizņēmējs uzreiz pamana, ka Piedāvājums B ļauj ietaupīt <strong>182 €</strong>, lai gan tā nominālā procentu likme izskatījās augstāka.</p>

<h2>Likumā noteiktie izmaksu griesti Latvijā</h2>
<p>Latvijā patērētāju kreditēšanu stingri regulē Patērētāju tiesību aizsardzības likums (PTAL). Licencētiem aizdevējiem ir jāievēro divi galvenie ierobežojumi:</p>
<ul>
<li><strong>Dienas procentu griesti:</strong> kopējās kredīta izmaksas patērētājam nedrīkst pārsniegt <strong>0,07% dienā</strong> no kredīta summas.</li>
<li><strong>Kopējās pārmaksas griesti (100% slieksnis):</strong> visi procenti, komisijas un pat kavējuma procenti kopā visā līguma darbības laikā nedrīkst pārsniegt sākotnēji izsniegto kredīta pamatsummu.</li>
</ul>

<blockquote>Zelta likums: salīdzini GPL tikai starp aizdevumiem ar <strong>identisku summu un identisku atmaksas termiņu</strong>. Īstermiņa aizdevumam uz 3 mēnešiem GPL matemātiski vienmēr izskatīsies augstāka nekā 5 gadu līgumam, pat ja pārmaksa eiro izteiksmē ir tikai daži desmiti eiro.</blockquote>',
            ],
            [
                'slug' => 'auto-kredits-vai-lizings-kas-izdevigak',
                'order' => 2,
                'name' => 'Auto kredīts vai auto līzings 2026. gadā: kurš risinājums izmaksā mazāk?',
                'title' => 'Auto kredīts vai auto līzings — Salīdzinājums, KASKO un CSDD īpašumtiesības',
                'description' => 'Objektīvs salīdzinājums starp auto kredītu un finanšu līzingu Latvijā: īpašumtiesības CSDD, pirmā iemaksa, obligātā KASKO apdrošināšana un kopējā pārmaksa.',
                'image' => '/images/blog/editorial-auto.svg',
                'image_alt_text' => 'Auto kredīta un auto līzinga salīdzinājums',
                'h1_description' => 'Pērkot auto cenu kategorijā no 3 000 € līdz 25 000 €, nepareiza finansējuma formas izvēle KASKO un vērtējuma dēļ var sadārdzināt pirkumu par vairākiem tūkstošiem eiro.',
                'source' => '<p>Latvijas sekundārajā auto tirgū vidējā darījuma summa svārstās ap 7 500 € – 12 000 €. Šajā segmentā pircējiem ir pieejami gan specializētie <a href="/auto-krediti">auto kredīti</a>, gan klasiskais finanšu līzings. Izvērtējam abus variantus pēc kopējām izmaksām.</p>',
                'text' => '<h2>Galvenā juridiskā atšķirība: kurš ir auto īpašnieks CSDD?</h2>
<p><strong>Auto kredīta</strong> gadījumā aizdevējs pārskaita naudu uz Tavu bankas kontu, un Tu iegādājies automašīnu kā pilntiesīgs īpašnieks. CSDD tehniskajā pasē Tu tiec reģistrēts kā <strong>īpašnieks</strong>, nevis tikai turētājs. Tas nozīmē, ka izbraukšanai ārpus valsts nav jāprasa pilnvara, nav jāuzstāda papildu pretaizdzīšanas sistēmas un nepieciešamības gadījumā auto var pārdot jebkurā brīdī.</p>
<p><strong>Auto līzinga</strong> gadījumā spēkratu iegādājas līzinga kompānija un nodod Tev lietošanā. Līdz pēdējā maksājuma veikšanai īpašnieks ir līzinga devējs.</p>

<h2>Izmaksu salīdzinājums: kad zemāks procents nenozīmē mazāku maksājumu?</h2>
<p>Līzinga galvenā priekšrocība ir zemāka procentu likme (parasti no 2,2% līdz 4,5% + 6 mēnešu EURIBOR), taču līzingam ir trīs papildu izmaksu pozīcijas, kuru nav auto kredītam:</p>
<ul>
<li><strong>Obligāta KASKO polise:</strong> visā līzinga periodā (3–5 gadi) KASKO izmaksā vidēji 450 € – 900 € gadā. Ja auto maksā 6 000 €, piecu gadu laikā KASKO vien var izmaksāt vairāk nekā pats kredīta sadārdzinājums.</li>
<li><strong>Pirmā iemaksa 10%–20%:</strong> līzingam uzreiz nepieciešami brīvi līdzekļi, kamēr auto kredīts finansē līdz 100% no pirkuma cenas.</li>
<li><strong>Sertificēts auto novērtējums:</strong> pērkot lietotu auto no privātpersonas, līzingam bieži nepieciešams maksas vērtējums (80 € – 150 €).</li>
</ul>

<h2>Kopsavilkums: kuru risinājumu izvēlēties?</h2>
<table>
<thead>
<tr>
<th>Kritērijs</th>
<th>Izvēlies Auto kredītu</th>
<th>Izvēlies Auto līzingu</th>
</tr>
</thead>
<tbody>
<tr>
<td>Auto cena</td>
<td>no 1 500 € līdz 15 000 €</td>
<td>virs 15 000 € (jauns vai mazlietots)</td>
</tr>
<tr>
<td>Auto vecums</td>
<td>jebkurš vecums (arī 10–18 g.)</td>
<td>līdz 10–12 gadiem termiņa beigās</td>
</tr>
<tr>
<td>Laiks auto iegādei</td>
<td>30–60 dienas pēc naudas saņemšanas</td>
<td>konkrēts auto jāizvēlas pirms pieteikuma</td>
</tr>
<tr>
<td>KASKO apdrošināšana</td>
<td>brīvprātīga (pēc paša izvēles)</td>
<td>obligāta visā līguma termiņā</td>
</tr>
</tbody>
</table>',
            ],
            [
                'slug' => 'ka-apvienot-vairakus-kreditus-viena-maksajuma',
                'order' => 3,
                'name' => 'Kredītu apvienošana un pārkreditācija: kā reāli samazināt mēneša maksājumu',
                'title' => 'Kredītu apvienošana vienā maksājumā — Praktisks pārkreditācijas ceļvedis',
                'description' => 'Kā darbojas kredītu apvienošana Latvijā: kad pārkreditācija patiešām ietaupa naudu, kā aprēķināt esošo saistību atlikumu un no kādām kļūdām izvairīties.',
                'image' => '/images/blog/editorial-apvienosana.svg',
                'image_alt_text' => 'Vairāku kredītu apvienošana vienā ikmēneša maksājumā',
                'h1_description' => 'Ja ik mēnesi dažādos datumos jāapmaksā vairākas kredītlīnijas, nomaksas līgumi un patēriņa kredīti, saistību konsolidācija ļauj aizstāt dārgos līgumus ar vienu pārskatāmu grafiku.',
                'source' => '<p><a href="/kreditu-apvienosana">Kredītu apvienošana</a> jeb refinansēšana nav jaunu parādu uzņemšanās — tā ir finanšu restrukturizācija, kurā vairāki īstermiņa vai augstu procentu aizdevumi tiek dzēsti ar vienu ilgtermiņa kredītu ar būtiski zemāku GPL.</p>',
                'text' => '<h2>Kāpēc vairāki mazi kredīti izmaksā dārgāk nekā viens liels?</h2>
<p>Daudziem aizņēmējiem saistību portfelis izveidojas pakāpeniski: viena atvērta kredītlīnija neparedzētiem tēriņiem, viens patēriņa kredīts remontam un viedtālrunis uz nomaksu. Problēma slēpjas tajā, ka:</p>
<ul>
<li>Katram līgumam ir sava ikmēneša apkalpošanas maksa un atšķirīga gada procentu likme (īpaši kredītlīnijām, kur tiek maksāta tikai minimālā summa);</li>
<li>Dažādi apmaksas datumi (piemēram, 5., 12. un 25. datums) palielina risku nejauši nokavēt maksājumu un sabojāt kredītvēsturi;</li>
<li>Summējot visus ikmēneša maksājumus, tie rada pārmērīgu slodzi uz ikmēneša budžetu.</li>
</ul>

<h2>4 soļu algoritms veiksmīgai pārkreditācijai</h2>
<ol>
<li><strong>Pieprasi precīzu atlikumu pirmstermiņa atmaksai:</strong> ieej katra esošā kreditora profilā un noskaidro, kāda ir precīza pamatsummas un uzkrāto procentu summa, ja kredītu dzēš šodien (nākotnes procenti pie pirmstermiņa atmaksas nav jāmaksā).</li>
<li><strong>Atšķiro izdevīgos līgumus:</strong> ja Tev ir studiju kredīts vai līzings ar 0% akcijas likmi, tos apvienošanā neiekļauj — pārkreditē tikai tos līgumus, kuru GPL pārsniedz 18%–20%.</li>
<li><strong>Salīdzini kopējo pārmaksu, nevis tikai mēneša maksājumu:</strong> pārliecinies, ka ikmēneša maksājuma samazinājums rodas no zemākas procentu likmes, nevis tikai no mākslīgi izstiepta 10 gadu termiņa.</li>
<li><strong>Slēdz vecos kredītlimitus:</strong> tiklīdz iepriekšējie kredīti ir dzēsti, nosūti iesniegumu par kredītlīnijas līguma slēgšanu, lai izvairītos no atkārtotas aizņemšanās.</li>
</ol>

<blockquote>Praktisks piemērs: apvienojot trīs saistības ar kopējo atlikumu 4 200 € (iepriekšējie maksājumi kopā 275 € mēnesī ar vidējo GPL 31%), jaunais patēriņa kredīts uz 36 mēnešiem ar GPL 14,9% samazina ikmēneša maksājumu līdz <strong>143 € mēnesī</strong> un ietaupa vairāk nekā 850 € procentos.</blockquote>',
            ],
            [
                'slug' => 'ka-uzlabot-kreditvesturi-un-sanemt-zemaku-procentu-likmi',
                'order' => 4,
                'name' => 'Kā aizdevēji izvērtē maksātspēju un kā kvalificēties zemākai procentu likmei',
                'title' => 'Kredītvēsture un maksātspējas vērtēšana Latvijā — 5 faktori zemākai likmei',
                'description' => 'Ko banku un nebanku algoritmi pārbauda konta pārskatā, Latvijas Bankas Kredītu reģistrā un Kredītinformācijas birojā (KIB), nosakot individuālo procentu likmi.',
                'image' => '/images/blog/editorial-kreditvesture.svg',
                'image_alt_text' => 'Maksātspējas izvērtēšanas kritēriji un kredītreitings',
                'h1_description' => 'Kāpēc vienam pieteicējam aizdevējs piedāvā 8,9% gadā, bet citam — 24% vai atsaka? Ieskats kredītreitinga veidošanās mehānismā.',
                'source' => '<p>Mūsdienās lielākā daļa licencēto aizdevēju Latvijā izmanto individuālo cenu noteikšanu (<em>risk-based pricing</em>). Tas nozīmē, ka tabulā redzamā likme "no 6,9%" tiek piešķirta klientiem ar zemu riska profilu. Lūk, pieci faktori, kas tieši ietekmē Tev piedāvāto likmi.</p>',
                'text' => '<h2>1. Saistību attiecība pret ienākumiem (DTI rādītājs)</h2>
<p><strong>DTI (Debt-to-Income)</strong> parāda, cik lielu daļu no Taviem ikmēneša oficiālajiem neto ienākumiem aizņem visi esošie kredītmaksājumi. Latvijas kreditēšanas praksē drošā robeža ir <strong>līdz 30%–40%</strong> no neto algas. Ja mani ienākumi "uz rokas" ir 1 300 €, kopējie kredītu maksājumi nedrīkst pārsniegt 390 € – 450 € mēnesī.</p>

<h2>2. Latvijas Bankas Kredītu reģistrs un KIB dati</h2>
<p>Pirms lēmuma pieņemšanas aizdevējs automātiski pārbauda divas datubāzes:</p>
<ul>
<li><strong>Latvijas Bankas Kredītu reģistrs:</strong> uzrāda visas aktīvās un vēsturiskās saistības pret bankām, līzinga kompānijām un licencētiem aizdevējiem, kā arī kavējumus virs 60 dienām;</li>
<li><strong>Kredītinformācijas biroji (KIB un CREFO):</strong> uzrāda arī kavētus telekomunikāciju, komunālo pakalpojumu un distances pirkumu rēķinus.</li>
</ul>

<h2>3. Kas tiek analizēts bankas konta pārskatā?</h2>
<p>Kad pieslēdzies ar Smart-ID vai augšupielādē pēdējo 6 mēnešu konta izrakstu, automātiskais skoringa algoritms vērtē ne tikai algas apmēru:</p>
<ul>
<li><strong>Sarkanais karogs Nr. 1 — azartspēles un totalizatori:</strong> regulāri pārskaitījumi tiešsaistes kazino vai totalizatoriem gandrīz vienmēr noved pie tūlītēja atteikuma vai maksimālās riska likmes, pat ja alga ir augsta.</li>
<li><strong>Pozitīva naudas plūsma:</strong> vai pirms nākamās algas dienas kontā regulāri paliek brīvs atlikums, vai arī viss tiek iztērēts līdz nullei.</li>
<li><strong>Nepieteikti ātrie kredīti:</strong> biežas īstermiņa kredītlīniju izņemšanas pēdējo 90 dienu laikā signalizē par saspringtu budžetu.</li>
</ul>

<h2>Kā sagatavot savu profilu 60 dienas pirms pieteikuma?</h2>
<p>Ja plāno lielāku <a href="/paterina-krediti">patēriņa kredītu</a> vai mājokļa remontu, vismaz 2–3 mēnešus pirms pieteikuma: apmaksā visus rēķinus bez nevienas kavētas dienas, slēdz neizmantotās kredītkartes un kredītlīnijas (jo pat neizmantots kredītlimits tiek rēķināts kā potenciāla saistība) un pilnībā izslēdz impulsīvus augsta riska darījumus no sava pamata konta.</p>',
            ],
            [
                'slug' => 'kreditlinija-vai-paterina-kredits-ko-izveleties',
                'order' => 5,
                'name' => 'Kredītlīnija vai patēriņa kredīts: kuru finanšu instrumentu izvēlēties?',
                'title' => 'Kredītlīnija vai patēriņa kredīts — Atšķirības, procentu aprēķins un piemēri',
                'description' => 'Skaidrs salīdzinājums starp elastīgu kredītlīniju un klasisku patēriņa kredītu ar fiksētu grafiku: kurā situācijā ietaupīsi vairāk uz procentu maksājumiem.',
                'image' => '/images/blog/editorial-kreditlinija.svg',
                'image_alt_text' => 'Kredītlīnijas un patēriņa kredīta salīdzinājums',
                'h1_description' => 'Abi produkti ir pieejami internetā 15 minūšu laikā bez ķīlas, taču to procentu aprēķina mehānisms un atmaksas disciplīna ir pilnīgi atšķirīga.',
                'source' => '<p>Meklējot finansējumu no 300 € līdz 5 000 €, aizņēmēji visbiežāk svārstās starp <a href="/kreditlinija">kredītlīniju</a> un klasisku <a href="/paterina-krediti">patēriņa kredītu</a>. Lai gan abi aizdevumi tiek ieskaitīti bankas kontā vienlīdz ātri, katrs no tiem ir radīts atšķirīgam mērķim.</p>',
                'text' => '<h2>Kā darbojas kredītlīnija?</h2>
<p><strong>Kredītlīnija</strong> darbojas līdzīgi digitālai kredītkartei ar noteiktu naudas limitu (piemēram, 3 000 €). Galvenā priekšrocība ir elastība:</p>
<ul>
<li>Tu maksā procentus <strong>tikai par faktiski izņemto summu</strong> un tikai par tām dienām, kurās naudu izmanto;</li>
<li>Ja limits ir apstiprināts, bet naudu neizņem, uzturēšanas maksa ir 0,00 €;</li>
<li>Daudzi aizdevēji jaunajiem klientiem piedāvā pirmo 30 vai 60 dienu periodu ar <a href="/bezprocentu-krediti">0% procentu likmi</a>;</li>
<li>Katru mēnesi var segt vai nu pilnu summu, vai līgumā noteikto minimālo maksājumu.</li>
</ul>

<h2>Kā darbojas patēriņa kredīts?</h2>
<p><strong>Patēriņa kredīts</strong> ir vienreizējs aizdevums ar fiksētu summu un stingru ikmēneša atmaksas grafiku (no 6 mēnešiem līdz pat 10 gadiem). Tā galvenās priekšrocības:</p>
<ul>
<li><strong>Zemāka gada procentu likme (GPL):</strong> patēriņa kredītiem gada likme ir ievērojami zemāka nekā kredītlīnijām, tāpēc ilgtermiņā tas vienmēr ir lētāks;</li>
<li><strong>Skadrs gala datums:</strong> katrs ikmēneša maksājums vienmērīgi dzēš gan procentus, gan pamatsummu, neļaujot parādam ievilkties;</li>
<li><strong>Lielākas summas:</strong> pieejams līdz pat 25 000 € mājokļa labiekārtošanai, medicīnai vai lieliem pirkumiem.</li>
</ul>

<h2>Praktisks lēmumu pieņemšanas ceļvedis</h2>
<table>
<thead>
<tr>
<th>Situācija</th>
<th>Piemērotākais risinājums</th>
<th>Kāpēc?</th>
</tr>
</thead>
<tbody>
<tr>
<td>Nepieciešami 400 € līdz algas dienai (atdosiet 30 dienās)</td>
<td><strong>Kredītlīnija (ar 0% akciju)</strong></td>
<td>Atmaksājot mēneša laikā, pārmaksa ir 0 € vai minimāla par izmantotajām dienām.</td>
</tr>
<tr>
<td>Dzīvokļa remonts vai sadzīves tehnika par 2 500 € (atmaksa 24 mēn.)</td>
<td><strong>Patēriņa kredīts</strong></td>
<td>Zema fiksēta GPL un vienāds ikmēneša maksājums ietaupīs vairākus simtus eiro.</td>
</tr>
<tr>
<td>Nepieciešams drošības spilvens neparedzētiem rēķiniem</td>
<td><strong>Kredītlīnija</strong></td>
<td>Stāv rezervē bez maksas un ir pieejama jebkurā brīdī dažu minūšu laikā.</td>
</tr>
</tbody>
</table>',
            ],
        ];

        foreach ($blogPostsData as $post) {
            Sections::create([
                'type' => 'blog',
                'lang' => 'lv',
                'route_name' => $post['slug'],
                'active' => 1,
                'order' => $post['order'],
                'parent_section_id' => $blogs->id,
                'name' => $post['name'],
                'title' => $post['title'],
                'description' => $post['description'],
                'h1' => $post['name'],
                'h1_description' => $post['h1_description'],
                'image' => $post['image'],
                'image_alt_text' => $post['image_alt_text'],
                'source' => $post['source'],
                'text' => $post['text'],
                'display_in_the_header' => 0,
            ]);
        }
    }
}
