<?php

namespace PhoNetworksAutogenerated;

use Pho\Framework;
use Pho\Kernel\Kernel;
use Pho\Kernel\Traits;
use Pho\Kernel\Foundation;




/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class Status extends Foundation\AbstractObject {

    const T_EDITABLE = false;
    const T_PERSISTENT = true;
    const T_EXPIRATION =  0;
    const T_VERSIONABLE = false;
    
    const DEFAULT_MOD = 0x0e444;
    const DEFAULT_MASK = 0xeeeee;

    const FIELDS = "{\"status\":{\"constraints\":{\"minLength\":null,\"maxLength\":\"140\",\"uuid\":null,\"regex\":null,\"greaterThan\":null,\"lessThan\":null},\"directives\":{\"md5\":false,\"now\":false,\"default\":\"|_~_~NO!-!VALUE!-!SET~_~_|\"}},\"create_time\":{\"constraints\":{\"minLength\":null,\"maxLength\":null,\"uuid\":null,\"regex\":null,\"greaterThan\":null,\"lessThan\":null},\"directives\":{\"md5\":false,\"now\":true,\"default\":\"|_~_~NO!-!VALUE!-!SET~_~_|\"}}}";

    public function __construct(\Pho\Kernel\Kernel $kernel, \Pho\Kernel\Foundation\AbstractActor $actor, \Pho\Lib\Graph\GraphInterface $graph , string $status)
    {
        $this->registerIncomingEdges(UserOut\Post::class);
        $this->registerIncomingEdges(UserOut\Like::class);
        parent::__construct($kernel, $actor, $graph);
                $this->setStatus($status);
        $this->setCreateTime(time());

    }

}

/*****************************************************
 * Timestamp: 1500415734
 * Size (in bytes): 1732
 * Compilation Time: 15703
 * eb016f68f4e2e09f0570e451961fab07
 ******************************************************/