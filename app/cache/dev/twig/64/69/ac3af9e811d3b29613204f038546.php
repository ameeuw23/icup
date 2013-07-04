<?php

/* ICupPublicSiteBundle:Default:playground.html.twig */
class __TwigTemplate_6469ac3af9e811d3b29613204f038546 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("base.html.twig");

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "<a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_showcategory", array("categoryid" => $this->getAttribute($this->getContext($context, "category"), "id"))), "html", null, true);
        echo "\">
    <h1 class=\"category\">";
        // line 5
        echo $this->env->getExtension('translator')->getTranslator()->trans("CATEGORY", array(), "messages");
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "category"), "name"), "html", null, true);
        echo "</h1>
</a>
<p class=\"subcategory\">
    ";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans(twig_join_filter(array(0 => $this->getAttribute($this->getContext($context, "category"), "gender"), 1 => $this->getAttribute($this->getContext($context, "category"), "classification")))), "html", null, true);
        echo "
    ";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "category"), "classification")), "html", null, true);
        echo "
</p>
<a href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_showcategory", array("categoryid" => $this->getAttribute($this->getContext($context, "category"), "id"))), "html", null, true);
        echo "\">
    <h2 class=\"group\">";
        // line 12
        echo $this->env->getExtension('translator')->getTranslator()->trans("GROUP", array(), "messages");
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "group"), "name"), "html", null, true);
        echo "</h2>
</a>
<p class=\"subgroup\">";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "playground"), "name"), "html", null, true);
        echo "</p>
<p>
    <table>
        <thead>
            <tr>
                <td class=\"flag\"></td>
                <td class=\"no\">";
        // line 20
        echo $this->env->getExtension('translator')->getTranslator()->trans("LEGEND.NO", array(), "messages");
        echo "</td>
                <td class=\"time\">";
        // line 21
        echo $this->env->getExtension('translator')->getTranslator()->trans("LEGEND.TIME", array(), "messages");
        echo "</td>
                <td class=\"flag\"></td>
                <td class=\"team\">";
        // line 23
        echo $this->env->getExtension('translator')->getTranslator()->trans("TEAM", array(), "messages");
        echo " A</td>
                <td class=\"flag\"></td>
                <td class=\"team\">";
        // line 25
        echo $this->env->getExtension('translator')->getTranslator()->trans("TEAM", array(), "messages");
        echo " B</td>
                <td class=\"match\">";
        // line 26
        echo $this->env->getExtension('translator')->getTranslator()->trans("LEGEND.RESULT", array(), "messages");
        echo "</td>
            </tr>
        </thead>
        <tboby>
";
        // line 30
        $context["catdate"] = "";
        // line 31
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "matchlist"));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["match"]) {
            // line 32
            echo "    ";
            if ((twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "schedule"), "d-M-Y") != $this->getContext($context, "catdate"))) {
                // line 33
                echo "            <tr class=\"";
                echo twig_escape_filter($this->env, twig_cycle(array(0 => "even", 1 => "odd"), $this->getAttribute($this->getContext($context, "loop"), "index")), "html", null, true);
                echo "\">
                <td class=\"date_solo\" colspan=\"8\">
                    ";
                // line 35
                echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $this->env->getExtension('translator')->trans(twig_join_filter(array(0 => "WEEK.", 1 => twig_upper_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "schedule"), "D")))))), "html", null, true);
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "schedule"), " j. "), "html", null, true);
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans(twig_join_filter(array(0 => "MONTH.", 1 => twig_upper_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "schedule"), "M"))))), "html", null, true);
                echo "
                </td>
            </tr>
        ";
                // line 38
                $context["catdate"] = twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "schedule"), "d-M-Y");
                // line 39
                echo "    ";
            }
            // line 40
            echo "            <tr class=\"";
            echo twig_escape_filter($this->env, twig_cycle(array(0 => "even", 1 => "odd"), $this->getAttribute($this->getContext($context, "loop"), "index")), "html", null, true);
            echo "\">
                <td class=\"flag\"></td>
                <td class=\"no\">";
            // line 42
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "matchno"), "html", null, true);
            echo "</td>
                <td class=\"time\">";
            // line 43
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "schedule"), "H.i"), "html", null, true);
            echo "</td>
                <td class=\"flag\"><img src=\"";
            // line 44
            echo twig_escape_filter($this->env, $this->getContext($context, "imagepath"), "html", null, true);
            echo "/flags/";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "flagA"), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "match"), "countryA")), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "match"), "countryA")), "html", null, true);
            echo "\"></td>
                <td class=\"team\"><a href=\"";
            // line 45
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_showteam", array("teamid" => $this->getAttribute($this->getContext($context, "match"), "idA"), "groupid" => $this->getAttribute($this->getContext($context, "group"), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "teamA"), "html", null, true);
            echo "</a></td>
                <td class=\"flag\"><img src=\"";
            // line 46
            echo twig_escape_filter($this->env, $this->getContext($context, "imagepath"), "html", null, true);
            echo "/flags/";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "flagB"), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "match"), "countryB")), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getAttribute($this->getContext($context, "match"), "countryB")), "html", null, true);
            echo "\"></td>
                <td class=\"team\"><a href=\"";
            // line 47
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_showteam", array("teamid" => $this->getAttribute($this->getContext($context, "match"), "idB"), "groupid" => $this->getAttribute($this->getContext($context, "group"), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "teamB"), "html", null, true);
            echo "</a></td>
                <td class=\"match\">";
            // line 48
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "scoreA"), "html", null, true);
            echo "-";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "match"), "scoreB"), "html", null, true);
            echo "</td>
            </tr>
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['match'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 51
        echo "        </tboby>
        <tfoot>
            <tr>
                <td colspan=\"8\"></td>
            </tr>
        </tfoot>
    </table>
</p>
";
    }

    public function getTemplateName()
    {
        return "ICupPublicSiteBundle:Default:playground.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  204 => 51,  185 => 48,  179 => 47,  169 => 46,  163 => 45,  153 => 44,  149 => 43,  145 => 42,  139 => 40,  136 => 39,  134 => 38,  126 => 35,  120 => 33,  117 => 32,  100 => 31,  98 => 30,  91 => 26,  87 => 25,  82 => 23,  77 => 21,  73 => 20,  64 => 14,  57 => 12,  53 => 11,  48 => 9,  44 => 8,  36 => 5,  31 => 4,  28 => 3,);
    }
}