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

/* themes/contrib/gin/templates/navigation/toolbar--gin--secondary.html.twig */
class __TwigTemplate_270c8f0fa7839cc0076922ab24720fdc extends \Twig\Template
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
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => "toolbar", 1 => "toolbar-meta"], "method", false, false, true, 23), "setAttribute", [0 => "id", 1 => "toolbar-administration-secondary"], "method", false, false, true, 23), 23, $this->source), "html", null, true);
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
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["tabs"] ?? null));
        foreach ($context['_seq'] as $context["key"] => $context["tab"]) {
            // line 28
            echo "      ";
            $context["tray"] = (($__internal_compile_0 = ($context["trays"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["key"]] ?? null) : null);
            // line 29
            echo "      ";
            $context["user_menu"] = (((($__internal_compile_1 = twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "links", [], "any", false, false, true, 29)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["user_links"] ?? null) : null)) ? ("user-menu") : (false));
            // line 30
            echo "      ";
            $context["item_id"] = [];
            // line 31
            echo "
      ";
            // line 32
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((($__internal_compile_2 = (($__internal_compile_3 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 32)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["#attributes"] ?? null) : null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["class"] ?? null) : null));
            foreach ($context['_seq'] as $context["key"] => $context["item"]) {
                // line 33
                echo "        ";
                if (twig_in_filter("icon-", $context["item"])) {
                    // line 34
                    echo "          ";
                    $context["item_id"] = twig_array_merge($this->sandbox->ensureToStringAllowed(($context["item_id"] ?? null), 34, $this->source), [0 => ("toolbar-id--" . $this->sandbox->ensureToStringAllowed($context["item"], 34, $this->source))]);
                    // line 35
                    echo "        ";
                }
                // line 36
                echo "      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 37
            echo "
      ";
            // line 38
            $context["tab_id"] = (((($__internal_compile_4 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 38)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["#id"] ?? null) : null)) ? (("toolbar-tab--" . $this->sandbox->ensureToStringAllowed((($__internal_compile_5 = twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 38)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["#id"] ?? null) : null), 38, $this->source))) : (""));
            // line 39
            echo "      ";
            $context["tab_classes"] = twig_array_merge($this->sandbox->ensureToStringAllowed(($context["item_id"] ?? null), 39, $this->source), [0 => "toolbar-tab", 1 => ($context["user_menu"] ?? null), 2 => ($context["tab_id"] ?? null)]);
            // line 40
            echo "
      ";
            // line 41
            $context["denylist_items"] = [0 => "toolbar-id--toolbar-icon-menu", 1 => "toolbar-id--toolbar-icon-edit"];
            // line 45
            echo "
      ";
            // line 47
            echo "      ";
            if (!twig_in_filter((($__internal_compile_6 = ($context["item_id"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6[0] ?? null) : null), ($context["denylist_items"] ?? null))) {
                // line 48
                echo "        ";
                $context["has_user_picture"] = (((($__internal_compile_7 = ($context["item_id"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7[0] ?? null) : null) == "toolbar-id--toolbar-icon-user") && ($context["user_picture"] ?? null));
                // line 49
                echo "        ";
                $context["user_class"] = ((($context["has_user_picture"] ?? null)) ? ("has-user-icon") : (false));
                // line 50
                echo "        <div";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["tab"], "attributes", [], "any", false, false, true, 50), "addClass", [0 => ($context["tab_classes"] ?? null), 1 => ($context["user_class"] ?? null)], "method", false, false, true, 50), 50, $this->source), "html", null, true);
                echo ">
          ";
                // line 51
                if (($context["has_user_picture"] ?? null)) {
                    // line 52
                    echo "            <a href=\"/user/";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "id", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
                    echo "\" class=\"toolbar-item icon-user\" role=\"button\" id=\"toolbar-item-user\">
              <img src=\"";
                    // line 53
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_picture"] ?? null), 53, $this->source), "html", null, true);
                    echo "\" alt=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "account", [], "any", false, false, true, 53), "name", [], "any", false, false, true, 53), 53, $this->source), "html", null, true);
                    echo "\" class=\"icon-user__image\" />
            </a>
          ";
                } else {
                    // line 56
                    echo "            ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["tab"], "link", [], "any", false, false, true, 56), 56, $this->source), "html", null, true);
                    echo "
          ";
                }
                // line 58
                echo "          <div";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "attributes", [], "any", false, false, true, 58), 58, $this->source), "html", null, true);
                echo ">
            ";
                // line 59
                if (twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "label", [], "any", false, false, true, 59)) {
                    // line 60
                    echo "              <nav class=\"toolbar-lining clearfix\" role=\"navigation\" aria-label=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "label", [], "any", false, false, true, 60), 60, $this->source), "html", null, true);
                    echo "\">
                <h3 class=\"toolbar-tray-name visually-hidden\">";
                    // line 61
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "label", [], "any", false, false, true, 61), 61, $this->source), "html", null, true);
                    echo "</h3>
            ";
                } else {
                    // line 63
                    echo "              <nav class=\"toolbar-lining clearfix\" role=\"navigation\">
            ";
                }
                // line 65
                echo "            ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["tray"] ?? null), "links", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
                echo "
            </nav>
          </div>
        </div>
      ";
            }
            // line 70
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['tab'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 71
        echo "  </nav>
  ";
        // line 72
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["remainder"] ?? null), 72, $this->source), "html", null, true);
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/gin/templates/navigation/toolbar--gin--secondary.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  178 => 72,  175 => 71,  169 => 70,  160 => 65,  156 => 63,  151 => 61,  146 => 60,  144 => 59,  139 => 58,  133 => 56,  125 => 53,  120 => 52,  118 => 51,  113 => 50,  110 => 49,  107 => 48,  104 => 47,  101 => 45,  99 => 41,  96 => 40,  93 => 39,  91 => 38,  88 => 37,  82 => 36,  79 => 35,  76 => 34,  73 => 33,  69 => 32,  66 => 31,  63 => 30,  60 => 29,  57 => 28,  53 => 27,  48 => 25,  44 => 24,  39 => 23,);
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
<div{{ attributes.addClass('toolbar', 'toolbar-meta').setAttribute('id', 'toolbar-administration-secondary') }}>
  <nav{{ toolbar_attributes.addClass('toolbar-bar', 'clearfix') }}>
    <h2 class=\"visually-hidden\">{{ toolbar_heading }}</h2>

    {% for key, tab in tabs %}
      {% set tray = trays[key] %}
      {% set user_menu = tray.links['user_links'] ? 'user-menu' : false %}
      {% set item_id = [] %}

      {% for key, item in tab.link['#attributes']['class'] %}
        {% if 'icon-' in item %}
          {% set item_id = item_id|merge(['toolbar-id--' ~ item]) %}
        {% endif %}
      {% endfor %}

      {% set tab_id = tab.link['#id'] ? 'toolbar-tab--' ~ tab.link['#id'] %}
      {% set tab_classes = item_id|merge(['toolbar-tab', user_menu, tab_id]) %}

      {% set denylist_items = [
          'toolbar-id--toolbar-icon-menu',
          'toolbar-id--toolbar-icon-edit',
      ] %}

      {# All items except main nav #}
      {% if item_id[0] not in denylist_items %}
        {% set has_user_picture = item_id[0] == 'toolbar-id--toolbar-icon-user' and user_picture %}
        {% set user_class = has_user_picture ? 'has-user-icon' : false %}
        <div{{ tab.attributes.addClass(tab_classes, user_class) }}>
          {% if has_user_picture %}
            <a href=\"/user/{{ user.id }}\" class=\"toolbar-item icon-user\" role=\"button\" id=\"toolbar-item-user\">
              <img src=\"{{ user_picture }}\" alt=\"{{ user.account.name }}\" class=\"icon-user__image\" />
            </a>
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
", "themes/contrib/gin/templates/navigation/toolbar--gin--secondary.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/themes/contrib/gin/templates/navigation/toolbar--gin--secondary.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 27, "set" => 28, "if" => 33);
        static $filters = array("escape" => 23, "merge" => 34);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for', 'set', 'if'],
                ['escape', 'merge'],
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
