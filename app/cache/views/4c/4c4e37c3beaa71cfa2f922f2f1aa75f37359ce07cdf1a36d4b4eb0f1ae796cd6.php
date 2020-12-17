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

/* @activeTheme/main/vFooter.html */
class __TwigTemplate_ff767d3b47c417d1f4ad340f1a0471d1afbccdb5dc6aba83d9a878808c8f8a4c extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'footer' => [$this, 'block_footer'],
            'scripts' => [$this, 'block_scripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        $this->displayBlock('footer', $context, $blocks);
        // line 3
        $this->displayBlock('scripts', $context, $blocks);
        // line 14
        echo "</body>
</html>";
    }

    // line 1
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 3
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"]);
        echo "
";
        // line 5
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.6/dist/semantic.min.js"]);
        echo "
";
        // line 6
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/authModal.js"]);
        echo "
";
        // line 7
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/menu.js"]);
        echo "
";
        // line 8
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/ckeditor/ckeditor.js"]);
        echo "
";
        // line 9
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/ckeditor/translations/fr.js"]);
        echo "
";
        // line 10
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/ckeditor/translations/en-gb.js"]);
        echo "
";
        // line 11
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/ckeditor/include.js"]);
        echo "
";
        // line 12
        echo call_user_func_array($this->env->getFunction('js')->getCallable(), ["js/notification.js"]);
        echo "
";
    }

    public function getTemplateName()
    {
        return "@activeTheme/main/vFooter.html";
    }

    public function getDebugInfo()
    {
        return array (  90 => 12,  86 => 11,  82 => 10,  78 => 9,  74 => 8,  70 => 7,  66 => 6,  62 => 5,  58 => 4,  54 => 3,  48 => 1,  43 => 14,  41 => 3,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@activeTheme/main/vFooter.html", "/Users/qgorak/Programmation/Ubiquity-workspace/qcm-app/app/views/main/vFooter.html");
    }
}
