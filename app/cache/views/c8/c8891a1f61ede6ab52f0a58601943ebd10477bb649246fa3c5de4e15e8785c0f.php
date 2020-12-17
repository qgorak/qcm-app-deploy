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

/* /main/UI/AuthModal.html */
class __TwigTemplate_b7beb165c21ce7fcf7635c6e3f3dc66a68ed108c9950985a801da08a2d25e690 extends Template
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
        echo "<div id=\"authmodal\" class=\"ui modal\" style=\"padding:10px;\">
  <i class=\"close icon\"></i>
  <div id=\"responseauth\"></div>
</div>";
    }

    public function getTemplateName()
    {
        return "/main/UI/AuthModal.html";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "/main/UI/AuthModal.html", "/Users/qgorak/Programmation/Ubiquity-workspace/qcm-app/app/views/main/UI/AuthModal.html");
    }
}
