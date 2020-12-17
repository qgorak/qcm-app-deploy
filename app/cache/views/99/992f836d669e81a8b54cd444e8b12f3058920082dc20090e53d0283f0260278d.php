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

/* __string_template__3a78695388b38b5cceefaf6796b0137877514593543b91af2752d5a17e3d736c */
class __TwigTemplate_17f3aafc57f9c44b54a30e26e26735cfca501946afdeeddceb580b780306502a extends Template
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
        echo "Home";
    }

    public function getTemplateName()
    {
        return "__string_template__3a78695388b38b5cceefaf6796b0137877514593543b91af2752d5a17e3d736c";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__3a78695388b38b5cceefaf6796b0137877514593543b91af2752d5a17e3d736c", "");
    }
}
