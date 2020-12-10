<?php
/*
Title: Organization details
Post Type: organization
Context: side
*/
piklist( 'field', array(
    'type'    => 'file',
    'field'   => 'big_logo',
    'scope'   => 'post_meta',
    'label'   => 'Organization big logo',
    'options' => array(
        'modal_title' => 'Add File(s)',
        'button'      => 'Add'
    )
) );
piklist( 'field', array(
    'type'    => 'file',
    'field'   => 'small_logo',
    'scope'   => 'post_meta',
    'label'   => 'Organization small logo',
    'options' => array(
        'modal_title' => 'Add File(s)',
        'button'      => 'Add'
    )
) );
piklist( 'field', array(
    'type'    => 'url',
    'field'   => 'organization_site',
    'scope'   => 'post_meta',
    'label'   => 'Organization web site',
) );
