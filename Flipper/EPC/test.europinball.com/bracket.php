<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  printTopper();
  
  $content = '
    <script type="text/javascript" src="'.__baseHref__.'/js/contrib/jquery.bracket.min.js"></script>
    <link rel="stylesheet" type="text/css" href="'.__baseHref__.'/css/jquery.bracket.min.css" />
    
    <h2 class="entry-title">Classics finals</h2>
    <p>The European Classics Pinball Champion 2013 is the one-handed expert <a href="'.__baseHref__.'/player/?obj=player&id=94"><strong>Fredrik Malmqvist</a></strong> from <a href="'.__baseHref__.'/country/?obj=country&id=188">Sweden</a>!</p>

<img src="'.__baseHref__.'/images/rey-classics.jpg">

<p>The silver medal was claimed <a href="'.__baseHref__.'/player/?obj=player&id=663"><strong>Levente Tregova</strong></a> from <a href="'.__baseHref__.'/country/?obj=country&id=41">Switzerland</a>, bronze goes to <a href="'.__baseHref__.'/player/?obj=player&id=226"><strong>Martin Tiljander</strong></a> from <a href="'.__baseHref__.'/country/?obj=country&id=188">Sweden</a>, and fourth came <a href="'.__baseHref__.'/player/?obj=player&id=275"><strong>Patrik Lindberg</strong></a> from <a href="'.__baseHref__.'/country/?obj=country&id=188">Sweden</a>.</p>

<h1>Games played:</h1>

<p><strong><a href="'.__baseHref__.'/information/game/?obj=game&id=389">Black Jack</a></strong>:
1: <a href="'.__baseHref__.'/player/?obj=player&id=94">Fredrik Malmvist</a>
2: <a href="'.__baseHref__.'/player/?obj=player&id=663">Levente Tregova</a>
3: <a href="'.__baseHref__.'/player/?obj=player&id=275">Patrik Lindberg</a>
4: <a href="'.__baseHref__.'/player/?obj=player&id=226">Martin Tiljander</a></p>


<p><strong><a href="'.__baseHref__.'/information/game/?obj=game&id=392">Space Invaders</a></strong>:
1: <a href="'.__baseHref__.'/player/?obj=player&id=663">Levente Tregova</a>
2: <a href="'.__baseHref__.'/player/?obj=player&id=94">Fredrik Malmvist</a>
3: <a href="'.__baseHref__.'/player/?obj=player&id=275">Patrik Lindberg</a>
4: <a href="'.__baseHref__.'/player/?obj=player&id=226">Martin Tiljander</a></p>


<p><strong><a href="'.__baseHref__.'/information/game/?obj=game&id=390">Harlem Globetrotters</a></strong>:
1: <a href="'.__baseHref__.'/player/?obj=player&id=226">Martin Tiljander</a>
2: <a href="'.__baseHref__.'/player/?obj=player&id=94">Fredrik Malmvist</a>
3: <a href="'.__baseHref__.'/player/?obj=player&id=663">Levente Tregova</a>
4: <a href="'.__baseHref__.'/player/?obj=player&id=275">Patrik Lindberg</a></p>

<h2 class="entry-title">Full finals tree</h2>

    <input type="checkbox" id="hideByes" onclick="hideByes(this);">Hide byes<br />

    <div id="bracket"></div>
    <script type="text/javascript">
    
    $(document).ready(function() { 
      var classicsBracket = {
        "teams": [ '."
          ['Levente Tregova', '-'],
          ['-', '-'],
          ['Jorian Engelbrektsson', 'Mark van der Gugten'],
          ['-', 'Albert Nomden'],
          ['Evert Brochez', '-'],
          ['Pekka Niiranen', 'Alvar Palm'],
          ['-', '-'],
          ['-', 'Craig Pullen'],
          ['Magnus Rostö', '-'],
          ['-', '-'],
          ['Paul Jongma', 'Tommy Andersson'],
          ['-', 'Patrik Bengtsson'],
          ['Mats Runsten', '-'],
          ['Johan Småros', 'Olli-Mikko Ojamies'],
          ['-', '-'],
          ['-', 'Anders Birgersson'],
          ['Jochen Ludwig', '-'],
          ['-', '-'],
          ['Martin Tiljander', 'Jonas Rosenvik'],
          ['-', 'Henrik Tomson'],
          ['Mads Kristensen', '-'],
          ['Johan Lööv', 'CJ Brown'],
          ['-', '-'],
          ['-', 'Roger Hansson'],
          ['René Gool', '-'],
          ['-', '-'],
          ['Reidar Spets', 'Konrad Masłowski'],
          ['-', 'David Kjellberg'],
          ['Balázs Pálfi', '-'],
          ['Måns Jonasson', 'Harald Jönsson'],
          ['-', '-'],
          ['-', 'Frans Bergbom'],
          ['Johan Lundin', '-'],
          ['-', '-'],
          ['Pekka Salmia', 'Niklas Lepa'],
          ['-', 'Jeroen Wieringa'],
          ['Pierre Riesen', '-'],
          ['Glenn Verhoosele', 'Philippe Bocquet'],
          ['-', '-'],
          ['-', 'Glenn Bech'],
          ['Patrik Lindberg', '-'],
          ['-', '-'],
          ['Urban Bergh', 'Stefan Bååth'],
          ['-', 'Jussi Rantala'],
          ['Mats Holmqvist', '-'],
          ['Thomas Smedberg', 'Martin Ayub'],
          ['-', '-'],
          ['-', 'Per Holknekt'],
          ['Karl Broström', '-'],
          ['-', '-'],
          ['Lars Ørskov Jensen', 'jorma lindgren'],
          ['-', 'Svante Ericsson'],
          ['Marcus Hugosson', '-'],
          ['Fredrik Malmqvist', 'Linus Jorenbo'],
          ['-', '-'],
          ['-', 'Jörgen Holm'],
          ['Lieven Engelbeen', '-'],
          ['-', '-'],
          ['Per Ahlenius', 'Daniel Kaczmarek'],
          ['-', 'Mikael Telerud'],
          ['Jean-Louis Mergny', '-'],
          ['Johan Genberg', 'David Mainwaring'],
          ['-', '-'],
          ['-', 'Helena Walter']".'
        ],
        "results": [            // List of brackets (single elimination, so only one bracket)
          [                     // List of rounds in bracket
            [                   // First round in this bracket
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
              [0, 2],
              [0, 1],
              [1, 0],
              [1, 2],
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
              [2, 0],
              [0, 1],
              [1, 0],
              [2, 1],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [0, 2],
              [0, 1],
              [1, 0],
              [2, 1],
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
              [2, 0],
              [0, 1],
              [1, 0],
              [2, 1],
              [1, 0],
              [0, 1],
              [1, 0],
              [0, 1],
              [2, 1],
              [0, 1],
              [1, 0],
              [2, 0],
              [1, 0],
              [0, 1]
            ],
            [                   // Second (final) round in single elimination bracket
              [1, 0],
              [2, 0],
              [0, 2],
              [0, 1],
              [1, 0],
              [2, 1],
              [2, 0],
              [0, 1],
              [1, 0],
              [2, 0],
              [1, 2],
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
              [2, 0],
              [1, 2],
              [0, 1],
              [1, 0],
              [1, 2],
              [1, 2],
              [0, 1],
              [1, 0],
              [0, 2],
              [0, 2],
              [0, 1]
            ],
            [
              [2, 0],
              [2, 0],
              [2, 1],
              [2, 1],
              [1, 2],
              [1, 2],
              [1, 2],
              [1, 2],
              [2, 0],
              [2, 1],
              [2, 0],
              [2, 0],
              [1, 2],
              [2, 0],
              [1, 2],
              [1, 2]
            ],
            [
              [2, 1],
              [1, 2],
              [2, 0],
              [0, 2],
              [2, 0],
              [2, 0],
              [1, 2],
              [2, 0]
            ],
            [
              [2, 0],
              [2, 1],
              [0, 2],
              [2, 0]
            ],
            [
              [7, 4],
              [2, 8]
            ],
            [
              [7, 8],
              [4, 2]
            ]
          ]
        ]
      };
      $(function() { $("div#bracket").bracket({init: classicsBracket}) });
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
    
  echo($content);
  printFooter($dbh, $ulogin);
?>

