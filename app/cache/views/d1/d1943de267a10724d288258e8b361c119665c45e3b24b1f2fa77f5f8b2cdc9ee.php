<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__ec8292c3df95a4afeb76c4da9a1148227c5fc723ad8e2e8984c3eaae33d3a747 */
class __TwigTemplate_eb494f3c41ea33087f7a0feac28a48e27b9b6532ffaf848d7c56158a2cc5723b extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "Groups I'm in";
    }

    public function getTemplateName()
    {
        return "__string_template__ec8292c3df95a4afeb76c4da9a1148227c5fc723ad8e2e8984c3eaae33d3a747";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__ec8292c3df95a4afeb76c4da9a1148227c5fc723ad8e2e8984c3eaae33d3a747", "");
    }
}
