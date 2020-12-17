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

/* __string_template__e3c4877576de7a7e49bddebb6155e7fb2e11bb1431737895e702319f4e255b8b */
class __TwigTemplate_082d30f5ac21d900005d740ea16a82179474cdcea289952b25fbe59a89a05dd6 extends Template
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
        echo "Create a group";
    }

    public function getTemplateName()
    {
        return "__string_template__e3c4877576de7a7e49bddebb6155e7fb2e11bb1431737895e702319f4e255b8b";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__e3c4877576de7a7e49bddebb6155e7fb2e11bb1431737895e702319f4e255b8b", "");
    }
}
