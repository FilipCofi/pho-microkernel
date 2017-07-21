<?php

namespace PhoNetworksAutogenerated\UserOut 
{

 use Pho\Kernel\Traits\Edge\PersistentTrait; 
use Pho\Lib\Graph\NodeInterface;
use Pho\Lib\Graph\PredicateInterface;
use Pho\Framework;



/*****************************************************
 * This file was auto-generated by pho-compiler
 * For more information, visit http://phonetworks.org
 ******************************************************/

class Like extends Framework\ActorOut\Subscribe {

    
    use PersistentTrait;
    

    const HEAD_LABEL = "like";
    const HEAD_LABELS = "likes";
    const TAIL_LABEL = "liker";
    const TAIL_LABELS = "likers";
    

    const SETTABLES_EXTRA = [\PhoNetworksAutogenerated\Status::class];
    

    public function __construct(NodeInterface $tail, NodeInterface $head, ?PredicateInterface $predicate = null) 
    {
        parent::__construct($tail, $head, $predicate);
        $this->kernel = $GLOBALS["kernel"];
        //$this->persist();
    }

}

/* Predicate to load as a partial */
class LikePredicate extends Framework\ActorOut\SubscribePredicate
{
    protected $binding = false;
    
    const T_CONSUMER = true;
    const T_NOTIFIER = false;
    const T_SUBSCRIBER = false;
    const T_FORMATIVE = false;
    const T_PERSISTENT = true;
}
/* Notification to load if it's a subtype of write or mention. */

}

/*****************************************************
 * Timestamp: 
 * Size (in bytes): 1536
 * Compilation Time: 15701
 * 92d07497dec62a4f74816953f347c934
 ******************************************************/