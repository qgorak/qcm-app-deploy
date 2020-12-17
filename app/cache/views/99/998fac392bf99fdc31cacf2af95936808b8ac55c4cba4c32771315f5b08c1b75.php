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

/* GroupController/index.html */
class __TwigTemplate_e12a2e92f6f6bbabe6144f4d12e2b3fb6e9d325b64ad74b4b924aaf6fa307216 extends Template
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
        echo "<div class=\"ui container\">
\t<a  id=\"addGroup\" class=\"ui green button\">
\t";
        // line 3
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "addSubmit", [], "main"]), "html", null, true);
        echo "
\t</a>
\t<a id=\"joinGroup\" class=\"ui green button\">
\t";
        // line 6
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "joinSubmit", [], "main"]), "html", null, true);
        echo "
\t</a>
\t<a href=\"";
        // line 8
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["group"]), "html", null, true);
        echo "\" class=\"ui primary button\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "seeGroups", [], "main"]), "html", null, true);
        echo "</a>
\t<div id=\"response\">
\t<div class=\"ui styled accordion\">
\t  <div class=\"title\">
\t    <i class=\"dropdown icon\"></i>
\t    ";
        // line 13
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "myGroups", [], "main"]), "html", null, true);
        echo "
\t  </div>
\t  <div class=\"content\">
\t    ";
        // line 16
        echo (($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["q"] ?? null)) && is_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4) || $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 instanceof ArrayAccess ? ($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4["myGroups"] ?? null) : null);
        echo "
\t  </div>
\t  <div class=\"title\">
\t    <i class=\"dropdown icon\"></i>
\t    ";
        // line 20
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "inGroups", [], "main"]), "html", null, true);
        echo "
\t  </div>
\t  <div class=\"content\">
\t    ";
        // line 23
        echo (($__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144 = ($context["q"] ?? null)) && is_array($__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144) || $__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144 instanceof ArrayAccess ? ($__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144["inGroups"] ?? null) : null);
        echo "
\t  </div>
\t</div>
\t</div>
\t<div id=\"addModal\" class=\"ui fullscreen modal\" style=\"padding:10px;\">
\t<div class=\"header\">";
        // line 28
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "addSubmit", [], "main"]), "html", null, true);
        echo "</div>
\t  <i class=\"close icon\"></i>
\t  <div class=\"content\">
\t  \t";
        // line 31
        echo (($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b = ($context["q"] ?? null)) && is_array($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b) || $__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b instanceof ArrayAccess ? ($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b["addForm"] ?? null) : null);
        echo "
\t  </div>
\t</div>
\t<div id=\"joinModal\" class=\"ui fullscreen modal\" style=\"padding:10px;\">
\t<div class=\"header\">";
        // line 35
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "joinSubmit", [], "main"]), "html", null, true);
        echo "</div>
\t  <i class=\"close icon\"></i>
\t  <div class=\"content\">
\t  \t";
        // line 38
        echo (($__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002 = ($context["q"] ?? null)) && is_array($__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002) || $__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002 instanceof ArrayAccess ? ($__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002["joinForm"] ?? null) : null);
        echo "
\t  </div>
\t</div>
</div>
";
        // line 42
        echo ($context["script_foot"] ?? null);
    }

    public function getTemplateName()
    {
        return "GroupController/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  115 => 42,  108 => 38,  102 => 35,  95 => 31,  89 => 28,  81 => 23,  75 => 20,  68 => 16,  62 => 13,  52 => 8,  47 => 6,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "GroupController/index.html", "/Users/qgorak/Programmation/Ubiquity-workspace/qcm-app/app/views/GroupController/index.html");
    }
}
