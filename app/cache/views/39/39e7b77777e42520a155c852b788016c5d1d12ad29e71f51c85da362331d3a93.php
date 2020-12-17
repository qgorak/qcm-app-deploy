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

/* __string_template__2cd8f1a205ca1359cc93352f75dcf525afb5f8efc432a4e2cc05769dd9072257 */
class __TwigTemplate_655b5a1583de1407f4b4bd9ff88b2b037cb6bb61a77b6deecc53ef60cfd409f8 extends Template
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
        echo "Join a group";
    }

    public function getTemplateName()
    {
        return "__string_template__2cd8f1a205ca1359cc93352f75dcf525afb5f8efc432a4e2cc05769dd9072257";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__2cd8f1a205ca1359cc93352f75dcf525afb5f8efc432a4e2cc05769dd9072257", "");
    }
}
