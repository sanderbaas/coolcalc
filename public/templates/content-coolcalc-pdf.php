<?php
setlocale(LC_ALL, array('nl_NL','nl_NL.utf8','NL'));
function my_money_format($num) {
  return number_format($num,2,',','.');
}
?>
<div class="pdf-content">
  <p style="text-align:right;"><br /><br /><br />Raamsdonksveer, <?php echo strftime('%e %B %Y', time());?></p>
  <p><br /><br /><br /><br /><br /><br /><br /><br /></p>
  <p>Geachte heer/mevrouw,</p>
  <p>Hartelijk dank voor de belangstelling voor één van onze koelmachines. Op de volgende pagina's
treft u de prijsopgave en specificatie aan van de door u samengestelde koelmachine.</p>
  <p>Wij kunnen ons voorstellen dat u na het samenstellen van deze koelmachine nog vragen heeft.
Aarzelt u dan niet om telefonisch contact met ons op te nemen of om een afspraak te maken voor een vrijblijvend en persoonlijk gesprek.
Uiteraard kunt u ons ook vragen stellen via info@goedkopekoelmachines.nl.</p>
  <p>Onze openingstijden zijn op maandag tot en met vrijdag van 8:00 tot 17:00 uur.</p>
  <p>Met vriendelijke groet,</p>
  <p>Goedkope Koelmachines</p>
</div>
<pagebreak />
<div class="pdf-content">
  <h1 class="center">Offerte</h1>
  <h2>Koelmachine</h2>
  <table class="brdr" width="100%">
    <tbody>
      <tr>
        <td><?php echo $machine->post_title; ?></td>
        <td align="right">&euro;</td>
        <td align="right" width="80"><?php echo my_money_format($modelPrice); ?></td>
      </tr>
    </tbody>
  </table>

  <?php if (sizeof($options) > 0): ?>
    <br /><h2>Meerprijzen</h2>
    <table class="brdr" width="100%">
    <tbody>
      <?php foreach ($options as $option): ?>
        <tr>
          <td><?php echo $option->post_title; ?></td>
          <td align="right">&euro;</td>
          <td align="right" width="80"><?php echo my_money_format($option->meta['price'][0]); ?></td>
        </tr>
      <?php endforeach; ?>
        <tr>
          <td>Totaal</td>
          <td align="right">&euro;</td>
          <td align="right" width="80"><?php echo my_money_format($optionTotal); ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <?php if (sizeof($installOptions) > 0): ?>
    <br /><h2>Installatieprijzen</h2>
    <table class="brdr" width="100%">
    <tbody>
      <?php foreach ($installOptions as $option): ?>
        <tr>
          <td><?php echo $option->post_title; ?></td>
          <td align="right">&euro;</td>
          <td align="right" width="80"><?php echo my_money_format($option->meta['price'][0]); ?></td>
        </tr>
      <?php endforeach; ?>
        <tr>
          <td>Totaal</td>
          <td align="right">&euro;</td>
          <td align="right" width="80"><?php echo my_money_format($installOptionTotal); ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <br /><h2>BTW en totalen</h2>
  <table class="brdr" width="100%">
    <tbody>
      <tr>
        <td>Totaal (exclusief BTW)</td>
        <td align="right">&euro;</td>
        <td align="right" width="80"><?php echo my_money_format($totalPrice);?></td>
      </tr>
      <tr>
        <td>BTW</td>
        <td align="right">&euro;</td>
        <td align="right" width="80"><?php echo my_money_format($tax);?></td>
      </tr>
      <tr>
        <td>Totaal (inclusief BTW)</td>
        <td align="right">&euro;</td>
        <td align="right" width="80"><?php echo my_money_format($totalInclPrice);?></td>
      </tr>
    </tbody>
  </table>
</div>
<pagebreak />
<div class="pdf-content">
  <h1><?php echo $machine->post_title; ?></h1>
  <p>Koelcapaciteit: <?php echo $machine->meta['capacity'][0]; ?>kW</p>
  <?php if (isset($machine->image)) { ?>
  <p><img class="center-block img-responsive" src="<?php echo $machine->image; ?>"></p>
  <?php } ?>
  <?php echo $machine->post_content;?>
</div>
  <?php if (sizeof($options) > 0): ?>
<pagebreak />
<div class="pdf-content">
  <br /><h1>Meerprijzen</h1>
  <?php foreach($options as $option): ?>
  <h2><?php echo $option->post_title;?></h2>
  <p class="list-group-item-text"><?php echo $option->post_content_html; ?></p>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php if (sizeof($options) > 0): ?>
<pagebreak />
<div class="pdf-content">
  <br /><h1>Installatie-opties</h1>
  <?php foreach($installOptions as $option): ?>
  <h2><?php echo $option->post_title;?></h2>
  <p class="list-group-item-text"><?php echo $option->post_content_html; ?></p>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<pagebreak />
<div class="pdf-content">
  <h1 class="terms">ALGEMENE VERKOOP- EN LEVERINGSVOORWAARDEN CLIMATE SOLUTIONS HOLLAND B.V.</h1>
<p class="terms">Gedeponeerd bij de Kamer van Koophandel te Breda, Nederland, op 11 januari 2008, onder het nummer 18089369</p>
<table id="terms" width="100%">
  <tbody>
    <tr>
      <td valign="top">
        <table>
          <tbody>
            <tr>
              <td>1</td>
              <td>ALGEMEEN</td>
            </tr>
            <tr>
              <td>1.a</td>
              <td>
                Onder  ‘CSH’ wordt in deze algemene voorwaarden verstaan Climate Solutions
                Holland B.V. Onder ‘afnemer’ wordt verstaan de partij waaraan CSH zaken en/of diensten verkoopt en/of levert.
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>ALGEMEEN/TOEPASSING</td>
            </tr>
            <tr>
              <td>2.a</td>
              <td>Alle bestellingen en/of opdrachten geplaatst bij CSH door de Afnemer en alle daaruit
        voortvloeiende rechtsverhoudingen tusse
        n CSH en de Afnemer worden uitsluitend door CSH
        geaccepteerd op basis van deze Algemene Verkoop
        -
        en Leveringsvoorwaarden (‘Algemene
        Voorwaarden’). Toepasselijkheid van de door de Afnemer gehanteerde algemene voorwaarden
        wordt uitdrukkelijk van de hand gewe
        zen.</td>
            </tr>
            <tr>
              <td>2.b</td>
              <td>Deze Algemene Voorwaarden zijn van toepassing op alle rechtsbetrekkingen (‘Overeenkomst’),
        waarbij CSH als (potentieel) verkoper en /of leverancier van zaken en/of diensten (‘Order)
        optreedt.</td>
            </tr>
            <tr>
              <td>2.c</td>
              <td>Van deze Algemene Voorwaarden kan slechts worden afgeweken
        indien partijen zulks
        uitdrukkelijk schriftelijk zijn overeengekomen.</td>
            </tr>
            <tr>
              <td>2.d</td>
              <td>Inkoopvoorwaarden van Afnemers gelden slechts voor zover zij niet in strijd zijn met de
        onderhavige Algemene Voorwaarden.</td>
            </tr>
            <tr>
              <td>3</td>
              <td>AANBIEDINGEN</td>
            </tr>
            <tr>
              <td>3.a</td>
              <td>CSH’s
        aanbiedingen zijn vrijblijvend: CSH is eerst gebonden nadat zij de Order schriftelijk heeft
        bevestigd danwel een begin met de uitvoering daarvan heeft gemaakt.</td>
            </tr>
            <tr>
              <td>4</td>
              <td>DOCUMENTATIE</td>
            </tr>
            <tr>
              <td>4.a</td>
              <td>De in CSH’s catalogi, prijslijsten, aanbiedingen, circulaires, afbeeldingen, tekeningen, schema’s en
        andere bescheiden vermelde gegevens met betrekking tot maten, gewichten en andere
        hoedanigheden van de producten/diensten van CSH zijn niet bindend.</td>
            </tr>
            <tr>
              <td>4.b</td>
              <td>De auteursrechten op bedoelde bescheiden en op al datgene wat door CSH wordt gepubliceerd
              behoudt CSH zich voor. CSH’s publicaties mogen zonder uitdrukkelijke toestemming van CSH noch
        geheel, noch gedeeltelijk worden gekopieerd, aan derden ter hand gesteld, of ter inzage worden
        gegeven noch op enigerlei andere wijze aan derden ter beschikking gesteld.</td>
            </tr>
            <tr>
              <td>5</td>
              <td>PRIJZEN</td>
            </tr>
            <tr>
              <td>5.a</td>
              <td>CSH’s prijzen zijn vrijblijvend. Indien kostprijsfactoren zoals bijvoorbeeld materiaalprijzen, lonen,
        sociale en/of andere overheidslasten, zoals invoerrechten en B.T.W., vrachten of
        assurantiepremies verhoging ondergaan of een prijssti
        jging intreedt als gevolg van
        waardevermindering van de Nederlandse valuta nadat CSH de Order schriftelijk heeft bevestigd,
        ook al geschiedt dit ingevolge reeds bij de schriftelijke bevestiging te voorziene omstandigheden,
        is CSH gerechtigd de aan CSH vers
        chuldigde prijs, met inachtneming van de eventueel ter zake
        bestaande wettelijke voorschriften, dienovereenkomstig te wijzingen.</td>
            </tr>
            <tr>
              <td>5.b</td>
              <td>CHS behoudt zich het recht voor verpakking tegen de kostprijs in rekening te brengen.</td>
            </tr>
            <tr>
              <td>5.c</td>
              <td>De Afnemer wordt geacht akkoord te zijn g
        egaan met de aldus gewijzigde prijs indien hij niet
        binnen acht (8) dagen na verzending door CSH van de mededeling van de prijswijziging, daartegen
        bij CSH schriftelijk heeft geprotesteerd.</td>
            </tr>
            <tr>
              <td>6</td>
              <td>LEVERING</td>
            </tr>
            <tr>
              <td>6.a</td>
              <td>Levering geschiedt franco afleveringsadres aan verharde
        weg in Nederland, onafgeladen van
        vrachtwagen.</td>
            </tr>
            <tr>
              <td>6.b</td>
              <td>Voor opdrachten beneden een door CSH telkens nader vast te stellen factuurbedrag (exclusief
        B.T.W.) behoudt CSH zich het recht voor de vrachtkosten in rekening te brengen, respectievelijk
        een toeslag te bereke
        nen overeenkomstig het ten tijde van de levering bij CSH geldende
        toeslagtarief.  Ook indien CSH de vrachtkosten in rekening brengt is de keuze van de wijze van
        vervoer aan CSH.</td>
            </tr>
            <tr>
              <td>6.c</td>
              <td>De verzending van goederen geschiedt steeds, dus ook indien franco levering is
        overeengekomen,
        voor risico van de Afnemer, zelfs dan wanneer voor CSH’s zendingen door de vervoerder de
        verklaring op de vrachtbrieven wordt gevorderd dat alle schaden gedurende het transport voor
        rekening van de afzender zijn.</td>
            </tr>
            <tr>
              <td>7</td>
              <td>LEVERINGSTIJD</td>
            </tr>
            <tr>
              <td>7.a</td>
              <td>Met CSH overeengekomen leveringstijden gelden slechts als indicatie en niet als fatale termijn,
        tenzij uitdrukkelijk anders is overeengekomen.</td>
            </tr>
            <tr>
              <td>7.b</td>
              <td>De opgegeven leveringstijd gaat in nadat CSH de Order schriftelijk heeft bevestigd, alle
        formaliteiten welke nodig zijn voor
        de aanvang der werkzaamheden zijn vervuld en de Afnemer
        CSH de, naar het oordeel van CSH, voor de uitvoering van de Order vereiste gegevens ter
        beschikking heeft gesteld. Is een vooruitbetaling bedongen dan gaan de leveringstijd pas in als
        deze is ontvangen en aan de eerder genoemde voorwaarden is voldaan. </td>
            </tr>
            <tr>
              <td>7.c</td>
              <td>De zaken en/of diensten die in het kader van een Order geleverd dienen te worden, gelden ten
        aanzien van de leveringstijd als geleverd wanneer zij voor verzending aan de Afnemer gereedstaan respectievelijk wanneer deze diensten aan Afnemer ter uitvoering worden aangeboden,
        doch deze daartoe niet de benodigde medewerking verleent.</td>
            </tr>
            <tr>
              <td>7.d</td>
              <td>Overschrijding van de leveringstijd, door welke oorzaak ook, zal de Afnemer nooit het recht geven
        op schadevergoeding, ontbin
        ding der Overeenkomst, of andere vordering uit hoofde van nietnakoming van enige verplichting welke voor hem uit deze of enige andere met deze
        Overeenkomst samenhangende overeenkomst mocht voortvloeien. Indien een fatale termijn is
        overeengekomen, geeft overschrijding daarvan de Afnemer uitsluitend het recht tot ontbinding
        van de Overeenkomst.</td>
            </tr>
            <tr>
              <td>7.e</td>
              <td>CSH is bevoegd een Order in haar geheel dan wel, na successievelijk beschikbaar komen van de
        zaken en/of diensten, in gedeelten uit te leveren. In dit geval
        gerechtigd per factuur, betrekking
        hebbende op een deellevering, betaling te verlangen overeenkomstig de geldende
        betalingsvoorwaarden</td>
            </tr>
            <tr>
              <td>8</td>
              <td>RISICO EN EIGENDOMSOVERDRACHT</td>
            </tr>
            <tr>
              <td>8.a</td>
              <td>Dadelijk nadat de zaken en/of diensten als geleverd gelden in de zin van artikel 7.c
        draagt de Afnemer het risico voor alle directe of indirecte schade welke aan of door deze zaken en/of
        diensten, voor de Afnemer of voor derden, mocht ontstaan.</td>
            </tr>
            <tr>
              <td>8.b</td>
              <td>Onverminderd het gestelde in het vorige lid en in artikel 7.c
        gaat de eigendom van de zaken eerst op de Afnemer over zodra al hetgeen Afnemer CSH ter zake van deze zaken is verschuldigd, met
        inbegrip van eventuele renten en kosten, is voldaan.</td>
            </tr>
            <tr>
              <td>8.c</td>
              <td>Met betrekking tot onbetaalde rekeningen zal worden aangenomen dat in het magazijn van
        Afnemer aanwezige voorrad en van door CSH geleverde artikelen daarop betrekking hebben.
        Afnemer is verplicht deze voorraden van CSH afgescheiden van andere zaken, en voldoende
        identificeerbaar opgeslagen te houden.</td>
            </tr>
            <tr>
              <td>8.d</td>
              <td>Indien enige zaak ingevolge lid b of lid caan CSH toekomt, kan de
        Afnemer daarover uitsluitend beschikken in het kader van zijn normale bedrijfsuitoefening. Afnemer is verplicht zaken welke
        CSH heeft geleverd onder eigendomsvoorbehoud, te verzekeren en verzekerd te houden. Daarbij
        is Afnemer verplicht, ingeval de zaken overgaan naar een derde, ook deze derde de verplichting
        op te leggen de zaken adequaat te verzekeren.</td>
            </tr>
            <tr>
              <td>8.e</td>
              <td>Indien Afnemer in verzuim is ten aanzien van de prestaties als in lid 3 bedoeld, is CSH gerechtigd
        de zaken, die aan haar toebehoren, zelf de rekening van de Afnemer terug te (doen) halen van de
        plaats waar zij zich bevinden. Afnemer verleent CSH reeds nu voor alsdan onherroepelijk
        machtiging om daartoe bij of voor Afnemer in gebruik zijnde ruimten te (doen) betreden.</td>
            </tr>
          </tbody>
        </table>
      </td>
      <td valign="top">
        <table>
          <tbody>
            <tr>
              <td>9</td>
              <td>VERPLICHTE INFORMATIEVERSCHAFFING</td>
            </tr>
            <tr>
              <td>9.a</td>
              <td>In dien en zodra de zaken die eigendom zijn van CSH in beslag worden genomen, is de Afnemer
        verplicht om CSH hiervan terstond in kennis te stellen.</td>
            </tr>
            <tr>
              <td>9.b</td>
              <td>De Afnemer is verplicht om de persoon die zaken in beslag neemt die eigendom zijn van CSH dan wel
        de persoon die namens die persoon rechten uitoefent op deze zaken, mede te delen dat de zaken
        eigendom zijn van CSH.</td>
            </tr>
            <tr>
              <td>10</td>
              <td>GARANTIE EN NON-CONFORMITEIT</td>
            </tr>
            <tr>
              <td>10.a</td>
              <td>Met inachtneming van de hierna gestelde beperkingen staat CSH in, zowel voor de deugdelijkheid
        van de door CSH geleverde goederen als voor de kwaliteit van het daarvoor door CSH geleverde en
        gebruikte materiaal, met dien verstande dat alle gebreken aan de geleverde goederen, waarvan de
        Afnemer bewijst dat zij binnen twaalf maanden na de levering in de zin van artikel 7.c
        zijn ontstaan, uitsluitend als gevolg van door CSH ontworpen, doch ondeugdelijke constructie, gebrekkige
        afwerking of gebruik van slecht materiaal, door CSH kosteloos worden hersteld. Hiertoe dient de
        Afnemer de defecte goederen franco aan CSH’s adres te retourneren. CSH is dus nimmer verplicht
        eventuele schadegevallen ter plaatse te herstellen. Deze garantieverplichtingen vervallen indien en
        zodra de Afnemer en/of derden werkzaamheden aan het geleverde hebben verricht zonder onze
        schriftelijke toestemming.</td>
            </tr>
            <tr>
              <td>10.b</td>
              <td>Indien CSH het ter voldoening aan haar garantieverplichting geraden acht zaken of onderdelen door
        nieuwe te vervangen zal CSH deze kosteloos en franco, doch overigens onder dezelfde voorwaarden
        als voor de te vervangen delen gelden, leveren. Zaken of onderdelen, welke door nieuwe worden
        vervangen, worden door de aflevering hiervan CSH’s eigendom en worden door Afnemer franco aan
        CSH teruggezonden.</td>
            </tr>
            <tr>
              <td>10.c</td>
              <td>De garantie geldt niet voor gebreken welke het gevolg zijn van enig overheidsvoorschrift met
        betrekking tot de aard of de kwaliteit van de toegepaste materialen; zij geldt ook niet voor lakwerk
        en chroomwerk, tenzij de beschadigingen daarvan het gevolg zijn van kwaliteit
        -
        en/of
        constructiefouten van andere delen.</td>
            </tr>
            <tr>
              <td>10.d</td>
              <td>De Afnemer heeft de verplichting bij aflevering te onderzoeken of de zaken aan de Overeenkomst
        beantwoorden. Indien dit niet het geval is, dan kan de Afnemer daarop geen beroep meer doen,
        indien hij CSH daarvan niet zo spoedig mogelijk en in ieder geval binnen acht dagen na aflevering,
        althans nadat constatering redelijkerwijze mogelijk was, schriftelijk en gemotiveerd kennis heeft
        gegeven.</td>
            </tr>
            <tr>
              <td>10.e</td>
              <td>Indien een gebrek in de zaak of afwijking van de Overeenkomst pas blijkt na twee maanden na de
        afleveringsdatum, kan de Afnemer zich er niet meer op beroepen dat de zaak niet aan de Overeenkomst beantwoordt.</td>
            </tr>
            <tr>
              <td>10.f</td>
              <td>Vorderingen en verweren, gegrond op feiten die de stelling zouden rechtvaardigen, dat de
        afgeleverde zaak/dienst niet aan de Overeenkomst beantwoordt, verjaren door verloop van twee
        maanden na aflevering.</td>
            </tr>
            <tr>
              <td>10.g</td>
              <td>Het beweerdelijk niet-nakomen van onze garantieverplichting ontheft de Afnemer niet van de
        verplichtingen welke voor hem uit deze of uit enige andere met CSH gesloten Overeenkomst
        mochten voortvloeien.</td>
            </tr>
            <tr>
              <td>10.h</td>
              <td>CSH is tot geen enkele garantie - hoe ook genaamd – gehouden indien de Afnemer niet, niet
        behoorlijk, of niet tijdig voldoet aan enige verplichting welke voor hem uit deze of uit enige andere
        met deze Overeenkomst samenhangende overeenkomst mocht voortvloeien.</td>
            </tr>
            <tr>
              <td>10.i</td>
              <td>CSH’s aansprakelijkheid is beperkt tot de garantieverplichting hierboven omschreven. CSH is nimmer
        aansprakelijk voor enige directe of indirecte schade, direct of indirect veroorzaakt door de werking of
        niet-werking van de door CSH geleverde of bewerkte zaak en/of dienst, of door in CSH’s dienst zijnd
        personeel toegebracht aan zaken en/of personen – welke of wie ook, tenzij deze schade
        toerekenbaar is aan opzet of grove schuld van CSH zelf. Afnemer is gehouden CSH tegen alle
        vorderingen van derden ter zake van zodanige kosten, schaden en interesse te vrijwaren.</td>
            </tr>
            <tr>
              <td>10.j</td>
              <td>Indien door bijvoorbeeld in- en/of uitvoerverboden, stakingen of andere niet te voorziene
        omstandigheden de garantieverplichtingen niet kunnen worden nagekomen, worden deze
        opgeschort.</td>
            </tr>
            <tr>
              <td>10.k</td>
              <td>Dit artikel is zowel van toepassing op de verlening van diensten als op de levering van zaken.</td>
            </tr>
            <tr>
              <td>11</td>
              <td>BETALING</td>
            </tr>
            <tr>
              <td>11.a</td>
              <td>Betaling moet geschieden binnen dertig dagen na levering van de goederen of uitvoering van  de
        werkzaamheden, tenzij schriftelijk anders is overeengekomen.  Het is de Afnemer niet toegestaan
        enig bedrag met het door hem verschuldigde in compensatie te brengen dan wel zijn betaling op te
        schorten.</td>
            </tr>
            <tr>
              <td>11.b</td>
              <td>De Afnemer kan slechts binnen de betalingstermijn bezwaar maken tegen de factuur.</td>
            </tr>
            <tr>
              <td>11.c</td>
              <td>Bij overschrijding van de betalingstermijn is de Afnemer vanaf de vervaldatum, zonder dat in gebrekestelling vereist is,
              gehouden een rente te betalen van 1% per maand over het van tijd tot tijd
        uitstaande bedrag. Alle gerechtelijke en buitengerechtelijke kosten die CSH moet maken ter inning
        van het door Afnemer verschuldigde, komen ten laste van
        Afnemer. De buitengerechtelijke kosten worden geacht minimaal 15% van de vordering te bedragen met een minimum van € 500,--.
        CSH is voorts, indien de Afnemer zijn verplichtingen niet nakomt, gerechtigd zonder dat gerechtelijke
        tussenkomst is vereist de Overeenkomst geheel of gedeeltelijk te ontbinden.</td>
            </tr>
            <tr>
              <td>11.d</td>
              <td>De betalingen door of vanwege Afnemer strekken achtereenvolgens ter voldoening van de door hem
        verschuldigde buitenrechtelijke incassokosten, de gerechtelijke kosten, de door hem verschuldigde
        renten en daarna
        de volgorde van ouderdom de openstaande hoofdsommen, ongeacht
        andersluidende aanwijzing van Afnemer.</td>
            </tr>
            <tr>
              <td>12</td>
              <td>ZEKERHEID</td>
            </tr>
            <tr>
              <td>12.a</td>
              <td>Indien er goede grond bestaat dat Afnemer zijn verplichtingen niet stipt zal nakomen, is Afnemer
        verplicht op eerste verzoek van CSH terstond g
        enoegzame en in de door CSH gewenste vorm
        zekerheid te stellen en deze zo nodig aan te vullen voor de nakoming van al zijn verplichtingen.
        Zolang Afnemer daaraan niet voldaan heeft, is CSH gerechtigd de nakoming van haar verplichtingen
        op te schorten zonde
        r enige verplichting tot vergoeding van schade of kosten welke Afnemer lijdt ten
        gevolge daarvan.</td>
            </tr>
            <tr>
              <td>13</td>
              <td>BEEINDIGING EN OPSCHORTING</td>
            </tr>
            <tr>
              <td>13.a</td>
              <td>Indien Afnemer niet stipt of tijdig presteert conform de Overeenkomst, of in geval van faillissement
        van de Afnemer, of indien
        Afnemer onder curatele wordt gesteld of (een belangrijk deel van) de
        bedrijfsvoering van de Afnemer wordt opgeschort dan wel geliquideerd, is CSH te allen tijde, zonder
        enige verplichting tot schadeloosstelling en niettegenstaande eventuele overige rechten
        van CSH,
        gerechtigd naar eigen goeddunken de Overeenkomst geheel of gedeeltelijk te beëindigen dan wel de
        (verdere) prestaties krachtens de Overeenkomst op te schorten. In dergelijke gevallen is CSH
        gerechtigd deze rechten met onmiddellijke ingang uit te
        oefenen.</td>
            </tr>
            <tr>
              <td>14</td>
              <td>TOEPASSELIJK  RECHT/BEVOEGDE RECHTER</td>
            </tr>
            <tr>
              <td>14.a</td>
              <td>Alle rechtsverhoudingen tussen CSH en Afnemer worden beheerst door Nederlands recht. Partijen
        zijn overeengekomen dat de <sup>1)</sup> United Nations Convention on the international Sale of Goods 1980  (CISG) niet van toepassing is.</td>
            </tr>
            <tr>
              <td>14.b</td>
              <td>Alle geschillen tussen CSH en de Afnemer zullen worden berecht door het Kantongerecht of de
        Arrondissementsrechtbank te Breda.</td>
            </tr>
            <tr>
              <td><sup>1)</sup></td>
              <td>Verdrag inzake het recht dat van toepassing is op internationale koopovereenkomsten
        betreffende roerende zaken.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
</div>