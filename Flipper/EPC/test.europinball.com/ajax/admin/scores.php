        $prefix = 'scores';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
        //${$prefix.'Div'}
        $prefix = 'results';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
        //${$prefix.'Div'}
        $prefix = 'volunteers';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          $volunteers = volunteers('active');
          $mailAddresses = $volunteers->getListOf('mailAddress');
          if ($mailAddresses) {
            ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
            ${$prefix.'Div'}->addParagraph('Email addresses to all volunteers that have registered their email address. Click in the box to copy the addresses to your clipboard.');
            ${$prefix.'Div'}->addParagraph(implode(', ', $mailAddresses), $prefix.'mailAddresses', 'toCopy');
            ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
          }
        //${$prefix.'Div'}
