<?php

namespace Alex\MultisiteBundle;

use Alex\MultisiteBundle\DependencyInjection\Compiler\InjectSiteContextPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AlexMultisiteBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new InjectSiteContextPass());
    }
}
