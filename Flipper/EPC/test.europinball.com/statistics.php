<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');

  printHeader('EPC 2013', __baseHref__);
  printTopper();
    
  function divide($x, $y) {
    if ($x == 0) {
      return 0;
    }
    if ($y == 0) {
      return $x;
    }
    return $x / $y;
  }
  
  $content = '<h2 class="entry-title">Statistics</h2>';
  
  $query = 'select count(*) as players, count(ifpaRank) as ranked, min(ifpaRank) as min, avg(ifpaRank) as avg, max(ifpaRank) as max from player where ifnull(ifpaRank,20000) != 0 and tournamentDivision_id = 1';
  $sth = $dbh->query($query);
  $res = $sth->fetchObject();
  
  $content .= '
        <br /><br /><h2 class="entry-title">IFPA rankings</h2>
        <table>
          <thead>
            <tr>
              <th class="numeric">Players</th>
              <th class="numeric">Ranked</th>
              <th class="numeric">Unranked</th>
              <th class="numeric">Highest</th>
              <th class="numeric">Lowest</th>
              <th class="numeric">Average</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>'.$res->players.'</td>
              <td>'.$res->ranked.'</td>
              <td>'.($res->players - $res->ranked).'</td>
              <td>'.$res->min.'</td>
              <td>'.$res->max.'</td>
              <td>'.round($res->avg).'</td>
            </tr>
          </tbody>
        </table>
  ';
  
  foreach($classes as $class) {
    $total = [];
    $num = [];
    $regionSumTd = '';
    $countrySumTd = '';
    if ($classes->{$class->name}->children) {
      $thead = '
        <br /><br /><h2 class="entry-title">'.ucfirst($classes->{$class->name}->plural).'</h2>
        <table>
          <thead>
            <tr>
              <th>'.ucfirst($class->name).'</th>
      ';
      $thead .= ($class->name == 'city') ? '<th>Region</th>': '';
      $thead .= ($class->name == 'region' || $class->name == 'city') ? '<th>Country</th>': '';
      $tbody = '
          <tbody>
      ';
      $objs = getObjs($dbh, $class->name, 1);
      $theadDone = false;
      foreach($objs as $obj) {
        $tbody .= '<tr><td>'.$obj->getLink().'</td>';
        if ($class->name == 'city') {
          $region = getRegionById($dbh, $obj->region_id);
          $tbody .= ($region) ? '<td>'.$region->getLink().'</td>' : '<td></td>';
          $regionSumTd = '<td></td>';
        }
        if ($class->name == 'region' || $class->name == 'city') {
          $country = getCountryById($dbh, $obj->country_id);
          $tbody .= ($country) ? '<td>'.$country->getLink().'</td>' : '<td></td>';
          $countrySumTd = '<td></td>';
        }
        $stats = $obj->getStats($dbh);
        foreach($classes->{$class->name}->children as $child) {
          $tbody .= '<td>'.$stats[$child].'</td>';
          $thead .= (!$theadDone) ? '<th class="numeric">'.ucfirst($classes->{$child}->plural).'</th>' : '';
          $total[$child] += $stats[$child];
          $num[$child] += ($stats[$child]) ? 1 : 0;
        }
        if ($class->name == 'manufacturer') {
          $tbody .= '<td>'.$stats['main'].'</td><td>'.$stats['classics'].'</td>';
          $total['main'] += $stats['main'];
          $total['classics'] += $stats['classics'];
          $num['main'] += ($stats['main']) ? 1 : 0;
          $num['classics'] += ($stats['classics']) ? 1 : 0;
        }
        $theadDone = true;
        $tbody .= '</tr>';
      }
      $thead .= ($class->name == 'manufacturer') ? '<th class="numeric">Main</th><th class="numeric">Classics</th>' : '';
      $tbody .= '<tr><td class="bold">&nbsp;Sum (Avg)</td>'.$regionSumTd.$countrySumTd;
      foreach($classes->{$class->name}->children as $child) {
        $tbody .= '<td class="bold">'.$total[$child].' ('.round(divide($total[$child], $num[$child])).')</td>';
      }
      if ($class->name == 'manufacturer') {
        $tbody .= '<td class="bold">'.$total['main'].' ('.round(divide($total['main'], $num['main'])).')</td>';
        $tbody .= '<td class="bold">'.$total['classics'].' ('.round(divide($total['classics'], $num['classics'])).')</td>';
      }
      $content .= $thead.'
            </tr>
          </thead>
          '.$tbody.'
          </tbody>
        </table>
      ';
    }
  }  

  $content .= "
    <script type=\"text/javascript\">
      $(document).ready(function() { 
        $('table').dataTable({
          'bProcessing': true, 
          'bDestroy': true, 
          'bJQueryUI': true,
          'sPaginationType': 'full_numbers', 
          'iDisplayLength': 200, 
          'aoColumnDefs':[{'sType': 'numeric', 'aTargets': ['numeric']}],
          'aLengthMenu': [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]
        });
      });
    </script>
  ";

  echo($content);
  printFooter($dbh, $ulogin);

?>