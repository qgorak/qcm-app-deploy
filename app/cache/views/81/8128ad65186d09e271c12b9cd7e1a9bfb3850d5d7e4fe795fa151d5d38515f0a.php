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

/* __string_template__a843de4d30417725b8494e53efe395ba7a5d7548394246618dc6aa4ce5b0dabe */
class __TwigTemplate_521edec315b2a1d526ff35c3bb2849a6edd28fa5e36d804622e1394e95a2513c extends Template
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
        echo "My groups";
    }

    public function getTemplateName()
    {
        return "__string_template__a843de4d30417725b8494e53efe395ba7a5d7548394246618dc6aa4ce5b0dabe";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__a843de4d30417725b8494e53efe395ba7a5d7548394246618dc6aa4ce5b0dabe", "");
    }
}
