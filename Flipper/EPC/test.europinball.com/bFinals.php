<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  printTopper();
  
  $content = '
    <script type="text/javascript" src="'.__baseHref__.'/js/contrib/jquery.bracket.min.js"></script>
    <link rel="stylesheet" type="text/css" href="'.__baseHref__.'/css/jquery.bracket.min.css" />
    
    <h2 class="entry-title">B finals</h2>
    
    <p>The winner of the B finals was <a href="'.__baseHref__.'/player/?obj=player&id=217">Mark van der Gugten</a> of the <a href="'.__baseHref__.'/country/?obj=country&id=158">Netherlands</a>!</p>

    <p><a href="'.__baseHref__.'/wp-content/uploads/2013/09/IMG_6271.jpg"><img src="'.__baseHref__.'/wp-content/uploads/2013/09/IMG_6271.jpg" alt="IMG_6271" width="640" height="427" class="alignnone size-full wp-image-629" /></a></p>
    <br />
    <p>He beat <a href="'.__baseHref__.'/player/?obj=player&id=15">Anders Carlsson</a> of <a href="'.__baseHref__.'/your-pages/country/?obj=country&id=188">Sweden</a> in the final.</p>

    <p>The third place was claimed by <a href="'.__baseHref__.'/player/?obj=player&id=187">Klas Hellström</a> from <a href="'.__baseHref__.'/country/?obj=country&id=188">Sweden</a>, beating <a href="'.__baseHref__.'/player/?obj=player&id=13763">Bo Mertins</a> from <a href="'.__baseHref__.'/country/?obj=country&id=56">Denmark</a> in the bronze match.</p>

    <h2 class="entry-title">Full finals tree</h2>

    <input type="checkbox" id="hideByes" onclick="hideByes(this);">Hide byes<br />

    <div id="bracket"></div>
    <script type="text/javascript">
    
    $(document).ready(function() { 
      var classicsBracket = {
        "teams": [ '."
          ['Anders Carlsson', '-'],
          ['-', '-'],
          ['Jarkko Kuoppamäki', 'Walk over'],
          ['-', 'Mikael Telerud'],
          ['Lieven Engelbeen', '-'],
          ['Glenn Bech', 'Pekka Niiranen'],
          ['-', '-'],
          ['-', 'Mithras Ljungberg'],
          ['Gustav Enekull', '-'],
          ['-', '-'],
          ['Andreas Nirven', 'Peter Roos'],
          ['-', 'Johan Svensson'],
          ['Linus Persson', '-'],
          ['James Watson', 'Marco Suvanto'],
          ['-', '-'],
          ['-', 'Christian Holmsten'],
          ['Thomas Smedberg', '-'],
          ['-', '-'],
          ['Andrzej Cieslik', 'Janne Åström'],
          ['-', 'Elias Persson'],
          ['Gerard Poelwijk', '-'],
          ['Daniel Kaczmarek', 'Robin Kemp'],
          ['-', '-'],
          ['-', 'Konrad Maslowski'],
          ['Timo Valkonen', '-'],
          ['-', '-'],
          ['Jacek Wenda', 'Mats Sahlberg'],
          ['-', 'Bo Mertins'],
          ['Linda Eriksson', '-'],
          ['Roni Valkonen', 'Axl Hydén'],
          ['-', '-'],
          ['-', 'Jonas Andersson'],
          ['Marcin Kisiel', '-'],
          ['-', '-'],
          ['Anders Birgersson', 'Dimitri Verhoosele'],
          ['-', 'Urban Bergh'],
          ['Stefan Bååth', '-'],
          ['Lasse Carlsson', 'Robert Lau'],
          ['-', '-'],
          ['-', 'Svante Ericsson'],
          ['Andreas Wesik', '-'],
          ['-', '-'],
          ['Jeroen Wieringa', 'Marcin Dylewski'],
          ['-', 'Teemu Vinnikka'],
          ['Klas Hellström', '-'],
          ['Joonas Haverinen', 'Glenn Verhoosele'],
          ['-', '-'],
          ['-', 'Peter Blakemore'],
          ['CJ Brown', '-'],
          ['-', '-'],
          ['Antti Peltonen', 'Balazs Takacs'],
          ['-', 'Rolph Ericson'],
          ['Mathias Leurs', '-'],
          ['Tim Slow', 'jorma lindgren'],
          ['-', '-'],
          ['-', 'Justin van Schooneveld'],
          ['Carsten Fromberg', '-'],
          ['-', '-'],
          ['Christer Öhman', 'Rune Raaschou'],
          ['-', 'Bo Olsson'],
          ['Jaakko Patrakka', '-'],
          ['Måns Jonasson', 'Pekka Salmia'],
          ['-', '-'],
          ['-', 'Mark van der Gugten']".'
        ],
        "results": [            // List of brackets (single elimination, so only one bracket)
          [                     // List of rounds in bracket
            [                   // First round in this bracket
              [1, 0],
              [0, 1],
              [1, 0],
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
              [2, 1],
              [0, 1],
              [1, 0],
              [2, 1],
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
              [0, 2],
              [1, 0],
              [0, 1],
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
              [0, 2],
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
              [1, 2],
              [1, 0],
              [0, 1]
            ],
            [                   // Second (final) round in single elimination bracket
              [1, 0],
              [2, 1],
              [2, 0],
              [0, 1],
              [1, 0],
              [2, 1],
              [2, 1],
              [0, 1],
              [1, 0],
              [2, 1],
              [2, 1],
              [0, 1],
              [1, 0],
              [0, 2],
              [0, 2],
              [0, 1],
              [1, 0],
              [0, 2],
              [2, 1],
              [0, 1],
              [1, 0],
              [1, 2],
              [2, 1],
              [0, 1],
              [1, 0],
              [1, 2],
              [2, 1],
              [0, 1],
              [1, 0],
              [0, 2],
              [0, 2],
              [0, 1]
            ],
            [
              [2, 0],
              [2, 0],
              [1, 2],
              [2, 0],
              [0, 2],
              [0, 2],
              [0, 2],
              [1, 2],
              [2, 0],
              [0, 2],
              [0, 2],
              [2, 1],
              [0, 2],
              [2, 0],
              [0, 2],
              [0, 2]
            ],
            [
              [2, 1],
              [0, 2],
              [2, 1],
              [2, 0],
              [2, 1],
              [1, 2],
              [2, 0],
              [1, 2]
            ],
            [
              [2, 0],
              [1, 2],
              [1, 2],
              [1, 2]
            ],
            [
              [2, 1],
              [0, 2]
            ],
            [
              [1, 2],
              [0, 2]
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
    
  echo($content);
  printFooter($dbh, $ulogin);
?>

