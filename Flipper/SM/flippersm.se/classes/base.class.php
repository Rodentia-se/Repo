<?php
  abstract class base {
    
    public $id;
    public $name;
    public $shortName;
    public $fullName;
    public $acronym;
    public $comment;
    public $newPhoto;
    public $tournamentEdition;
    public $tournamentEdition_id;
    public $tournamentDivision;
    public $tournamentDivision_id;
    public $class = 'base';

    public function __construct($data = null, $type = 'array') {
      switch ($type) {
        case 'json':
          if ($data) {
            $this->set(json_decode($json, true));
          }
        break;
        case 'array':
          if ($data) {
            $this->set($data);
          }
        break;
      }
    }

    public function set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }
    
    public function getInfo($dbh, $ulogin) {
      return getInfo($dbh, $ulogin, $this->class, $this->id);
    }
    
    public function getPhoto($thumbnail = false) {
      if ($thumbnail) {
        foreach (getPhotoExts() as $ext) {
          if (file_exists(__ROOT__.'/images/objects/'.$this->class.'/'.$this->id.'.thumb.'.$ext)) {
            return __baseHref__.'/images/objects/'.$this->class.'/'.$this->id.'.thumb.'.$ext;
          }
        }
      } 
      foreach (getPhotoExts() as $ext) {
        if (file_exists(__ROOT__.'/images/objects/'.$this->class.'/'.$this->id.'.'.$ext)) {
          return __baseHref__.'/images/objects/'.$this->class.'/'.$this->id.'.'.$ext;
        }
      }
      if ($this->class == 'player') {
        foreach (getPhotoExts() as $ext) {
          if (file_exists(__ROOT__.'/images/objects/'.$this->class.'/ifpa/'.$this->ifpa_id.'.'.$ext)) {
            return __baseHref__.'/images/objects/'.$this->class.'/ifpa/'.$this->ifpa_id.'.'.$ext;
          }
        }
      }
      if ($thumbnail) {
        foreach (getPhotoExts() as $ext) {
          if (file_exists(__ROOT__.'/images/objects/'.$this->class.'/0.thumb.'.$ext)) {
            return __baseHref__.'/images/objects/'.$this->class.'/0.thumb.'.$ext;
          }
        }
      }
      foreach (getPhotoExts() as $ext) {
        if (file_exists(__ROOT__.'/images/objects/'.$this->class.'/0.'.$ext)) {
          return __baseHref__.'/images/objects/'.$this->class.'/0.'.$ext;
        }
      }
      foreach (getPhotoExts() as $ext) {
        if (file_exists(__ROOT__.'/images/objects/0.'.$ext)) {
          return __baseHref__.'/images/objects/0.'.$ext;
        }
      }
      return false;
    }
    
    public function setPhoto($newPhoto = false) {
      $newPhoto = ($newPhoto) ? $newPhoto : $this->newPhoto;
      if ($newPhoto && $newPhoto != 'false') {
        if ($this->id && $this->id != 0) {
          foreach (getPhotoExts() as $ext) {
            if (file_exists(__ROOT__.'/images/objects/'.$this->class.'/'.$this->id.'.'.$ext)) {
              unlink(__ROOT__.'/images/objects/'.$this->class.'/'.$this->id.'.'.$ext);
            }
          }
          if (rename(__ROOT__.'/images/objects/'.$newPhoto, __ROOT__.'/images/objects/'.preg_replace('/\/preview\//', '/', $newPhoto))) {
            return true;
          }
        }
        return false;
      } else {
        return false;
      }
    }
    
    public function getLink($href = true) {
      switch ($this->class) {
        case 'qualGroup':
          $url = __baseHref__.'/?s=kvalgrupper&id='.$this->id;
          return ($href) ? '<a href="'.$url.'">'.ucfirst($this->name).'</a>' : $url;
        break;
        default:
          $url = __baseHref__.'/?s=object&obj='.$this->class.'&id='.$this->id;
          return ($href) ? '<a href="'.$url.'">'.$this->name.'</a>' : $url;
        break;
      }
    }

    public function populate($dbh) {
    }
  }
?>