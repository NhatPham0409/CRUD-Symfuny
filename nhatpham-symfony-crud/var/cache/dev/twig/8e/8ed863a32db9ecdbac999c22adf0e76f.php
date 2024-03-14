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

/* main/detail.html.twig */
class __TwigTemplate_b20e8fa189c2418ce829aaa3c8aaf5f8 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "main/detail.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "main/detail.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 4
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 5
        echo " 
 <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title\" id=\"updateSv\">Chỉnh sửa thông tin sinh viên</h5>
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
      </div>
     ";
        // line 11
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 11, $this->source); })()), 'form_start');
        echo "
      <div class=\"modal-body\">    
            <div class='form-group'>
            ";
        // line 14
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 14, $this->source); })()), "masv", [], "any", false, false, false, 14), 'label');
        echo "
            ";
        // line 15
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 15, $this->source); })()), "masv", [], "any", false, false, false, 15), 'widget', ["attr" => ["class" => "form-control"]]);
        echo "
            </div>
               <div class='form-group'>
            ";
        // line 18
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 18, $this->source); })()), "hoten", [], "any", false, false, false, 18), 'label');
        echo "
            ";
        // line 19
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 19, $this->source); })()), "hoten", [], "any", false, false, false, 19), 'widget', ["attr" => ["class" => "form-control", "id" => "floatingInput"]]);
        echo "
            </div>
               <div class='form-group'>
            ";
        // line 22
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 22, $this->source); })()), "lop", [], "any", false, false, false, 22), 'label');
        echo "
            ";
        // line 23
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 23, $this->source); })()), "lop", [], "any", false, false, false, 23), 'widget', ["attr" => ["class" => "form-control"]]);
        echo "
            </div>
      </div>
      <div class=\"modal-footer\">
        <button type=\"submit\" class=\"btn btn-primary\">Lưu</button>
        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Huỷ</button>
      </div>
        ";
        // line 30
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["formUpdate"]) || array_key_exists("formUpdate", $context) ? $context["formUpdate"] : (function () { throw new RuntimeError('Variable "formUpdate" does not exist.', 30, $this->source); })()), 'form_end');
        echo " 
    </div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "main/detail.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  107 => 30,  97 => 23,  93 => 22,  87 => 19,  83 => 18,  77 => 15,  73 => 14,  67 => 11,  59 => 5,  52 => 4,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source(" {% extends 'base.html.twig' %}


{% block body %}
 
 <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title\" id=\"updateSv\">Chỉnh sửa thông tin sinh viên</h5>
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
      </div>
     {{ form_start(formUpdate) }}
      <div class=\"modal-body\">    
            <div class='form-group'>
            {{ form_label(formUpdate.masv) }}
            {{ form_widget(formUpdate.masv,{'attr':{'class':'form-control'}}) }}
            </div>
               <div class='form-group'>
            {{ form_label(formUpdate.hoten) }}
            {{ form_widget(formUpdate.hoten,{'attr':{'class':'form-control','id':'floatingInput'}}) }}
            </div>
               <div class='form-group'>
            {{ form_label(formUpdate.lop) }}
            {{ form_widget(formUpdate.lop,{'attr':{'class':'form-control'}}) }}
            </div>
      </div>
      <div class=\"modal-footer\">
        <button type=\"submit\" class=\"btn btn-primary\">Lưu</button>
        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Huỷ</button>
      </div>
        {{ form_end(formUpdate) }} 
    </div>
{% endblock %}
", "main/detail.html.twig", "/Users/nhatpham/Desktop/crud_app_sym/templates/main/detail.html.twig");
    }
}
