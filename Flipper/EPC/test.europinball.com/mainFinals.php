<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  printTopper();
  
  $content = '
    <script type="text/javascript" src="'.__baseHref__.'/js/contrib/jquery.bracket.min.js"></script>
    <link rel="stylesheet" type="text/css" href="'.__baseHref__.'/css/jquery.bracket.min.css" />
    
    <h2 class="entry-title">Main tournament finals</h2>
    
    <p>The new European Pinball Champion of 2013 is <a href="'.__baseHref__.'/player/?obj=player&id=171">Jorian Engelbrektsson</a> of <a href="'.__baseHref__.'/your-pages/country/?obj=country&id=188">Sweden</a>!</p>

    <p><a href="'.__baseHref__.'/wp-content/uploads/2013/09/IMG_62851.jpg"><img src="'.__baseHref__.'/wp-content/uploads/2013/09/IMG_62851.jpg" alt="IMG_6285" width="640" height="427" class="alignnone size-full wp-image-629" /></a></p>
    <br />
    <p>He beat <a href="'.__baseHref__.'/player/?obj=player&id=856">Krisztián Szalai</a> of <a href="'.__baseHref__.'/your-pages/country/?obj=country&id=95">Hungary</a> in the final, after winning on both <a href="'.__baseHref__.'/information/game/?obj=game&id=356">Tron</a> and <a href="'.__baseHref__.'/information/game/?obj=game&id=2">Attack from Mars</a>. The third game would have been <a href="'.__baseHref__.'/information/game/?obj=game&id=357">AC/DC</a>, but was never needed.</p>

    <p>The third place was claimed by <a href="'.__baseHref__.'/player/?obj=player&id=14579">Philippe Bocquet</a> from <a href="'.__baseHref__.'/country/?obj=country&id=72">France</a>, beating <a href="'.__baseHref__.'/player/?obj=player&id=306">René Gool</a> from the <a href="'.__baseHref__.'/country/?obj=country&id=158">Netherlands</a> in the bronze match on <a href="'.__baseHref__.'/information/game/?obj=game&id=354">Metallica</a>.</p>

    <p>The fair play prize was won by <a href="'.__baseHref__.'/player/?obj=player&id=69">David Kjellberg</a>. Playing multiball on <a href="'.__baseHref__.'/information/game/?obj=game&id=36">Fish Tales</a> with Super Jackpot lit, when one ball got stuck in the wheel. In stead of super jackpot frenzy, David called for the scorekeeper. And when opening the coin door to release the ball, the game reset, and David had to start all over again.</p>

    <div id="tabs">
      <ul>
        <li class="tabs"><a href="#treeView"><h2>Full finals tree</h2></a></li>
        <li class="tabs next"><a href="#listView"><h2>Full player list</h2></a></li>
      </ul>
      <div id="treeView">
        <input type="checkbox" id="hideByes" onclick="hideByes(this);">Hide byes<br />
        <div id="bracket"></div>
      </div>
      <div id="listView">
        <table id="list" class="listView">
          <thead>
            <tr>
              <th>Place</th>
              <th>WPPR place</th>
              <th>WPPR points</th>
              <th>Player</th>
              <th>Tag</th>
              <th>Country</th>
            </tr>
          </thead>
          <tbody>
    ';
    $players = getPlayers($dbh, ' where m.tournamentEdition_id = 1', 'order by ifnull(m.wpprPlace, m.place) asc');
            
    foreach ($players as $player) {
      if ($player->place) {
        $content .= '
            <tr>
              <td>'.$player->place.'</td>
              <td>'.$player->wpprPlace.'</td>
              <td>'.$player->wpprPoints.'</td>
              <td>'.$player->getLink().'</td>
              <td>'.$player->initials.'</td>
              <td><a href="'.__baseHref__.'/countries/?obj=country&id='.$player->country_id.'">'.$player->country.'</td>
            </tr>
        ';
     }
    }

    $content .= '
          </tbody>
        </table>
      </div>
    </div>
    <script type="text/javascript">
    
    $(document).ready(function() { 
      var classicsBracket = {
        "teams": [ '."
          ['Jorian Engelbrektsson', '-'],
          ['-', '-'],
          ['Stefan Cederlöf', 'Jochen Ludwig'],
          ['-', 'Per-Olof Romell'],
          ['Victor Håkansson', '-'],
          ['Martin Ayub', 'Mikael Tillander'],
          ['-', '-'],
          ['-', 'Albert Nomden'],
          ['Helena Walter', '-'],
          ['-', '-'],
          ['Peter Franck', 'Kjell Ömmersjö'],
          ['-', 'Daniele Celestino Acciari'],
          ['Magnus Rostö', '-'],
          ['Edwin Nijs', 'Mats Runsten'],
          ['-', '-'],
          ['-', 'Mikkel Sjølin'],
          ['Reidar Spets', '-'],
          ['-', '-'],
          ['Ari Sovijärvi', 'Bally Hagman'],
          ['-', 'Johan Lööv'],
          ['Marcus Hugosson', '-'],
          ['Pontus Qvarfordh', 'Olli-Mikko Ojamies'],
          ['-', '-'],
          ['-', 'Gabor Solymosi'],
          ['Patrik Bodin', '-'],
          ['-', '-'],
          ['René Gool', 'Zoltan Zsifkovits'],
          ['-', 'Roberto Pedroni'],
          ['Tobias Lund', '-'],
          ['Jonas Johansson', 'Craig Pullen'],
          ['-', '-'],
          ['-', 'Jörgen Holm'],
          ['Fredrik Malmqvist', '-'],
          ['-', '-'],
          ['Torben Jacobsen', 'Henrik Lagercrantz'],
          ['-', 'Linus Jorenbo'],
          ['David Kjellberg', '-'],
          ['Stefan Bergdahl', 'Johan Wadelius'],
          ['-', '-'],
          ['-', 'Philippe Bocquet'],
          ['Alvar Palm', '-'],
          ['-', '-'],
          ['Franck Bona', 'Ingemar Kemi'],
          ['-', 'Daniel Nowak'],
          ['Levente Tregova', '-'],
          ['Evert Brochez', 'Frans Bergbom'],
          ['-', '-'],
          ['-', 'Johan Genberg'],
          ['Per Ahlenius', '-'],
          ['-', '-'],
          ['Henrik Björk', 'Lars Ørskov Jensen'],
          ['-', 'John van der Wulp'],
          ['Paul Jongma', '-'],
          ['Patrik Lindberg', 'Jonas Rosenvik'],
          ['-', '-'],
          ['-', 'Johan Småros'],
          ['Michael Trepp', '-'],
          ['-', '-'],
          ['Jussi Rantala', 'Tommy Andersson'],
          ['-', 'Per Holknekt'],
          ['Mads Kristensen', '-'],
          ['David Mainwaring', 'Laszlo Horn'],
          ['-', '-'],
          ['-', 'Krisztián Szalai']".'
        ],
        "results": [            // List of brackets (single elimination, so only one bracket)
          [                     // List of rounds in bracket
            [                   // First round in this bracket
              [1, 0],
              [0, 1],
              [2, 0],
              [0, 1],
              [1, 0],
              [2, 0],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [1, 2],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [1, 2],
              [0, 1],
              [1, 0],
              [2, 1],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [2, 0],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [0, 2],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [2, 0],
              [0, 1],
              [1, 0],
              [1, 2],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [2, 1],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [1, 2],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 0],
              [0, 1]
            ],
            [                   // Second (final) round in single elimination bracket
              [1, 0],
              [0, 2],
              [2, 0],
              [0, 1],
              [1, 0],
              [0, 2],
              [0, 2],
              [0, 1],
              [1, 0],
              [2, 0],
              [2, 0],
              [0, 1],
              [1, 0],
              [2, 0],
              [1, 2],
              [0, 1],
              [1, 0],
              [2, 0],
              [0, 2],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 2],
              [0, 1],
              [1, 0],
              [2, 0],
              [2, 0],
              [0, 1],
              [1, 0],
              [0, 2],
              [1, 2],
              [0, 1]
            ],
            [
              [2, 0],
              [1, 2],
              [0, 2],
              [2, 0],
              [2, 0],
              [2, 0],
              [0, 2],
              [0, 2],
              [0, 2],
              [0, 2],
              [2, 1],
              [2, 0],
              [2, 1],
              [2, 1],
              [1, 2],
              [0, 2]
            ],
            [
              [2, 0],
              [1, 2],
              [0, 2],
              [2, 1],
              [0, 2],
              [2, 0],
              [2, 1],
              [0, 2]
            ],
            [
              [2, 1],
              [0, 2],
              [2, 0],
              [1, 2]
            ],
            [
              [2, 0],
              [0, 2]
            ],
            [
              [2, 0],
              [0, 1]
            ]
          ]
        ]
      };
      $(function() { 
        $("div#bracket").bracket({init: classicsBracket}) 
      });
      $(function() { 
        $("div.team").each(function() {
          if (!this.innerHTML.match(/<b>-<\/b>/)) {
            this.title = this.innerHTML.match(/<b>([^<]+)/)[1];
          }
        });
      });
    });
    function hideByes(box) {
      $(".team").each(function() {
        if (this.innerHTML.match(/<b>-<\/b>/)) {
          this.parentNode.parentNode.style.visibility = ((box.checked) ? "hidden" : "visible");
          // moveOpponent(this, box.checked);
        }
      });
      function moveOpponent(teamDiv, remove) {
        $(teamDiv).siblings(".team").each(function() {
          if ($(this).attr("index")) {
            $(".team[index=" + $(this).attr("index") + "]").each(function() {
              if ($(this.parentNode.parentNode).height() > 50) {
                console.log(this);
                $(this.parentNode.parentNode).height((+$(this.parentNode.parentNode).height() - ((remove) ? 50 : -50)));
                moveOpponent(this, box.checked);
              }
            });
          }
        });
      }
    }
    </script>
  ';
  $content .= getDataTables('.listView');
    
  echo($content);
  printFooter($dbh, $ulogin);
?>

