<?php

namespace Pho\Kernel\Foundation;

use Pho\Lib\Graph as LibGraph;
use Pho\Kernel\Kernel;
use Stringy\Stringy as S;

class AttributeBag extends LibGraph\AttributeBag
{

    /**
     * {@inheritDoc}
     */
    public function __construct(LibGraph\EntityInterface $owner, array $bag = []) 
    {
        parent::__construct($owner, $bag);
        $this->hydrate();
    }

    /**
     * Hydrates the imported values.
     *
     * @return void
     */
    private function hydrate(): void 
    {
        $this->owner->kernel()->logger()->info(
            "Hydration of AttributeBag with data:",
            print_r($this->bag, true)
        );
        foreach($this->bag as $key=>$value) {
            if(!is_string($value))
                continue;
            $s = S::create($value);
            if(
                $s->startsWith(Kernel::PARTICLE_IN_ATTRIBUTEBAG_TPL[0])
                &&
                $s->endsWith(Kernel::PARTICLE_IN_ATTRIBUTEBAG_TPL[1])
            ) {
                $id = (string) $s
                        ->removeLeft(Kernel::PARTICLE_IN_ATTRIBUTEBAG_TPL[0])
                        ->removeRight(Kernel::PARTICLE_IN_ATTRIBUTEBAG_TPL[1]);
                $this->bag[$key] = $this->owner->kernel()->gs()->node($id);
            }
        }
    }
}