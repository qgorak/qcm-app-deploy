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

/* __string_template__64e1e71ccca2b44c204cbb70cb55c8edf952650578903d19db5e5588e1ce2d28 */
class __TwigTemplate_4971a1ab0a5bf3e4c760027c51b8e9b9e983e245fdb3064efcc76aee5f15672b extends Template
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
        echo "See groups";
    }

    public function getTemplateName()
    {
        return "__string_template__64e1e71ccca2b44c204cbb70cb55c8edf952650578903d19db5e5588e1ce2d28";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__64e1e71ccca2b44c204cbb70cb55c8edf952650578903d19db5e5588e1ce2d28", "");
    }
}
