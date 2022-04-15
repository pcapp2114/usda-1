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

/* themes/contrib/gin/templates/form/datetime-wrapper.html.twig */
class __TwigTemplate_1c10c62f6a560475793f23f8f2990116 extends \Twig\Template
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
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 12
        $context["classes"] = [0 => "form-item", 1 => "form-datetime-wrapper", 2 => ((        // line 15
($context["description_toggle"] ?? null)) ? ("help-icon__description-container") : (""))];
        // line 19
        $context["title_classes"] = [0 => "form-item__label", 1 => ((        // line 21
($context["required"] ?? null)) ? ("js-form-required") : ("")), 2 => ((        // line 22
($context["required"] ?? null)) ? ("form-required") : ("")), 3 => ((        // line 23
($context["errors"] ?? null)) ? ("has-error") : (""))];
        // line 27
        $context["description_classes"] = [0 => "form-item__description", 1 => (((        // line 29
($context["description_display"] ?? null) == "invisible")) ? ("visually-hidden") : (""))];
        // line 32
        echo "<div ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 32), 32, $this->source), "html", null, true);
        echo ">
  ";
        // line 33
        if ((($context["description"] ?? null) && ($context["description_toggle"] ?? null))) {
            // line 34
            echo "  <div class=\"help-icon\">
  ";
        }
        // line 36
        echo "  ";
        if (($context["title"] ?? null)) {
            // line 37
            echo "  <h4";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [0 => ($context["title_classes"] ?? null)], "method", false, false, true, 37), 37, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 37, $this->source), "html", null, true);
            echo "</h4>
  ";
        }
        // line 39
        echo "  ";
        if ((($context["description"] ?? null) && ($context["description_toggle"] ?? null))) {
            // line 40
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("gin/gin_description_toggle"), "html", null, true);
            echo "
    <button class=\"help-icon__description-toggle\"></button>
  </div>
  ";
        }
        // line 44
        echo "  ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 44, $this->source), "html", null, true);
        echo "
";
        // line 45
        if (($context["errors"] ?? null)) {
            // line 46
            echo "  <div class=\"form-item__error-message\">
    ";
            // line 47
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null), 47, $this->source), "html", null, true);
            echo "
  </div>
";
        }
        // line 50
        if (($context["description"] ?? null)) {
            // line 51
            echo "  <div";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["description_attributes"] ?? null), "addClass", [0 => ($context["description_classes"] ?? null)], "method", false, false, true, 51), 51, $this->source), "html", null, true);
            echo ">
    ";
            // line 52
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["description"] ?? null), 52, $this->source), "html", null, true);
            echo "
  </div>
";
        }
        // line 55
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/gin/templates/form/datetime-wrapper.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  112 => 55,  106 => 52,  101 => 51,  99 => 50,  93 => 47,  90 => 46,  88 => 45,  83 => 44,  75 => 40,  72 => 39,  64 => 37,  61 => 36,  57 => 34,  55 => 33,  50 => 32,  48 => 29,  47 => 27,  45 => 23,  44 => 22,  43 => 21,  42 => 19,  40 => 15,  39 => 12,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override of a datetime form wrapper.
 *
 * @todo Refactor when https://www.drupal.org/node/3010558 is fixed.
 *
 * @see template_preprocess_form_element()
 */
#}
{%
  set classes = [
    'form-item',
    'form-datetime-wrapper',
    description_toggle ? 'help-icon__description-container',
  ]
  %}
{%
  set title_classes = [
    'form-item__label',
    required ? 'js-form-required',
    required ? 'form-required',
    errors ? 'has-error',
  ]
%}
{%
  set description_classes = [
    'form-item__description',
    description_display == 'invisible' ? 'visually-hidden',
  ]
%}
<div {{ attributes.addClass(classes) }}>
  {% if description and description_toggle %}
  <div class=\"help-icon\">
  {% endif %}
  {% if title %}
  <h4{{ title_attributes.addClass(title_classes) }}>{{ title }}</h4>
  {% endif %}
  {% if description and description_toggle %}
    {{ attach_library('gin/gin_description_toggle') }}
    <button class=\"help-icon__description-toggle\"></button>
  </div>
  {% endif %}
  {{ content }}
{% if errors %}
  <div class=\"form-item__error-message\">
    {{ errors }}
  </div>
{% endif %}
{% if description %}
  <div{{ description_attributes.addClass(description_classes) }}>
    {{ description }}
  </div>
{% endif %}
</div>
", "themes/contrib/gin/templates/form/datetime-wrapper.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/themes/contrib/gin/templates/form/datetime-wrapper.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 12, "if" => 33);
        static $filters = array("escape" => 32);
        static $functions = array("attach_library" => 40);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
                ['attach_library']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
