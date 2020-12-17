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

/* __string_template__d0527e4b3d658351dae74be7b10c7531a7ac98493c6b257ab62774853bcc74b2 */
class __TwigTemplate_d9fe3e6e53d978afac9c63449f3a4f20100fa2f3a0cf100b4ea828e6f7440957 extends Template
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
        echo "Logout";
    }

    public function getTemplateName()
    {
        return "__string_template__d0527e4b3d658351dae74be7b10c7531a7ac98493c6b257ab62774853bcc74b2";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__d0527e4b3d658351dae74be7b10c7531a7ac98493c6b257ab62774853bcc74b2", "");
    }
}
