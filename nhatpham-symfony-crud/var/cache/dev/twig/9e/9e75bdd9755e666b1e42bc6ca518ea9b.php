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

/* main/index.html.twig */
class __TwigTemplate_9b74dca1a68678364e9b42ca9001a79a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
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
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "main/index.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "main/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        echo "Hello MainController!";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 6
        echo "<div class='mt-9'>
<button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#createSv\">
<i class=\"fa-solid fa-plus\"></i>
Tạo mới
</button>
<table class=\"table\">
  <thead>
    <tr>
      <th scope=\"col\">ID</th>
      <th scope=\"col\">MSSV</th>
      <th scope=\"col\">Họ và tên</th>
      <th scope=\"col\">Lớp</th>
      <th scope=\"col\">Action</th>
    </tr>
  </thead>
  <tbody>

    ";
        // line 23
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["listSv"]) || array_key_exists("listSv", $context) ? $context["listSv"] : (function () { throw new RuntimeError('Variable "listSv" does not exist.', 23, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["sv"]) {
            // line 24
            echo "    <tr>
      <th scope=\"row\">";
            // line 25
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["sv"], "id", [], "any", false, false, false, 25), "html", null, true);
            echo "</th>
      <td>";
            // line 26
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["sv"], "masv", [], "any", false, false, false, 26), "html", null, true);
            echo "</td>
      <td>";
            // line 27
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["sv"], "hoten", [], "any", false, false, false, 27), "html", null, true);
            echo "</td>
      <td>";
            // line 28
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["sv"], "lop", [], "any", false, false, false, 28), "html", null, true);
            echo "</td>
      <td> 
      <a href='";
            // line 30
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("detail", ["id" => twig_get_attribute($this->env, $this->source, $context["sv"], "id", [], "any", false, false, false, 30)]), "html", null, true);
            echo "' type=\"button\" class=\"btn btn-light btn-sm\"  >
      <i class=\"fa-solid fa-pen\"></i>
        </a>
          <a href='";
            // line 33
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("delete", ["id" => twig_get_attribute($this->env, $this->source, $context["sv"], "id", [], "any", false, false, false, 33)]), "html", null, true);
            echo "' type=\"button\" class=\"btn btn-light btn-sm\"  >
                  <i class=\"fa-solid fa-trash\"></i>
        </a>
     
        </td>
    </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sv'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 40
        echo "  </tbody>
</table>



<!-- Modal create -->
<div class=\"modal fade\" id=\"createSv\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title\" id=\"exampleModalLabel\">Thêm sinh viên mới</h5>
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
      </div>
     ";
        // line 53
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 53, $this->source); })()), 'form_start');
        echo "
      <div class=\"modal-body\">    
            <div class='form-group'>
            ";
        // line 56
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 56, $this->source); })()), "masv", [], "any", false, false, false, 56), 'label');
        echo "
            ";
        // line 57
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 57, $this->source); })()), "masv", [], "any", false, false, false, 57), 'widget', ["attr" => ["class" => "form-control"]]);
        echo "
            </div>
               <div class='form-group'>
            ";
        // line 60
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 60, $this->source); })()), "hoten", [], "any", false, false, false, 60), 'label');
        echo "
            ";
        // line 61
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 61, $this->source); })()), "hoten", [], "any", false, false, false, 61), 'widget', ["attr" => ["class" => "form-control", "id" => "floatingInput"]]);
        echo "
            </div>
               <div class='form-group'>
            ";
        // line 64
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 64, $this->source); })()), "lop", [], "any", false, false, false, 64), 'label');
        echo "
            ";
        // line 65
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 65, $this->source); })()), "lop", [], "any", false, false, false, 65), 'widget', ["attr" => ["class" => "form-control"]]);
        echo "
            </div>
      </div>
      <div class=\"modal-footer\">
        <button type=\"submit\" class=\"btn btn-primary\">Lưu</button>
        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Huỷ</button>
      </div>
        ";
        // line 72
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["formCreate"]) || array_key_exists("formCreate", $context) ? $context["formCreate"] : (function () { throw new RuntimeError('Variable "formCreate" does not exist.', 72, $this->source); })()), 'form_end');
        echo "

    </div>
  </div>
</div>

";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "main/index.html.twig";
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
        return array (  190 => 72,  180 => 65,  176 => 64,  170 => 61,  166 => 60,  160 => 57,  156 => 56,  150 => 53,  135 => 40,  122 => 33,  116 => 30,  111 => 28,  107 => 27,  103 => 26,  99 => 25,  96 => 24,  92 => 23,  73 => 6,  66 => 5,  53 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Hello MainController!{% endblock %}

{% block body %}
<div class='mt-9'>
<button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#createSv\">
<i class=\"fa-solid fa-plus\"></i>
Tạo mới
</button>
<table class=\"table\">
  <thead>
    <tr>
      <th scope=\"col\">ID</th>
      <th scope=\"col\">MSSV</th>
      <th scope=\"col\">Họ và tên</th>
      <th scope=\"col\">Lớp</th>
      <th scope=\"col\">Action</th>
    </tr>
  </thead>
  <tbody>

    {% for sv in listSv %}
    <tr>
      <th scope=\"row\">{{sv.id}}</th>
      <td>{{sv.masv}}</td>
      <td>{{sv.hoten}}</td>
      <td>{{sv.lop}}</td>
      <td> 
      <a href='{{path('detail',{id:sv.id})}}' type=\"button\" class=\"btn btn-light btn-sm\"  >
      <i class=\"fa-solid fa-pen\"></i>
        </a>
          <a href='{{path('delete',{id:sv.id})}}' type=\"button\" class=\"btn btn-light btn-sm\"  >
                  <i class=\"fa-solid fa-trash\"></i>
        </a>
     
        </td>
    </tr>
    {% endfor %}
  </tbody>
</table>



<!-- Modal create -->
<div class=\"modal fade\" id=\"createSv\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title\" id=\"exampleModalLabel\">Thêm sinh viên mới</h5>
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
      </div>
     {{ form_start(formCreate) }}
      <div class=\"modal-body\">    
            <div class='form-group'>
            {{ form_label(formCreate.masv) }}
            {{ form_widget(formCreate.masv,{'attr':{'class':'form-control'}}) }}
            </div>
               <div class='form-group'>
            {{ form_label(formCreate.hoten) }}
            {{ form_widget(formCreate.hoten,{'attr':{'class':'form-control','id':'floatingInput'}}) }}
            </div>
               <div class='form-group'>
            {{ form_label(formCreate.lop) }}
            {{ form_widget(formCreate.lop,{'attr':{'class':'form-control'}}) }}
            </div>
      </div>
      <div class=\"modal-footer\">
        <button type=\"submit\" class=\"btn btn-primary\">Lưu</button>
        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Huỷ</button>
      </div>
        {{ form_end(formCreate) }}

    </div>
  </div>
</div>

{% endblock %}
", "main/index.html.twig", "/Users/nhatpham/Desktop/crud_app_sym/templates/main/index.html.twig");
    }
}
