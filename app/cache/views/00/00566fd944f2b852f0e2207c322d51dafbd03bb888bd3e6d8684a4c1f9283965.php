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

/* @activeTheme/main/vHeader.html */
class __TwigTemplate_5918173b5dea7d4485da95a4f63985aeed4c7dfe1ca159d2e08017dd8d708051 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'header' => [$this, 'block_header'],
            'css' => [$this, 'block_css'],
            'head' => [$this, 'block_head'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
";
        // line 4
        $this->displayBlock('header', $context, $blocks);
        // line 13
        $this->displayBlock('css', $context, $blocks);
        // line 18
        echo "</head>
";
        // line 19
        $this->displayBlock('head', $context, $blocks);
    }

    // line 4
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 5
        echo "\t<base href=\"";
        echo twig_escape_filter($this->env, (($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["config"] ?? null)) && is_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4) || $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 instanceof ArrayAccess ? ($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4["siteUrl"] ?? null) : null), "html", null, true);
        echo "\">
\t<meta charset=\"UTF-8\">
\t<link rel=\"icon\" href=\"data:;base64,iVBORw0KGgo=\">
\t<title>QCM</title>
\t";
        // line 9
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["/js/timer.js"]);
        echo "
\t";
        // line 10
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/highlight.min.js"]);
        echo "
\t<script>hljs.initHighlightingOnLoad();</script>
";
    }

    // line 13
    public function block_css($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 14
        echo "\t";
        echo call_user_func_array($this->env->getFunction('css')->getCallable(), ["css/androidstudio.min.css"]);
        echo "
\t";
        // line 15
        echo call_user_func_array($this->env->getFunction('css')->getCallable(), ["https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.6/dist/semantic.min.css"]);
        echo "
\t";
        // line 16
        echo call_user_func_array($this->env->getFunction('css')->getCallable(), ["css/style.css"]);
        echo "
";
    }

    // line 19
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 20
        echo "<body>
";
    }

    public function getTemplateName()
    {
        return "@activeTheme/main/vHeader.html";
    }

    public function getDebugInfo()
    {
        return array (  102 => 20,  98 => 19,  92 => 16,  88 => 15,  83 => 14,  79 => 13,  72 => 10,  68 => 9,  60 => 5,  56 => 4,  52 => 19,  49 => 18,  47 => 13,  45 => 4,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@activeTheme/main/vHeader.html", "/Users/qgorak/Programmation/Ubiquity-workspace/qcm-app/app/views/main/vHeader.html");
    }
}
