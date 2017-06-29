<?php

namespace PhoNetworksAutogenerated\StatusOut 
{

use Pho\Kernel\Bridge\EdgeHydratorTrait;
use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Framework;



/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class Mention extends Framework\ObjectOut\Mention {

    
    use EdgeHydratorTrait;
    

    const HEAD_LABEL = "mention";
    const HEAD_LABELS = "mentions";
    const TAIL_LABEL = "notification";
    const TAIL_LABELS = "notifications";
    
    const SETTABLES_EXTRA = [\PhoNetworksAutogenerated\User::class];

    public function __construct(NodeInterface $tail, NodeInterface $head, ?PredicateInterface $predicate = null) 
    {
        parent::__construct($tail, $head, $predicate);
        $this->kernel = $GLOBALS["kernel"];
        //$this->persist();
    }

}

/* Predicate to load as a partial */
class MentionPredicate extends Framework\ObjectOut\MentionPredicate
{
    protected $binding = false;
    const T_CONSUMER = true;
    const T_PERSISTENT = true;
}
/* Notification to load if it's a subtype of write or mention. */
class MentionNotification extends Framework\ObjectOut\MentionNotification
{

}
}

/*****************************************************
 * Timestamp: 
 * Size (in bytes): 1533
 * Compilation Time: 7289
 * 233b0d4523ceef10b58f95340dc8709f
 ******************************************************/