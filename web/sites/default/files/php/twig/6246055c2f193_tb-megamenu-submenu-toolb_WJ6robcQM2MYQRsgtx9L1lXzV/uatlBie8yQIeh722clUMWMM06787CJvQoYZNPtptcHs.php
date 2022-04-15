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

/* modules/contrib/tb_megamenu/templates/backend/tb-megamenu-submenu-toolbox.html.twig */
class __TwigTemplate_0e1ad767e77ebdaaa12c6342a9f97600 extends \Twig\Template
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
        // line 1
        echo "<div id=\"tbm-admin-mm-toolsub\" class=\"admin-toolbox\">
  <h3>";
        // line 2
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submenu Configuration"));
        echo " (<a href=\"#\" class=\"back-megamenu-toolbox\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("MegaMenu Toolbox"));
        echo "</a>)</h3>
  <p>";
        // line 3
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Configue all level 2+ items."));
        echo "</p>
  <div class=\"tbm-tools-container\">
    <div>
      <label aria-label=\"";
        // line 6
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add row"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add a new row to the selected submenu."));
        echo "\" class=\"hasTip\"> ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add row"));
        echo " </label>
      <fieldset class=\"btn-group\">
        <a href=\"\" class=\"btn toolsub-addrow toolbox-action\" data-action=\"addRow\">&plus;</a>
      </fieldset>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"";
        // line 12
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide on mobile"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide this submenu below the mobile breakpoint."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide on mobile"));
        echo "</label>
      <fieldset class=\"radio btn-group toolsub-hidewhencollapse\">
        <input type=\"radio\" id=\"togglesubHideWhenCollapse0\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"togglesubHideWhenCollapse\" value=\"0\" checked=\"checked\"/>
        <label for=\"togglesubHideWhenCollapse0\" title=\"";
        // line 15
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Keep showing this submenu when the menu is collapsed on mobile."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("No"));
        echo "</label>
        <input type=\"radio\" id=\"togglesubHideWhenCollapse1\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"togglesubHideWhenCollapse\" value=\"1\"/>
        <label for=\"togglesubHideWhenCollapse1\" title=\"";
        // line 17
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide this submenu when the menu is collapsed on mobile."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Yes"));
        echo "</label>
      </fieldset>
    </div>                    
    <div>
      <label aria-label=\"";
        // line 21
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submenu width (px)"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Set the submenu width in pixels. This field must be a integer."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submenu width (px)"));
        echo "</label>
      <input type=\"text\" class=\"toolsub-width toolbox-input input-small\" name=\"toolsub-width\" data-name=\"width\" value=\"\" />
    </div>
    <div>
      <label aria-label=\"";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Alignment"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Align this submenu."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Alignment"));
        echo "</label>
      <fieldset class=\"toolsub-alignment btn-group\">
        <a class=\"btn toolsub-align-left toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"left\" title=\"";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Left"));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Left"));
        echo "</a>
        <a class=\"btn toolsub-align-right toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"right\" title=\"";
        // line 28
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Right"));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Right"));
        echo "</a>
        <a class=\"btn toolsub-align-center toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"center\" title=\"";
        // line 29
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Center"));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Center"));
        echo "</a>
        <a class=\"btn toolsub-align-justify toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"justify\" title=\"";
        // line 30
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Justify"));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Justify"));
        echo "</a>
      </fieldset>
    </div>          
    <div>
      <label aria-label=\"";
        // line 34
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("CSS class"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Attach a custom CSS class to this submenu."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("CSS class"));
        echo "</label>
      <input type=\"text\" class=\"toolsub-exclass toolbox-input input-medium\" name=\"toolsub-exclass\" data-name=\"class\" value=\"\" />
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/tb_megamenu/templates/backend/tb-megamenu-submenu-toolbox.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  140 => 34,  131 => 30,  125 => 29,  119 => 28,  113 => 27,  104 => 25,  93 => 21,  84 => 17,  77 => 15,  67 => 12,  54 => 6,  48 => 3,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div id=\"tbm-admin-mm-toolsub\" class=\"admin-toolbox\">
  <h3>{{ 'Submenu Configuration'|t }} (<a href=\"#\" class=\"back-megamenu-toolbox\">{{ 'MegaMenu Toolbox'|t }}</a>)</h3>
  <p>{{ 'Configue all level 2+ items.'|t }}</p>
  <div class=\"tbm-tools-container\">
    <div>
      <label aria-label=\"{{ 'Add row'|t }} - {{ 'Add a new row to the selected submenu.'|t }}\" class=\"hasTip\"> {{ 'Add row'|t }} </label>
      <fieldset class=\"btn-group\">
        <a href=\"\" class=\"btn toolsub-addrow toolbox-action\" data-action=\"addRow\">&plus;</a>
      </fieldset>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"{{ 'Hide on mobile'|t }} - {{ 'Hide this submenu below the mobile breakpoint.'|t }}\">{{ 'Hide on mobile'|t }}</label>
      <fieldset class=\"radio btn-group toolsub-hidewhencollapse\">
        <input type=\"radio\" id=\"togglesubHideWhenCollapse0\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"togglesubHideWhenCollapse\" value=\"0\" checked=\"checked\"/>
        <label for=\"togglesubHideWhenCollapse0\" title=\"{{ 'Keep showing this submenu when the menu is collapsed on mobile.'|t }}\">{{ 'No'|t }}</label>
        <input type=\"radio\" id=\"togglesubHideWhenCollapse1\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"togglesubHideWhenCollapse\" value=\"1\"/>
        <label for=\"togglesubHideWhenCollapse1\" title=\"{{ 'Hide this submenu when the menu is collapsed on mobile.'|t }}\">{{ 'Yes'|t }}</label>
      </fieldset>
    </div>                    
    <div>
      <label aria-label=\"{{ 'Submenu width (px)'|t }} - {{ 'Set the submenu width in pixels. This field must be a integer.'|t }}\" class=\"hasTip\">{{ 'Submenu width (px)'|t }}</label>
      <input type=\"text\" class=\"toolsub-width toolbox-input input-small\" name=\"toolsub-width\" data-name=\"width\" value=\"\" />
    </div>
    <div>
      <label aria-label=\"{{ 'Alignment'|t }} - {{ 'Align this submenu.'|t }}\" class=\"hasTip\">{{ 'Alignment'|t }}</label>
      <fieldset class=\"toolsub-alignment btn-group\">
        <a class=\"btn toolsub-align-left toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"left\" title=\"{{ 'Left'|t }}\">{{ 'Left'|t }}</a>
        <a class=\"btn toolsub-align-right toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"right\" title=\"{{ 'Right'|t }}\">{{ 'Right'|t }}</a>
        <a class=\"btn toolsub-align-center toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"center\" title=\"{{ 'Center'|t }}\">{{ 'Center'|t }}</a>
        <a class=\"btn toolsub-align-justify toolbox-action\" href=\"#\" data-action=\"alignment\" data-align=\"justify\" title=\"{{ 'Justify'|t }}\">{{ 'Justify'|t }}</a>
      </fieldset>
    </div>          
    <div>
      <label aria-label=\"{{ 'CSS class'|t }} - {{ 'Attach a custom CSS class to this submenu.'|t }}\" class=\"hasTip\">{{ 'CSS class'|t }}</label>
      <input type=\"text\" class=\"toolsub-exclass toolbox-input input-medium\" name=\"toolsub-exclass\" data-name=\"class\" value=\"\" />
    </div>
  </div>
</div>
", "modules/contrib/tb_megamenu/templates/backend/tb-megamenu-submenu-toolbox.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/modules/contrib/tb_megamenu/templates/backend/tb-megamenu-submenu-toolbox.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("t" => 2);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                ['t'],
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
