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

/* modules/contrib/tb_megamenu/templates/backend/tb-megamenu-column-toolbox.html.twig */
class __TwigTemplate_c83678f46dac94452b5eeeb19c04aac2 extends \Twig\Template
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
        echo "<div id=\"tbm-admin-mm-toolcol\" class=\"admin-toolbox\">
  <h3>";
        // line 2
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Column Configuration"));
        echo "(<a href=\"#\" class=\"back-megamenu-toolbox\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("MegaMenu Toolbox"));
        echo "</a>)</h3>
  <p>";
        // line 3
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add, remove and configure columns."));
        echo "</p>
  <div class=\"tbm-tools-container\">
    <div>
      <label class=\"hasTip\" aria-label=\"";
        // line 6
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add/remove column"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Click + to add a new column to the right of the selected column. Click - to remove the selected column."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add/remove column"));
        echo "</label>
      <fieldset class=\"btn-group\">
        <a href=\"\" class=\"btn toolcol-addcol toolbox-action first\" data-action=\"addColumn\" title=\"";
        // line 8
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add a new column to the right of the selected column."));
        echo "\">&plus;</a>
        <a href=\"\" class=\"btn toolcol-removecol toolbox-action last\" data-action=\"removeColumn\" title=\"";
        // line 9
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Remove the selected column."));
        echo "\">&minus;</a>
      </fieldset>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"";
        // line 13
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Move row up/down"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Click up to move the selected row up. Click down to move the selected row down."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Move row up/down"));
        echo "</label>
      <fieldset class=\"btn-group\">
        <a href=\"\" class=\"btn toolcol-addcol toolbox-action first\" data-action=\"rowUp\" title=\"";
        // line 15
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Move the selected row up."));
        echo "\">&uarr;</a>
        <a href=\"\" class=\"btn toolcol-removecol toolbox-action last\" data-action=\"rowDown\" title=\"";
        // line 16
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Move the selected row down."));
        echo "\">&darr;</a>
      </fieldset>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"";
        // line 20
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide on mobile"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide this column below the mobile breakpoint."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide on mobile"));
        echo "</label>
      <fieldset class=\"radio btn-group toolcol-hidewhencollapse\">
        <input type=\"radio\" id=\"toggleHideWhenCollapse0\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"toggleHideWhenCollapse\" value=\"0\" checked=\"checked\"/>
        <label for=\"toggleHideWhenCollapse0\" title=\"";
        // line 23
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show this column on mobile."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("No"));
        echo "</label>
        <input type=\"radio\" id=\"toggleHideWhenCollapse1\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"toggleHideWhenCollapse\" value=\"1\"/>
        <label for=\"toggleHideWhenCollapse1\" title=\"";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide this column on mobile."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Yes"));
        echo "</label>
      </fieldset>
    </div>
    <div id=\"tbm-grid-wrapper\">
      <label aria-label=\"";
        // line 29
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Grid (1-12)"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Set the number of grid columns that the selected column should span."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Grid (1-12)"));
        echo "</label>
      <select class=\"toolcol-width toolbox-input toolbox-select input-mini\" name=\"toolcol-width\" data-name=\"width\">
        <option value=\"1\">1</option>
        <option value=\"2\">2</option>
        <option value=\"3\">3</option>
        <option value=\"4\">4</option>
        <option value=\"5\">5</option>
        <option value=\"6\">6</option>
        <option value=\"7\">7</option>
        <option value=\"8\">8</option>
        <option value=\"9\">9</option>
        <option value=\"10\">10</option>
        <option value=\"11\">11</option>
        <option value=\"12\">12</option>
      </select>
    </div>
    <div id=\"tbm-block-wrapper\">
      <label aria-label=\"";
        // line 46
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Blocks"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Place a block in the selected column."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Blocks"));
        echo "</label>
      <select class=\"toolcol-block toolbox-input toolbox-select input-medium\" name=\"toolcol-block\" data-name=\"block\" data-placeholder=\"";
        // line 47
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Select Block"));
        echo "\" style=\"width: 200px;\">
        <option value=\"\"></option>
        ";
        // line 49
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["blocks_options"] ?? null));
        foreach ($context['_seq'] as $context["block_id"] => $context["block"]) {
            // line 50
            echo "          <option value=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["block_id"], 50, $this->source), "html", null, true);
            echo "\"> ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["block"], 50, $this->source), "html", null, true);
            echo " </option>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['block_id'], $context['block'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 52
        echo "      </select>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"";
        // line 55
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show the block title."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show block title"));
        echo "</label>
      <fieldset class=\"radio btn-group toolcol-showblocktitle\">
        <input type=\"radio\" id=\"toggleShowBlockTitle0\" class=\"toolbox-toggle\" data-action=\"showBlockTitle\" name=\"toggleShowBlockTitle\" value=\"0\"/>
        <label for=\"toggleShowBlockTitle0\">";
        // line 58
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("No"));
        echo "</label>
        <input type=\"radio\" id=\"toggleShowBlockTitle1\" class=\"toolbox-toggle\" data-action=\"showBlockTitle\" name=\"toggleShowBlockTitle\" value=\"1\" checked=\"checked\"/>
        <label for=\"toggleShowBlockTitle1\">";
        // line 60
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Yes"));
        echo "</label>
      </fieldset>
    </div>          
    <div>
      <label aria-label=\"";
        // line 64
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("CSS class"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Attach a custom CSS class to this column."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("CSS class"));
        echo "</label>
      <input type=\"text\" class=\"input-medium toolcol-exclass toolbox-input\" name=\"toolcol-exclass\" data-name=\"class\" value=\"\" />
    </div>
  </div>
</div>    
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/tb_megamenu/templates/backend/tb-megamenu-column-toolbox.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  197 => 64,  190 => 60,  185 => 58,  177 => 55,  172 => 52,  161 => 50,  157 => 49,  152 => 47,  144 => 46,  120 => 29,  111 => 25,  104 => 23,  94 => 20,  87 => 16,  83 => 15,  74 => 13,  67 => 9,  63 => 8,  54 => 6,  48 => 3,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div id=\"tbm-admin-mm-toolcol\" class=\"admin-toolbox\">
  <h3>{{ 'Column Configuration'|t }}(<a href=\"#\" class=\"back-megamenu-toolbox\">{{ 'MegaMenu Toolbox'|t }}</a>)</h3>
  <p>{{ 'Add, remove and configure columns.'|t }}</p>
  <div class=\"tbm-tools-container\">
    <div>
      <label class=\"hasTip\" aria-label=\"{{ 'Add/remove column'|t }} - {{ 'Click + to add a new column to the right of the selected column. Click - to remove the selected column.'|t }}\">{{ 'Add/remove column'|t }}</label>
      <fieldset class=\"btn-group\">
        <a href=\"\" class=\"btn toolcol-addcol toolbox-action first\" data-action=\"addColumn\" title=\"{{ 'Add a new column to the right of the selected column.'|t }}\">&plus;</a>
        <a href=\"\" class=\"btn toolcol-removecol toolbox-action last\" data-action=\"removeColumn\" title=\"{{ 'Remove the selected column.'|t }}\">&minus;</a>
      </fieldset>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"{{ 'Move row up/down'|t }} - {{ 'Click up to move the selected row up. Click down to move the selected row down.'|t }}\">{{ 'Move row up/down'|t }}</label>
      <fieldset class=\"btn-group\">
        <a href=\"\" class=\"btn toolcol-addcol toolbox-action first\" data-action=\"rowUp\" title=\"{{ 'Move the selected row up.'|t }}\">&uarr;</a>
        <a href=\"\" class=\"btn toolcol-removecol toolbox-action last\" data-action=\"rowDown\" title=\"{{ 'Move the selected row down.'|t }}\">&darr;</a>
      </fieldset>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"{{ 'Hide on mobile'|t }} - {{ 'Hide this column below the mobile breakpoint.'|t }}\">{{ 'Hide on mobile'|t }}</label>
      <fieldset class=\"radio btn-group toolcol-hidewhencollapse\">
        <input type=\"radio\" id=\"toggleHideWhenCollapse0\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"toggleHideWhenCollapse\" value=\"0\" checked=\"checked\"/>
        <label for=\"toggleHideWhenCollapse0\" title=\"{{ 'Show this column on mobile.'|t }}\">{{ 'No'|t }}</label>
        <input type=\"radio\" id=\"toggleHideWhenCollapse1\" class=\"toolbox-toggle\" data-action=\"hideWhenCollapse\" name=\"toggleHideWhenCollapse\" value=\"1\"/>
        <label for=\"toggleHideWhenCollapse1\" title=\"{{ 'Hide this column on mobile.'|t }}\">{{ 'Yes'|t }}</label>
      </fieldset>
    </div>
    <div id=\"tbm-grid-wrapper\">
      <label aria-label=\"{{ 'Grid (1-12)'|t }} - {{ 'Set the number of grid columns that the selected column should span.'|t }}\" class=\"hasTip\">{{ 'Grid (1-12)'|t }}</label>
      <select class=\"toolcol-width toolbox-input toolbox-select input-mini\" name=\"toolcol-width\" data-name=\"width\">
        <option value=\"1\">1</option>
        <option value=\"2\">2</option>
        <option value=\"3\">3</option>
        <option value=\"4\">4</option>
        <option value=\"5\">5</option>
        <option value=\"6\">6</option>
        <option value=\"7\">7</option>
        <option value=\"8\">8</option>
        <option value=\"9\">9</option>
        <option value=\"10\">10</option>
        <option value=\"11\">11</option>
        <option value=\"12\">12</option>
      </select>
    </div>
    <div id=\"tbm-block-wrapper\">
      <label aria-label=\"{{ 'Blocks'|t }} - {{ 'Place a block in the selected column.'|t }}\" class=\"hasTip\">{{ 'Blocks'|t }}</label>
      <select class=\"toolcol-block toolbox-input toolbox-select input-medium\" name=\"toolcol-block\" data-name=\"block\" data-placeholder=\"{{ 'Select Block'|t }}\" style=\"width: 200px;\">
        <option value=\"\"></option>
        {% for block_id, block in blocks_options %}
          <option value=\"{{ block_id }}\"> {{ block }} </option>
        {% endfor %}
      </select>
    </div>
    <div>
      <label class=\"hasTip\" aria-label=\"{{ 'Show the block title.'|t }}\">{{ 'Show block title'|t }}</label>
      <fieldset class=\"radio btn-group toolcol-showblocktitle\">
        <input type=\"radio\" id=\"toggleShowBlockTitle0\" class=\"toolbox-toggle\" data-action=\"showBlockTitle\" name=\"toggleShowBlockTitle\" value=\"0\"/>
        <label for=\"toggleShowBlockTitle0\">{{ 'No'|t }}</label>
        <input type=\"radio\" id=\"toggleShowBlockTitle1\" class=\"toolbox-toggle\" data-action=\"showBlockTitle\" name=\"toggleShowBlockTitle\" value=\"1\" checked=\"checked\"/>
        <label for=\"toggleShowBlockTitle1\">{{ 'Yes'|t }}</label>
      </fieldset>
    </div>          
    <div>
      <label aria-label=\"{{ 'CSS class'|t }} - {{ 'Attach a custom CSS class to this column.'|t }}\" class=\"hasTip\">{{ 'CSS class'|t }}</label>
      <input type=\"text\" class=\"input-medium toolcol-exclass toolbox-input\" name=\"toolcol-exclass\" data-name=\"class\" value=\"\" />
    </div>
  </div>
</div>    
", "modules/contrib/tb_megamenu/templates/backend/tb-megamenu-column-toolbox.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/modules/contrib/tb_megamenu/templates/backend/tb-megamenu-column-toolbox.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 49);
        static $filters = array("t" => 2, "escape" => 50);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for'],
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
