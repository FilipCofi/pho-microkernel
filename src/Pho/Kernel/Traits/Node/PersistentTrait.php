<?php

namespace Pho\Kernel\Traits\Node;

use Pho\Kernel\Kernel;
use Pho\Kernel\Hooks;
use Pho\Kernel\Acl;
use Pho\Framework;
use Pho\Lib\Graph\ID;
use Pho\Lib\Graph;
use Pho\Kernel\Standards;
use Pho\Kernel\Foundation;
use Pho\Kernel\Foundation\AttributeBag;

/**
 * Persistent Trait
 * 
 * Persistent nodes are not volatile, which means, they are stored
 * in the database, and they don't go away when the kernel 
 * is halted for any reason --voluntary or by accident--.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
trait PersistentTrait {

    protected function persistable(): bool
    {
        return (static::T_PERSISTENT && $this->kernel->live());
    }

    public function persist(): void
    {
        if(!$this->persistable())
            return;
        $this->kernel()->gs()->touch($this);
    }

    public function serialize(): string
    {
        if(!$this->persistable()) {
            return parent::serialize();
       }
        $this->kernel->logger()->info("About to serialize the node  %s, a %s", $this->id(), $this->label());
        $_ = serialize($this->toArray());
        $this->kernel->logger()->info("The node serialized as: %s", $_);
        return $_;
    }

  public function unserialize(/* mixed */ $data): void
  {
      $this->kernel = $GLOBALS["kernel"];
      if(!$this->persistable()) {
            parent::unserialize($data);
            return;
       }
    $data = unserialize($data);
    $this->id = ID::fromString($data["id"]);
    $this->kernel->logger()->info("Unserialization begins for %s", $this->id());
    $this->kernel->logger()->info("The edge list is as follows: %s", print_r($data["edge_list"], true));
    $this->edge_list = new Graph\EdgeList($this, $data["edge_list"]);
    if((string) ID::root() == $data["context"]) {
        $space_class = $this->kernel->config()->default_objects->space;
        $this->context = new $space_class($this->kernel);
        $this->context_id = $data["context"];
    }
    else {
        $this->context_id = $data["context"];
    }
    
    $this->creator_id = $data["creator"];
    if(isset($data["current_context"])) { // Actor
        $this->current_context = $this->kernel->gs()->node($data["current_context"]);
    }
    if(isset($data["master"])) { // VirtualGraph
        $this->master = $data["master"];
    }
    if(isset($data["registered_edges"])&&isset($data["registered_edges"]["out"])&&isset($data["registered_edges"]["in"])) { // VirtualGraph
        $this->incoming_edges = $data["registered_edges"]["in"];
        $this->outgoing_edges = $data["registered_edges"]["out"];
    }
    if(isset($data["members"])) { // Frame
        $this->kernel->logger()->info(
            "Extracting members for the frame %s: %s",
            $this->id(),
            print_r($data["members"], true)
        );
        $this->rewire()->loadNodesFromIDArray($data["members"]);
    }
    if(isset($data["acl"])) {
        $this->acl = Acl\AclFactory::seed($this->kernel, $this, $data["acl"]["permissions"]);
    }

    if(isset($data["editors"])) {
        $this->editors = $this->kernel->gs()->node($data["editors"]);
    }
    if(isset($data["notifications"]) && $this instanceof Framework\Actor) {
        $notifications = array();
        foreach($data["notifications"] as $notification) {
            // let's recreate the objects
            $class = $notification["class"];
            if(!class_exists($class) || !preg_match("/^[a-z0-9_\\\\]+$/i", $class)) {
                continue;
            }
            $edge_id = (string) ID::fromString($notification["edge"]);
            $edge = $this->kernel->gs()->edge($edge_id);
            $notifications[] = new $class($edge); 
            Hooks::setup($notifications[(count($notifications)-1)]);
        }
        $this->notifications = new Framework\NotificationList($this, $notifications); // assuming it's an actor
    }
    $this->kernel->logger()->info("Attributes as follows: %s", print_r($data["attributes"], true));
    $this->attributes = new AttributeBag($this, $data["attributes"]);
    $this->initializeHandler();
    $this->rewire();
  }
     

   public function destroy(): void
   {
       if(!$this->persistable()) {
           parent::destroy();
            return;
       }
    $edges_in = $this->edges()->in();
    $edges_out = $this->edges()->out();
    foreach($edges_in as $edge) {
        $edge->destroy();
    }
    foreach($edges_out as $edge) {
        if($edge->predicate()->binding()) {
            $this->kernel->logger()->info("Deleting edge head node %s with label: %s", $edge->head()->id(), $edge->head()->label());
            $edge->head()->destroy();
        }
        $edge->destroy();
    }
    $this->kernel->logger()->info("Node %s with label: %s has been called for deletion", $this->id(), $this->label());
   $this->kernel->database()->del(sprintf("node:%s", $this->id()));
   parent::destroy();
   }


}