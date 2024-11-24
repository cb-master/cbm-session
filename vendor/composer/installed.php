<?php return array(
    'root' => array(
        'name' => 'cb-master/cbm-session',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'a0ea4e7bbbe559970f4769fe82dccef347c2347c',
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
            'reference' => 'a0ea4e7bbbe559970f4769fe82dccef347c2347c',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
