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

/* /main/UI/userNavbar.html */
class __TwigTemplate_85ce31456f3228caeaeb6d0c2562a51ed5885c09b083910dd1b20e8c4e43a066 extends Template
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
        echo "<div id=\"primary-menu\" class=\"ui inverted menu\">
\t<a class=\"item\" id=\"show-menu\"><i class=\"cog icon\"></i></a>
\t<a class=\"item\" href=\"";
        // line 3
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["_default"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "home", [], "main"]), "html", null, true);
        echo " </a>
\t<div class=\"right menu\">
\t\t<a class=\"item\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["notification"]), "html", null, true);
        echo "\"><i class=\"icons\">
\t\t\t<i class=\"bell icon\"></i>
\t\t\t<i id=\"notificationCircle\" style=\"visibility: hidden\" class=\"top right inverted orange corner circle icon\"></i>
\t\t</i></a>
\t\t<a class=\"item\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["terminate"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "logout", [], "main"]), "html", null, true);
        echo "</a>
\t\t<a class =\"item\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["user"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", false, false, false, 10), "html", null, true);
        echo "</a>
\t</div>
</div>
<div id=\"second-menu\" class=\"ui secondary pointing menu\" style=\"visibility:hidden;\">
\t<a class=\"item\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["question"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "question", [], "main"]), "html", null, true);
        echo "</a>
\t<a class=\"item\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["group"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "group", [], "main"]), "html", null, true);
        echo "</a>
\t<a class=\"item\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["qcm"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "qcm", [], "main"]), "html", null, true);
        echo "</a>
\t<a class=\"item\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["exam"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('t')->getCallable(), [$context, "exam", [], "main"]), "html", null, true);
        echo "</a>
</div>

";
    }

    public function getTemplateName()
    {
        return "/main/UI/userNavbar.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 17,  82 => 16,  76 => 15,  70 => 14,  61 => 10,  55 => 9,  48 => 5,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "/main/UI/userNavbar.html", "/Users/qgorak/Programmation/Ubiquity-workspace/qcm-app/app/views/main/UI/userNavbar.html");
    }
}
