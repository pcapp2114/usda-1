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

/* modules/contrib/tb_megamenu/templates/backend/tb-megamenu-backend.html.twig */
class __TwigTemplate_df9b7095d5bc5655b7e39341dd987ba4 extends \Twig\Template
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
        echo "<div id=\"tbm-admin\" class=\"hidden tbm-admin tbm\">
  <div class=\"admin-inline-toolbox clearfix\">
    <div class=\"tbm-admin-mm-row\">
      <div id=\"tbm-admin-mm-tb\">
        <div id=\"toolbox-loading\" class=\"toolbox-loading\">&nbsp;</div>
        <div id=\"toolbox-message\" class=\"toolbox-message\">&nbsp;</div>
        <div id=\"tbm-admin-mm-intro\" class=\"admin-toolbox\">
          <h3>";
        // line 8
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("MegaMenu Toolbox"));
        echo "</h3>
          <p>";
        // line 9
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("This toolbox includes global menu settings. Click on a menu item, submenu or column for additional configuration options."));
        echo "</p>
          <div class=\"tbm-tools-container\">
            <div>
              <label aria-label=\"";
        // line 12
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Mobile breakpint"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Specify a mobile breakpoint in pixels."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Mobile breakpoint"));
        echo "</label>
              <input type=\"text\" class=\"input-medium tbm-breakpoint toolbox-input\" name=\"tbm-breakpoint\" data-name=\"breakpoint\" value=\"";
        // line 13
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_0 = ($context["block_config"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["breakpoint"] ?? null) : null), 13, $this->source), "html", null, true);
        echo "\" />
            </div>
            <div id=\"tbm-animation-wrapper\">
              <label aria-label=\"";
        // line 16
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Animation"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Select an animation style for this menu."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Animation"));
        echo "</label>
              <select name=\"tbm-animation\">
                ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["animation_options"] ?? null));
        foreach ($context['_seq'] as $context["value"] => $context["title"]) {
            // line 19
            echo "                  <option value='";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["value"], 19, $this->source), "html", null, true);
            echo "' ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((((($__internal_compile_1 = ($context["block_config"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["animation"] ?? null) : null) == $context["value"])) ? ("selected") : ("")));
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["title"], 19, $this->source), "html", null, true);
            echo "</option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['value'], $context['title'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 21
        echo "              </select>
            </div>
            <div id=\"tbm-delay-wrapper\" style=\"display: ";
        // line 23
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((((($__internal_compile_2 = ($context["block_config"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["animation"] ?? null) : null) == "none")) ? ("none") : ("inline-block")));
        echo ";\">
              <label aria-label=\"";
        // line 24
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Animation delay (ms)"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Specify a delay for the animation - this field must be an integer."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Animation delay (ms)"));
        echo "</label>
              <input class=\"input-medium toolitem-delay toolbox-input\" name=\"tbm-delay\" type=\"text\" value=\"";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_3 = ($context["block_config"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["delay"] ?? null) : null), 25, $this->source), "html", null, true);
        echo "\" data-name=\"delay\"></input>
            </div>
            <div id=\"tbm-duration-wrapper\" style=\"display: ";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((((($__internal_compile_4 = ($context["block_config"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["animation"] ?? null) : null) == "none")) ? ("none") : ("inline-block")));
        echo ";\">
              <label aria-label=\"";
        // line 28
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Animation duration (ms)"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Specify a duration for the animation - this field must be an integer."));
        echo "\" class=\"hasTip\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Animation duration (ms)"));
        echo "</label>
              <input class=\"input-medium toolitem-duration toolbox-input\" name=\"tbm-duration\" type=\"text\" value=\"";
        // line 29
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_5 = ($context["block_config"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["duration"] ?? null) : null), 29, $this->source), "html", null, true);
        echo "\" data-name=\"duration\"></input>
            </div>
            <div>
              <label class=\"hasTip\" aria-label=\"";
        // line 32
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submenu arrow"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show or hide a clickable arrow next to top-level menu items that have a submenu. Clicking on the arrow reveals the submenu. Otherwise, hovering over a top-level menu item reveals the submenu."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submenu arrow"));
        echo "</label>
              <fieldset class=\"radio btn-group toolitem-auto-arrow\" data-auto-arrow=\"";
        // line 33
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_6 = ($context["block_config"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["auto-arrow"] ?? null) : null), 33, $this->source), "html", null, true);
        echo "\">
                <input type=\"radio\" ";
        // line 34
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_7 = ($context["block_config"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["auto-arrow"] ?? null) : null)) ? ("") : ("checked=\"checked\"")));
        echo " value=\"0\" name=\"tbm-auto-arrow\" data-action=\"toggleAutoArrow\" class=\"toolbox-toggle\" id=\"toggleAutoArrow0\">
                <label for=\"toggleAutoArrow0\" class=\"btn ";
        // line 35
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_8 = ($context["block_config"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["auto-arrow"] ?? null) : null)) ? ("") : ("active btn-danger")));
        echo "\" title=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Hide the arrow next to items that have a submenu."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("No"));
        echo "</label>
                <input type=\"radio\" ";
        // line 36
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_9 = ($context["block_config"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["auto-arrow"] ?? null) : null)) ? ("checked=\"checked\"") : ("")));
        echo " value=\"1\" name=\"tbm-auto-arrow\" data-action=\"toggleAutoArrow\" class=\"toolbox-toggle\" id=\"toggleAutoArrow1\">
                <label for=\"toggleAutoArrow1\" class=\"btn ";
        // line 37
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_10 = ($context["block_config"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["auto-arrow"] ?? null) : null)) ? ("active btn-success") : ("")));
        echo "\" title=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show the arrow next to items that have a submenu."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Yes"));
        echo "</label>
              </fieldset>        
            </div>
            <div>
              <label class=\"hasTip\" aria-label=\"";
        // line 41
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show submenu on mobile"));
        echo " - ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("When set to 'yes', submenus will always be visible on mobile. When set to 'no', the user will have to click to reveal submenus on mobile."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show submenu on mobile"));
        echo "</label>
              <fieldset class=\"radio btn-group toolitem-always-show-submenu\" data-always-show-submenu=\"";
        // line 42
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_11 = ($context["block_config"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["always-show-submenu"] ?? null) : null), 42, $this->source), "html", null, true);
        echo "\">
                <input type=\"radio\" ";
        // line 43
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_12 = ($context["block_config"] ?? null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["always-show-submenu"] ?? null) : null)) ? ("") : ("checked=\"checked\"")));
        echo " value=\"0\" name=\"tbm-always-show-submenu\" data-action=\"toggleAlwayShowSubmenu\" class=\"toolbox-toggle\" id=\"toggleAlwayShowSubmenu0\">
                <label for=\"toggleAlwayShowSubmenu0\" class=\"btn ";
        // line 44
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_13 = ($context["block_config"] ?? null)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["always-show-submenu"] ?? null) : null)) ? ("") : ("active btn-danger")));
        echo "\" title=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Collapse submenus when browsing on small screens."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("No"));
        echo "</label>
                <input type=\"radio\" ";
        // line 45
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_14 = ($context["block_config"] ?? null)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["always-show-submenu"] ?? null) : null)) ? ("checked=\"checked\"") : ("")));
        echo " value=\"1\" name=\"tbm-always-show-submenu\" data-action=\"toggleAlwayShowSubmenu\" class=\"toolbox-toggle\" id=\"toggleAlwayShowSubmenu1\">
                <label for=\"toggleAlwayShowSubmenu1\" class=\"btn ";
        // line 46
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($__internal_compile_15 = ($context["block_config"] ?? null)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["always-show-submenu"] ?? null) : null)) ? ("active btn-success") : ("")));
        echo "\" title=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Show submenus when browsing on small screens."));
        echo "\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Yes"));
        echo "</label>
              </fieldset>
            </div>
          </div>
        </div>
        ";
        // line 51
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["item_toolbox"] ?? null), 51, $this->source), "html", null, true);
        echo "
        ";
        // line 52
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["submenu_toolbox"] ?? null), 52, $this->source), "html", null, true);
        echo "
        ";
        // line 53
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["column_toolbox"] ?? null), 53, $this->source), "html", null, true);
        echo "
      </div>
      <div class=\"toolbox-actions-group\">
        <button class=\"btn btn-success toolbox-action toolbox-saveConfig\" data-action=\"saveConfig\">";
        // line 56
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Save"));
        echo "</button>
        <button class=\"btn btn-danger toolbox-action toolbox-resetConfig\" data-action=\"resetConfig\">";
        // line 57
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Reset"));
        echo "</button>
      </div>
      <div class=\"toolbox-links-groups\">
        <a href=\"";
        // line 60
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["edit_links"] ?? null), 60, $this->source), "html", null, true);
        echo "\" target=\"_blank\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Edit links"));
        echo "</a>
      </div>
    </div>
  </div>
  <div id=\"tbm-admin-mm-container\" class=\"clearfix\">
    ";
        // line 65
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["menu_content"] ?? null), 65, $this->source), "html", null, true);
        echo "
  </div> 
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/tb_megamenu/templates/backend/tb-megamenu-backend.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  250 => 65,  240 => 60,  234 => 57,  230 => 56,  224 => 53,  220 => 52,  216 => 51,  204 => 46,  200 => 45,  192 => 44,  188 => 43,  184 => 42,  176 => 41,  165 => 37,  161 => 36,  153 => 35,  149 => 34,  145 => 33,  137 => 32,  131 => 29,  123 => 28,  119 => 27,  114 => 25,  106 => 24,  102 => 23,  98 => 21,  85 => 19,  81 => 18,  72 => 16,  66 => 13,  58 => 12,  52 => 9,  48 => 8,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div id=\"tbm-admin\" class=\"hidden tbm-admin tbm\">
  <div class=\"admin-inline-toolbox clearfix\">
    <div class=\"tbm-admin-mm-row\">
      <div id=\"tbm-admin-mm-tb\">
        <div id=\"toolbox-loading\" class=\"toolbox-loading\">&nbsp;</div>
        <div id=\"toolbox-message\" class=\"toolbox-message\">&nbsp;</div>
        <div id=\"tbm-admin-mm-intro\" class=\"admin-toolbox\">
          <h3>{{ 'MegaMenu Toolbox'|t }}</h3>
          <p>{{ 'This toolbox includes global menu settings. Click on a menu item, submenu or column for additional configuration options.'|t }}</p>
          <div class=\"tbm-tools-container\">
            <div>
              <label aria-label=\"{{ 'Mobile breakpint'|t }} - {{ 'Specify a mobile breakpoint in pixels.'|t }}\" class=\"hasTip\">{{ 'Mobile breakpoint'|t }}</label>
              <input type=\"text\" class=\"input-medium tbm-breakpoint toolbox-input\" name=\"tbm-breakpoint\" data-name=\"breakpoint\" value=\"{{ block_config['breakpoint'] }}\" />
            </div>
            <div id=\"tbm-animation-wrapper\">
              <label aria-label=\"{{ 'Animation'|t }} - {{ 'Select an animation style for this menu.'|t }}\" class=\"hasTip\">{{ 'Animation'|t }}</label>
              <select name=\"tbm-animation\">
                {% for value, title in animation_options %}
                  <option value='{{ value }}' {{ block_config['animation'] == value ? 'selected' : '' }}>{{ title }}</option>
                {% endfor %}
              </select>
            </div>
            <div id=\"tbm-delay-wrapper\" style=\"display: {{ block_config['animation'] == 'none' ? 'none' : 'inline-block' }};\">
              <label aria-label=\"{{ 'Animation delay (ms)'|t }} - {{ 'Specify a delay for the animation - this field must be an integer.'|t }}\" class=\"hasTip\">{{ 'Animation delay (ms)'|t }}</label>
              <input class=\"input-medium toolitem-delay toolbox-input\" name=\"tbm-delay\" type=\"text\" value=\"{{ block_config['delay'] }}\" data-name=\"delay\"></input>
            </div>
            <div id=\"tbm-duration-wrapper\" style=\"display: {{ block_config['animation'] == 'none' ? 'none' : 'inline-block' }};\">
              <label aria-label=\"{{ 'Animation duration (ms)'|t }} - {{ 'Specify a duration for the animation - this field must be an integer.'|t }}\" class=\"hasTip\">{{ 'Animation duration (ms)'|t }}</label>
              <input class=\"input-medium toolitem-duration toolbox-input\" name=\"tbm-duration\" type=\"text\" value=\"{{ block_config['duration'] }}\" data-name=\"duration\"></input>
            </div>
            <div>
              <label class=\"hasTip\" aria-label=\"{{ 'Submenu arrow'|t }} - {{ 'Show or hide a clickable arrow next to top-level menu items that have a submenu. Clicking on the arrow reveals the submenu. Otherwise, hovering over a top-level menu item reveals the submenu.'|t }}\">{{ 'Submenu arrow'|t }}</label>
              <fieldset class=\"radio btn-group toolitem-auto-arrow\" data-auto-arrow=\"{{ block_config['auto-arrow'] }}\">
                <input type=\"radio\" {{ block_config['auto-arrow'] ? '' : 'checked=\"checked\"' }} value=\"0\" name=\"tbm-auto-arrow\" data-action=\"toggleAutoArrow\" class=\"toolbox-toggle\" id=\"toggleAutoArrow0\">
                <label for=\"toggleAutoArrow0\" class=\"btn {{ block_config['auto-arrow'] ? '' : 'active btn-danger' }}\" title=\"{{ 'Hide the arrow next to items that have a submenu.'|t }}\">{{ 'No'|t }}</label>
                <input type=\"radio\" {{ block_config['auto-arrow'] ? 'checked=\"checked\"' : '' }} value=\"1\" name=\"tbm-auto-arrow\" data-action=\"toggleAutoArrow\" class=\"toolbox-toggle\" id=\"toggleAutoArrow1\">
                <label for=\"toggleAutoArrow1\" class=\"btn {{ block_config['auto-arrow'] ? 'active btn-success' : '' }}\" title=\"{{ 'Show the arrow next to items that have a submenu.'|t }}\">{{ 'Yes'|t }}</label>
              </fieldset>        
            </div>
            <div>
              <label class=\"hasTip\" aria-label=\"{{ 'Show submenu on mobile'|t }} - {{ 'When set to \\'yes\\', submenus will always be visible on mobile. When set to \\'no\\', the user will have to click to reveal submenus on mobile.'|t }}\">{{ 'Show submenu on mobile'|t }}</label>
              <fieldset class=\"radio btn-group toolitem-always-show-submenu\" data-always-show-submenu=\"{{ block_config['always-show-submenu'] }}\">
                <input type=\"radio\" {{ block_config['always-show-submenu'] ? '' : 'checked=\"checked\"' }} value=\"0\" name=\"tbm-always-show-submenu\" data-action=\"toggleAlwayShowSubmenu\" class=\"toolbox-toggle\" id=\"toggleAlwayShowSubmenu0\">
                <label for=\"toggleAlwayShowSubmenu0\" class=\"btn {{ block_config['always-show-submenu'] ? '' : 'active btn-danger' }}\" title=\"{{ 'Collapse submenus when browsing on small screens.'|t }}\">{{ 'No'|t }}</label>
                <input type=\"radio\" {{ block_config['always-show-submenu'] ? 'checked=\"checked\"' : '' }} value=\"1\" name=\"tbm-always-show-submenu\" data-action=\"toggleAlwayShowSubmenu\" class=\"toolbox-toggle\" id=\"toggleAlwayShowSubmenu1\">
                <label for=\"toggleAlwayShowSubmenu1\" class=\"btn {{ block_config['always-show-submenu'] ? 'active btn-success' : '' }}\" title=\"{{ 'Show submenus when browsing on small screens.'|t }}\">{{ 'Yes'|t }}</label>
              </fieldset>
            </div>
          </div>
        </div>
        {{ item_toolbox }}
        {{ submenu_toolbox }}
        {{ column_toolbox }}
      </div>
      <div class=\"toolbox-actions-group\">
        <button class=\"btn btn-success toolbox-action toolbox-saveConfig\" data-action=\"saveConfig\">{{ 'Save'|t }}</button>
        <button class=\"btn btn-danger toolbox-action toolbox-resetConfig\" data-action=\"resetConfig\">{{ 'Reset'|t }}</button>
      </div>
      <div class=\"toolbox-links-groups\">
        <a href=\"{{ edit_links }}\" target=\"_blank\">{{ 'Edit links'|t }}</a>
      </div>
    </div>
  </div>
  <div id=\"tbm-admin-mm-container\" class=\"clearfix\">
    {{ menu_content }}
  </div> 
</div>
", "modules/contrib/tb_megamenu/templates/backend/tb-megamenu-backend.html.twig", "/Users/jtoomer/Sites/nass/nass_www/web/modules/contrib/tb_megamenu/templates/backend/tb-megamenu-backend.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 18);
        static $filters = array("t" => 8, "escape" => 13);
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
