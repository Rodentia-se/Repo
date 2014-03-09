        $prefix = 'groups';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
        //${$prefix.'Div'}
