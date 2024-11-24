<?php return array(
    'root' => array(
        'name' => 'cb-master/cbm-session',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'bb91877c6e66725f7d21e98b6b5517cd6f432e2f',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'cb-master/cb-model' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'd0ed5507a3ecc10b07ce76a3bd92223608e19640',
            'type' => 'package',
            'install_path' => __DIR__ . '/../cb-master/cb-model',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'cb-master/cbm-session' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'bb91877c6e66725f7d21e98b6b5517cd6f432e2f',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
