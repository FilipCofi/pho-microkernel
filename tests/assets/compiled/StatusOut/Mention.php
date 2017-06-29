<?php

namespace PhoNetworksAutogenerated\StatusOut;

use Pho\Kernel\Traits\HydratingEdgeTrait;
use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Framework;



/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class Mention extends Framework\ObjectOut\Mention {

    
    use HydratingEdgeTrait;
    

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
class MentionMention extends Framework\ObjectOut\MentionMention
{

}
/*****************************************************
 * Timestamp: 
 * Size (in bytes): 1521
 * Compilation Time: 46594
 * 2c6cc763d411469fcbdc62c39362d49c
 ******************************************************/