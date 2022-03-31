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

/* themes/contrib/uswds/templates/system/menu/menu--footer_menu.html.twig */
class __TwigTemplate_068db1af968a1602df79cbbafd194e26 extends \Twig\Template
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
        // line 7
        echo "
";
        // line 8
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 9
        echo "
";
        // line 14
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [($context["items"] ?? null), 0, ($context["footer_style"] ?? null)], 14, $context, $this->getSourceContext()));
        echo "

";
    }

    // line 16
    public function macro_menu_links($__items__ = null, $__menu_level__ = null, $__footer_style__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "items" => $__items__,
            "menu_level" => $__menu_level__,
            "footer_style" => $__footer_style__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 17
            echo "  ";
            $macros["menus"] = $this;
            // line 18
            echo "  ";
            if (($context["items"] ?? null)) {
                // line 19
                echo "
    ";
                // line 21
                echo "    ";
                if ((($context["menu_level"] ?? null) == 0)) {
                    // line 22
                    echo "    ";
                    if ((($context["footer_style"] ?? null) == "big")) {
                        // line 23
                        echo "    <div class=\"grid-row grid-gap\">
    ";
                    } else {
                        // line 25
                        echo "    <ul class=\"grid-row grid-gap add-list-reset\">
    ";
                    }
                    // line 27
                    echo "    ";
                }
                // line 28
                echo "
    ";
                // line 29
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 30
                    echo "
      ";
                    // line 31
                    if (((($context["menu_level"] ?? null) == 0) && ("big" == ($context["footer_style"] ?? null)))) {
                        // line 32
                        echo "      ";
                        // line 33
                        echo "      <div class=\"mobile-lg:grid-col-6 desktop:grid-col-3\">
        <section class=\"usa-footer__primary-content usa-footer__primary-content--collapsible\">
          <h4 class=\"usa-footer__primary-link\">";
                        // line 35
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 35), 35, $this->source), "html", null, true);
                        echo "</h4>
          ";
                        // line 36
                        if (twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 36)) {
                            // line 37
                            echo "          <ul class=\"usa-list usa-list--unstyled\">
            ";
                            // line 38
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 38), 1, ($context["footer_style"] ?? null)], 38, $context, $this->getSourceContext()));
                            echo "
          </ul>
          ";
                        }
                        // line 41
                        echo "        </section>
      </div>
      ";
                    } elseif (("big" ==                     // line 43
($context["footer_style"] ?? null))) {
                        // line 44
                        echo "      ";
                        // line 45
                        echo "      <li class=\"usa-footer__secondary-link\">
        ";
                        // line 46
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 46), 46, $this->source), $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 46), 46, $this->source)), "html", null, true);
                        echo "
      </li>
      ";
                    } elseif (("medium" ==                     // line 48
($context["footer_style"] ?? null))) {
                        // line 49
                        echo "      ";
                        // line 50
                        echo "      <li class=\"mobile-lg:grid-col-4 desktop:grid-col-2 usa-footer__primary-content\">
        ";
                        // line 51
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 51), 51, $this->source), $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 51), 51, $this->source), ["class" => [0 => "usa-footer__primary-link"]]), "html", null, true);
                        echo "
      </li>
      ";
                    } else {
                        // line 54
                        echo "      ";
                        // line 55
                        echo "      <li class=\"mobile-lg:grid-col-6 desktop:grid-col-auto usa-footer__primary-content\">
        ";
                        // line 56
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 56), 56, $this->source), $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 56), 56, $this->source), ["class" => [0 => "usa-footer__primary-link"]]), "html", null, true);
                        echo "
      </li>
      ";
                    }
                    // line 59
                    echo "
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 61
                echo "
    ";
                // line 63
                echo "    ";
                if ((($context["menu_level"] ?? null) == 0)) {
                    // line 64
                    echo "    ";
                    if ((($context["footer_style"] ?? null) == "big")) {
                        // line 65
                        echo "    </div>
    ";
                    } else {
                        // line 67
                        echo "    </ul>
    ";
                    }
                    // line 69
                    echo "    ";
                }
                // line 70
                echo "
  ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "themes/contrib/uswds/templates/system/menu/menu--footer_menu.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  195 => 70,  192 => 69,  188 => 67,  184 => 65,  181 => 64,  178 => 63,  175 => 61,  168 => 59,  162 => 56,  159 => 55,  157 => 54,  151 => 51,  148 => 50,  146 => 49,  144 => 48,  139 => 46,  136 => 45,  134 => 44,  132 => 43,  128 => 41,  122 => 38,  119 => 37,  117 => 36,  113 => 35,  109 => 33,  107 => 32,  105 => 31,  102 => 30,  98 => 29,  95 => 28,  92 => 27,  88 => 25,  84 => 23,  81 => 22,  78 => 21,  75 => 19,  72 => 18,  69 => 17,  54 => 16,  47 => 14,  44 => 9,  42 => 8,  39 => 7,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Override of system/menu.html.twig for the footer menu.
 */
#}

{% import _self as menus %}

{#
  We call a macro which calls itself to render the full tree.
  @see http://twig.sensiolabs.org/doc/tags/macro.html
#}
{{ menus.menu_links(items, 0, footer_style) }}

{% macro menu_links(items, menu_level, footer_style) %}
  {% import _self as menus %}
  {% if items %}

    {# Menu should not apply this wrapper if the macro already recursed. #}
    {% if menu_level == 0 %}
    {% if footer_style == 'big' %}
    <div class=\"grid-row grid-gap\">
    {% else %}
    <ul class=\"grid-row grid-gap add-list-reset\">
    {% endif %}
    {% endif %}

    {% for item in items %}

      {% if menu_level == 0 and 'big' == footer_style %}
      {# Turn the menu item into a header and its children the links. #}
      <div class=\"mobile-lg:grid-col-6 desktop:grid-col-3\">
        <section class=\"usa-footer__primary-content usa-footer__primary-content--collapsible\">
          <h4 class=\"usa-footer__primary-link\">{{ item.title }}</h4>
          {% if item.below %}
          <ul class=\"usa-list usa-list--unstyled\">
            {{ menus.menu_links(item.below, 1, footer_style) }}
          </ul>
          {% endif %}
        </section>
      </div>
      {% elseif 'big' == footer_style %}
      {# Big menu links. We've already recursed once, from above. #}
      <li class=\"usa-footer__secondary-link\">
        {{ link(item.title, item.url) }}
      </li>
      {% elseif 'medium' == footer_style %}
      {# Medium menu links. #}
      <li class=\"mobile-lg:grid-col-4 desktop:grid-col-2 usa-footer__primary-content\">
        {{ link(item.title, item.url, { class: ['usa-footer__primary-link'] }) }}
      </li>
      {% else %}
      {# Slim menu links. #}
      <li class=\"mobile-lg:grid-col-6 desktop:grid-col-auto usa-footer__primary-content\">
        {{ link(item.title, item.url, { class: ['usa-footer__primary-link'] }) }}
      </li>
      {% endif %}

    {% endfor %}

    {# Menu should not apply this wrapper if the macro already recursed. #}
    {% if menu_level == 0 %}
    {% if footer_style == 'big' %}
    </div>
    {% else %}
    </ul>
    {% endif %}
    {% endif %}

  {% endif %}
{% endmacro %}
", "themes/contrib/uswds/templates/system/menu/menu--footer_menu.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/themes/contrib/uswds/templates/system/menu/menu--footer_menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 8, "macro" => 16, "if" => 18, "for" => 29);
        static $filters = array("escape" => 35);
        static $functions = array("link" => 46);

        try {
            $this->sandbox->checkSecurity(
                ['import', 'macro', 'if', 'for'],
                ['escape'],
                ['link']
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
