<?php

namespace Acme\PortalBundle\Tests\Helper\Mocker;

class PortalMockerData
{
  public function getStructuredClients()
  {
    return array(
      'clients' => array(
        'asv' => array(
          'pos' => 0,
          'name' => 'asv',
          'articles' => array(
            'stylebook' => array(
              'pos' => 0,
              'description' => 'stylebook',
              'tags' => array('beauty', 'html')
            ),
            'travelbook' => array(
              'pos' => 0,
              'description' => 'travelbook',
              'tags' => array('beauty', 'framework', 'symfony')
            )
          )
        ),
        'spiegel' => array(
          'pos' => 0,
          'name' => 'spiegel',
          'articles' => array(
            array(
              'pos' => 0,
              'description' => 'qc',
              'tags' => array('framework', 'symfony')
            )
          )
        )
      )
    );
  }
}