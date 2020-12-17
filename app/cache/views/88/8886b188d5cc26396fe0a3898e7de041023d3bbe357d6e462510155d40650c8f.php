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

/* __string_template__02044af3237c651d22884eb0b319d4a99a661d9303328e76ea19ed7ea1c59665 */
class __TwigTemplate_89e8eb3ddd1a54c124bf493736438cef45bb0529517264e7b9c28a4f3999e578 extends Template
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
        echo "Exam";
    }

    public function getTemplateName()
    {
        return "__string_template__02044af3237c651d22884eb0b319d4a99a661d9303328e76ea19ed7ea1c59665";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__02044af3237c651d22884eb0b319d4a99a661d9303328e76ea19ed7ea1c59665", "");
    }
}
