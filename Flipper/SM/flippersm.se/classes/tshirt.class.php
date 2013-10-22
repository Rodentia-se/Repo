<?php
  
  class tshirt extends base {
    
    public $player;
    public $player_id;
    public $playerTshirt_id;
    public $color;
    public $number;
    public $number_id;
    public $total;
    public $buyers;
    public $reserved;
    public $delivered;
    public $color_id;
    public $size;
    public $size_id;
    public $tournamentTshirt_id;
    public $dateRegistered;
    public $dateDelivered;
    
    function addOrder($dbh, $player) {
      $query = '
        insert into personTShirt set 
          tournamentTShirt_id = :tShirtId,
          number = :number,
          person_id = :playerId,
          firstName = :firstName,
          lastName = :lastName,
          initials = :initials,
          streetAddress = :streetAddress,
          zipCode = :zipCode,
          telephoneNumber = :telephoneNumber,
          mobileNumber = :mobileNumber,
          mailAddress = :mailAddress,
          dateRegistered = :dateRegistered
      ';
      $insert[':tShirtId'] = $this->tournamentTshirt_id;
      $insert[':number'] = $this->number;
      $insert[':playerId'] = $player->id;
      $insert[':firstName'] = $player->firstName;
      $insert[':lastName'] = $player->lastName;
      $insert[':initials'] = $player->initials;
      $insert[':streetAddress'] = $player->streetAddress;
      $insert[':zipCode'] = $player->zipCode;
      $insert[':telephoneNumber'] = $player->telephoneNumber;
      $insert[':mobileNumber'] = $player->mobileNumber;
      $insert[':mailAddress'] = $player->mailAddress;
      $insert[':dateRegistered'] = date('Y-m-d');
      $sth = $dbh->prepare($query);
      if ($sth->execute($insert)) {
        return $dbh->lastInsertId();
      } else {
        return false;
      }
    }
    
    function inStock($dbh) {
      $tShirt = getNoOfTshirts($dbh, $this->tournamentTshirt_id);
      var_dump($tShirt);
      return ($tShirt->total - $tShirt->reserved - $tShirt->soldOnSite);
    }

    function updateOrder($dbh) {
      $query = '
        update personTShirt ps
        left join tshirt ts
          on ts.id = :tournamentTShirt_id
        set
          ps.tournamentTShirt_id = :tournamentTShirt_id,
          ps.number = :number,
          ps.name = concat(:tournamentEdition, ", ", ifnull(ps.initials, concat("ID:", ps.person_id)), ": ", ts.name),
          ps.dateRegistered = :dateRegistered
        where ps.id = :id
      ';
      $update[':tournamentTShirt_id'] = $this->tournamentTshirt_id;
      $update[':number'] = $this->number;
      $update[':tournamentEdition'] = $this->tournamentEdition;
      $update[':dateRegistered'] = date('Y-m-d');
      $update[':id'] = $this->playerTshirt_id;
      $sth = $dbh->prepare($query);
      if ($sth->execute($update)) {
        return $sth->rowCount();
      }
      return false;
    }
    
    function deleteOrder($dbh) {
      $delete[':id'] = $this->playerTshirt_id;
      $query = 'delete from personTShirt where id = :id';
      $sth = $dbh->prepare($query);
      if ($sth->execute($delete)) {
        return true;
      } else {
        return false;
      }
    }
    
    function setDelivered($dbh, $dlvr = true) {
      $dateDelivered = ($dlvr) ? date('Y-m-d') : null;
      $query = ' 
        update personTShirt ps set
          ps.dateDelivered = :dateDelivered
        where ps.id = :id
      ';
      $update[':dateDelivered'] = $dateDelivered;
      $update[':id'] = $this->playerTshirt_id;
      $sth = $dbh->prepare($query);
      if ($sth->execute($update)) {
        return $sth->rowCount();
      }
      return false;
    }
    
    function setSold($dbh, $action = false, $number = false) {
      if ($action || $number) {
        $query = 'update tournamentTShirt set soldOnSite = ';
        $query .= ($action) ? 'ifnull(soldOnSite, 0) + '.$action : $number;
        $query .= ' where id = :id';
        $update[':id'] = $this->tournamentTshirt_id;
        $sth = $dbh->prepare($query);
        if ($sth->execute($update)) {
          return $sth->rowCount();
        }
      return false;
      }
    }
    
    function getRow($dbh, $tournament = 1, $warning = true, $format = 'table') {
      $tshirts = getTshirts($dbh, $tournament);
      $content = '<tr id="'.$this->playerTshirt_id.'_tshirtTr">';
      $json['trId'] = $this->playerTshirt_id.'_tshirtTr';
      $options['size'] = array('0' => 'Choose...');
      $options['color'] = array('0' => 'Choose...');
      array_merge($options['size'], getTshirtSizes($dbh, $tournament));
      array_merge($options['color'], getTshirtColors($dbh, $tournament));
      $options['number'] = array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10);
      foreach(array('number', 'color', 'size') as $param) {
        $json[$param]['label'] = '<label>'.ucfirst($param).':</label>';
        $content .= '<td class="labelTd">'.$json[$param]['label'].'</td>';
        $json[$param]['select'] = '<select id="'.$this->playerTshirt_id.'_tshirt'.ucfirst($param).'Select" class="select '.$param.'" onchange="tshirtChanged(this);"'.((__tshirtsDisabled__) ? ' disabled' : '').'>';
        foreach($options[$param] as $option_id => $option) {
          $json[$param]['select'] .= '<option value="'.$option_id.'"';
          if ($this->{$param.'_id'} == $option_id) {
            $json[$param]['select'] .= ' selected ';
          }
          $json[$param]['select'] .= '>'.$option."</option>\n";
        }
        $json[$param]['select'] .= '</select>';
        $content .= '<td class="selectTd">'.$json[$param]['select'].'</td>';
      }
      $json[$param]['img'] = '<img id="'.$this->playerTshirt_id.'_tshirtDel" src="'.__baseHref__.'/images/cancel.png" class="icon" onclick="delTshirt('.$this->playerTshirt_id.');" alt="Click to delete this T-shirt" title="Click to delete this T-shirt"/><span class="error errorSpan" id="'.$this->playerTshirt_id.'_tshirtSpan">';
      if ($warning && (!($this->number_id > 0) || !($this->color_id > 0) || !($this->size_id > 0))) {
        $json[$param]['img'] .= 'You have not chosen all options for these T-shirts!';
      }
      $json[$param]['img'] .= '</span>';
      $content .= '<td>'.$json[$param]['img'].'</td></tr>';
      $json['success'] = true;
      switch ($format) {
        case 'table':
          return $content;
        break;
        case 'json';
          return $json;
        break;
        default:
          return $content;
        break;
      }
    }
        
  }
?>
