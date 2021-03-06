<?php

namespace Pho\Kernel;

use Pho\Kernel\Kernel;
use Pho\Framework;
use Pho\Lib\Graph;
use Pho\Lib\Graph\EntityInterface;
use Pho\Kernel\Foundation;

/**
 * Graphsystem
 * 
 * Gs is short for graphsystem; Pho's equivalent of UNIX' filesystem.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Graphsystem 
{
    private $database;
    private $logger;

    public function __construct(Kernel $kernel) {
        $this->database = $kernel->database();
        $this->logger = $kernel->logger();
    }

  /**
   * Retrieves a node
   *
   * @param string $node_id
   * 
   * @return Pho\Lib\Graph\NodeInterface The node object.
   * 
   * @throws Pho\Kernel\Exceptions\NodeDoesNotExistException When there is no entity with the given id.
   * @throws Pho\Kernel\Exceptions\NotANodeException When the given id does not belong to a node.
   */
  public function node(string $node_id): Graph\NodeInterface
  {
    $query = (string) $node_id; // sprintf("node:%s", (string) $node_id);
    $node = $this->database->get($query);
    if(is_null($node)) {
      throw new Exceptions\NodeDoesNotExistException($node_id);
    }
    $node = unserialize($node);
    if(!$node instanceof Framework\ParticleInterface && !$node instanceof Foundation\World) {
      throw new Exceptions\NotANodeException($node_id);
    }
    if($node instanceof Foundation\AbstractActor) {
        $node->registerHandler(
            "form",
            \Pho\Kernel\Foundation\Handlers\Form::class
        );
    }
    return $node;
  }

  /**
   * Retrieves an edge
   * 
   * Reconstructs a single edge object based on its ID.
   *
   * @param string $node_id
   * 
   * @return Pho\Lib\Graph\EdgeInterface The edge in its object form.
   * 
   * @throws Pho\Kernel\Exceptions\EdgeDoesNotExistException when the given id does not exist in the database.
   * @throws Pho\Kernel\Exceptions\NotAnEdgeException when the given id does not belong to an edge.
   */
  public function edge(string $edge_id): Graph\EdgeInterface
  {
    $query = (string) $edge_id; // sprintf("edge:%s", (string) $edge_id);
    $edge = $this->database->get($query);
    if(is_null($edge)) {
      throw new Exceptions\EdgeDoesNotExistException($edge_id);
    }
    $edge = unserialize($edge);
    if(!$edge instanceof Graph\EdgeInterface) {
      throw new Exceptions\NotAnEdgeException($edge_id);
    }
    return $edge;
  }

  public function entity(string $entity_id): EntityInterface
  {
    $node = null;
    try {
      $node = $this->node($entity_id);
    }
    catch(\Exception $node_exception) {}
    finally {
      if(!is_null($node))
        return $node;
      try {
        $edge = $this->edge($entity_id);
      }
      catch(\Exception $edge_exception) {
        throw new Exceptions\EntityDoesNotExistException($entity_id);
      }
      return $edge;
    }
  }

  /**
   * Creates the entity in the graphsystem 
   *
   * @param Graph\EntityInterface $entity
   * 
   * @return void
   */
  public function touch(EntityInterface $entity): void
  { 
    $this->database->set(
        (string) $entity->id(), serialize($entity)
    );
  }

  public function delEdge(Graph\ID $id): void
  {
      $this->database->del($id);
  }

  public function delNode(Graph\ID $id): void
  {
      $this->database->del($id);
  }

  public function expire(Graph\ID $id, int $timeout = (60*60*24)): void
  {
    $this->database->expire((string) $id, $timeout);
  }

}