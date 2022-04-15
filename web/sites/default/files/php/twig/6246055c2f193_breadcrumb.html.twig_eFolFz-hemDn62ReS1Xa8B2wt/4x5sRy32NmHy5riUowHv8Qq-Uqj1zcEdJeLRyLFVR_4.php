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

/* themes/contrib/uswds/templates/system/block/breadcrumb.html.twig */
class __TwigTemplate_a175403b729503624fdd5cc7306004c0 extends \Twig\Template
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
        if (($context["breadcrumb"] ?? null)) {
            // line 8
            echo "<h2 id=\"system-breadcrumb\" class=\"visually-hidden\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Breadcrumb"));
            echo "</h2>
<ol class=\"add-list-reset uswds-breadcrumbs uswds-horizontal-list\">
";
            // line 10
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["breadcrumb"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 11
                echo "  <li>
    ";
                // line 12
                if (twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 12)) {
                    // line 13
                    echo "      <a href=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
                    echo "\">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "text", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
                    echo "</a>
    ";
                } else {
                    // line 15
                    echo "      ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "text", [], "any", false, false, true, 15), 15, $this->source), "html", null, true);
                    echo "
    ";
                }
                // line 17
                echo "  </li>
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 19
            echo "</ol>
";
        }
    }

    public function getTemplateName()
    {
        return "themes/contrib/uswds/templates/system/block/breadcrumb.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 19,  70 => 17,  64 => 15,  56 => 13,  54 => 12,  51 => 11,  47 => 10,  41 => 8,  39 => 7,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * USWDS theme implementation for a breadcrumb trail.
 */
#}
{% if breadcrumb %}
<h2 id=\"system-breadcrumb\" class=\"visually-hidden\">{{ 'Breadcrumb'|t }}</h2>
<ol class=\"add-list-reset uswds-breadcrumbs uswds-horizontal-list\">
{% for item in breadcrumb %}
  <li>
    {% if item.url %}
      <a href=\"{{ item.url }}\">{{ item.text }}</a>
    {% else %}
      {{ item.text }}
    {% endif %}
  </li>
{% endfor %}
</ol>
{% endif %}
", "themes/contrib/uswds/templates/system/block/breadcrumb.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/themes/contrib/uswds/templates/system/block/breadcrumb.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 7, "for" => 10);
        static $filters = array("t" => 8, "escape" => 13);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['t', 'escape'],
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
