<?php

namespace Pho\Kernel\Foundation\ActorOut;

use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Kernel\Foundation\Exceptions\ExecutePermissionException;

class Subscribe extends \Pho\Framework\ActorOut\Subscribe
{
    //use \Pho\Kernel\Traits\Edge\PersistentTrait;

    public function __construct(NodeInterface $tail, ?NodeInterface $head = null, ?PredicateInterface $predicate = null) 
    {
        if(!is_null($head)&&!$head->acl()->executable($tail)) {
            throw new ExecutePermissionException($head, $tail);
        }
        parent::__construct($tail, $head, $predicate);
        $this->kernel = $GLOBALS["kernel"];
    }
}