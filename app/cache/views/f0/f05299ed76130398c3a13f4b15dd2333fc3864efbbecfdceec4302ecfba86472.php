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

/* __string_template__289aff12b04274cb04b8f7dbf486ba8b3528c6fd16b60b9a31d31ce23b339236 */
class __TwigTemplate_e2d434923c2d5a120ebbf16a5da93c7af5f445f461be873aadf6bc4e6098494b extends Template
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
        echo "Question";
    }

    public function getTemplateName()
    {
        return "__string_template__289aff12b04274cb04b8f7dbf486ba8b3528c6fd16b60b9a31d31ce23b339236";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__289aff12b04274cb04b8f7dbf486ba8b3528c6fd16b60b9a31d31ce23b339236", "");
    }
}
