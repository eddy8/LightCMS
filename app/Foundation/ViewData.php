<?php
/**
 * 视图数据类，用于相关事件中传递自定义视图数据
 */

namespace App\Foundation;

class ViewData
{
    /**
     * @var array css文件url
     */
    protected array $css;

    /**
     * @var array js文件url
     */
    protected array $js;

    /**
     * @var array 包含的模板文件
     */
    protected array $includeTemplate;

    public function __construct(array $js = [], array $css = [], array $includeTemplate = [])
    {
        $this->js = $js;
        $this->css = $css;
        $this->includeTemplate = $includeTemplate;
    }

    public function addJs(string $js)
    {
        array_push($this->js, $js);
    }

    public function addCss(string $css)
    {
        array_push($this->css, $css);
    }

    public function addTemplate(string $template)
    {
        array_push($this->includeTemplate, $template);
    }

    /**
     * @return array
     */
    public function getCss(): array
    {
        return $this->css;
    }

    /**
     * @return array
     */
    public function getJs(): array
    {
        return $this->js;
    }

    /**
     * @return array
     */
    public function getIncludeTemplate(): array
    {
        return $this->includeTemplate;
    }
}
