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

/* themes/contrib/gin/templates/navigation/toolbar--gin.html.twig */
class __TwigTemplate_7bab8e58a970d63ac1d092078f08febd extends \Twig\Template
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
        // line 23
        echo "<div";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => "toolbar"], "method", false, false, true, 23), 23, $this->source), "html", null, true);
        echo ">
  <nav";
        // line 24
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["toolbar_attributes"] ?? null), "addClass", [0 => "toolbar-bar", 1 => "clearfix"], "method", false, false, true, 24), 24, $this->source), "html", null, true);
        echo ">
    <h2 class=\"visually-hidden\">";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["toolbar_heading"] ?? null), 25, $this->source), "html", null, true);
        echo "</h2>
    ";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["tabs"] ?? null));
        foreach ($context['_seq'] as $context["key"] => $context["tab"]) {
            // line 27
            echo "      ";
            $context["tray"] = (($__internal_compile_0 = ($context["trays"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["key"]] ?? null) : null);
            // line 28
            echo "      ";
            $context["user_menu"] = (((($__internal_compile_1 = twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "links", [], "any", false, false, true, 28)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["user_links"] ?? null) : null)) ? ("user-menu") : (false));
            // line 29
            echo "      ";
            if (((            // line 30
($context["toolbar_variant"] ?? null) != "classic") && ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 31
$context["tab"], "attributes", [], "any", false, false, true, 31), "id", [], "any", false, false, true, 31) == "admin-toolbar-search-tab") || (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 32
$context["tab"], "attributes", [], "any", false, false, true, 32), "id", [], "any", false, false, true, 32) == "responsive-preview-toolbar-tab")))) {
                // line 35
                echo "        ";
                // line 36
                echo "      ";
            } else {
                // line 37
                echo "        ";
                if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["tab"], "attributes", [], "any", false, false, true, 37), "id", [], "any", false, false, true, 37) == "toolbar-tab-tour")) {
                    // line 38
                    echo "        <div";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["tab"], "attributes", [], "any", false, false, true, 38), "addClass", [0 => "toolbar-tab", 1 => ($context["user_menu"] ?? null), 2 => (((($__internal_compile_2 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 38)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["#id"] ?? null) : null)) ? (("toolbar-tab--" . $this->sandbox->ensureToStringAllowed((($__internal_compile_3 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 38)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["#id"] ?? null) : null), 38, $this->source))) : (null))], "method", false, false, true, 38), "removeAttribute", [0 => "id"], "method", false, false, true, 38), 38, $this->source), "html", null, true);
                    echo ">
        ";
                } else {
                    // line 40
                    echo "        <div";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["tab"], "attributes", [], "any", false, false, true, 40), "addClass", [0 => "toolbar-tab", 1 => ($context["user_menu"] ?? null), 2 => (((($__internal_compile_4 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 40)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["#id"] ?? null) : null)) ? (("toolbar-tab--" . $this->sandbox->ensureToStringAllowed((($__internal_compile_5 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 40)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["#id"] ?? null) : null), 40, $this->source))) : (null))], "method", false, false, true, 40), 40, $this->source), "html", null, true);
                    echo ">
        ";
                }
                // line 42
                echo "          ";
                if (((($__internal_compile_6 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 42)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["#id"] ?? null) : null) == "toolbar-item-administration")) {
                    // line 43
                    echo "            <a class=\"toolbar-menu__logo\" href=\"/admin/content\" aria-label=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Toolbar Menu Logo"));
                    echo "\">
              <span class=\"visually-hidden\">";
                    // line 44
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Toolbar Menu Logo"));
                    echo "</span>
            </a>
            ";
                    // line 46
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 46), 46, $this->source), "html", null, true);
                    echo "
          ";
                } else {
                    // line 48
                    echo "            ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 48), 48, $this->source), "html", null, true);
                    echo "
          ";
                }
                // line 50
                echo "          <div";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "attributes", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
                echo ">
            ";
                // line 51
                if (twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "label", [], "any", false, false, true, 51)) {
                    // line 52
                    echo "              <nav class=\"toolbar-lining clearfix\" role=\"navigation\" aria-label=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "label", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
                    echo "\">
                <h3 class=\"toolbar-tray-name visually-hidden\">";
                    // line 53
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "label", [], "any", false, false, true, 53), 53, $this->source), "html", null, true);
                    echo "</h3>
            ";
                } else {
                    // line 55
                    echo "              <nav class=\"toolbar-lining clearfix\" role=\"navigation\">
            ";
                }
                // line 57
                echo "            ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "links", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
                echo "
            </nav>
          </div>
        </div>
      ";
            }
            // line 62
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['tab'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        echo "  </nav>
  ";
        // line 64
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["remainder"] ?? null), 64, $this->source), "html", null, true);
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/gin/templates/navigation/toolbar--gin.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  151 => 64,  148 => 63,  142 => 62,  133 => 57,  129 => 55,  124 => 53,  119 => 52,  117 => 51,  112 => 50,  106 => 48,  101 => 46,  96 => 44,  91 => 43,  88 => 42,  82 => 40,  76 => 38,  73 => 37,  70 => 36,  68 => 35,  66 => 32,  65 => 31,  64 => 30,  62 => 29,  59 => 28,  56 => 27,  52 => 26,  48 => 25,  44 => 24,  39 => 23,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override for the administrative toolbar.
 *
 * Available variables:
 * - attributes: HTML attributes for the wrapper.
 * - toolbar_attributes: HTML attributes to apply to the toolbar.
 * - toolbar_heading: The heading or label for the toolbar.
 * - tabs: List of tabs for the toolbar.
 *   - attributes: HTML attributes for the tab container.
 *   - link: Link or button for the menu tab.
 * - trays: Toolbar tray list, each associated with a tab. Each tray in trays
 *   contains:
 *   - attributes: HTML attributes to apply to the tray.
 *   - label: The tray's label.
 *   - links: The tray menu links.
 * - remainder: Any non-tray, non-tab elements left to be rendered.
 *
 * @see template_preprocess_toolbar()
 */
#}
<div{{ attributes.addClass('toolbar') }}>
  <nav{{ toolbar_attributes.addClass('toolbar-bar', 'clearfix') }}>
    <h2 class=\"visually-hidden\">{{ toolbar_heading }}</h2>
    {% for key, tab in tabs %}
      {% set tray = trays[key] %}
      {% set user_menu = tray.links['user_links'] ? 'user-menu' : false %}
      {% if
        toolbar_variant != 'classic' and (
          tab.attributes.id == 'admin-toolbar-search-tab' or
          tab.attributes.id == 'responsive-preview-toolbar-tab'
        )
      %}
        {# render nothing #}
      {% else %}
        {% if tab.attributes.id == 'toolbar-tab-tour' %}
        <div{{ tab.attributes.addClass('toolbar-tab', user_menu, tab.link['#id'] ? 'toolbar-tab--' ~ tab.link['#id'] : null).removeAttribute('id') }}>
        {% else %}
        <div{{ tab.attributes.addClass('toolbar-tab', user_menu, tab.link['#id'] ? 'toolbar-tab--' ~ tab.link['#id'] : null) }}>
        {% endif %}
          {% if tab.link['#id'] == 'toolbar-item-administration' %}
            <a class=\"toolbar-menu__logo\" href=\"/admin/content\" aria-label=\"{{ 'Toolbar Menu Logo'|t }}\">
              <span class=\"visually-hidden\">{{ 'Toolbar Menu Logo'|t }}</span>
            </a>
            {{ tab.link }}
          {% else %}
            {{ tab.link }}
          {% endif %}
          <div{{ tray.attributes }}>
            {% if tray.label %}
              <nav class=\"toolbar-lining clearfix\" role=\"navigation\" aria-label=\"{{ tray.label }}\">
                <h3 class=\"toolbar-tray-name visually-hidden\">{{ tray.label }}</h3>
            {% else %}
              <nav class=\"toolbar-lining clearfix\" role=\"navigation\">
            {% endif %}
            {{ tray.links }}
            </nav>
          </div>
        </div>
      {% endif %}
    {% endfor %}
  </nav>
  {{ remainder }}
</div>
", "themes/contrib/gin/templates/navigation/toolbar--gin.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/themes/contrib/gin/templates/navigation/toolbar--gin.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 26, "set" => 27, "if" => 29);
        static $filters = array("escape" => 23, "t" => 43);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for', 'set', 'if'],
                ['escape', 't'],
                []
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
