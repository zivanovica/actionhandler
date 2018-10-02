<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 10/2/2018
 * Time: 12:50 AM
 */

namespace RequestHandler\Modules\Template;

/**
 * Very simple and easy to use PHP template engine.
 * Instead of working with fancy custom syntax, this engine relies on pure php and html
 *
 * TODO: Use proper exceptions
 */
class Template implements ITemplate
{

    /**
     * @var array All existing sections for current template
     */
    private $sections = [];

    /**
     * @var array Render content (HTML) for sections in current template
     */
    private $sectionsContent = [];

    /**
     * @var array Data for each section
     */
    private $sectionData = [];

    /**
     * @var string Base templated used for currently rendering template
     */
    private $baseTemplate = null;

    /**
     * Set content of section, callback return value will be used as content
     *
     * @param string $sectionName Section that will contain provided content
     * @param callable $renderCallback Function whos retrun value will be used as section content
     */
    public function section(string $sectionName, callable $renderCallback): void
    {
        if (empty($this->sections[$sectionName])) {
            $this->sections[$sectionName] = [];
        }

        $this->sections[$sectionName][] = $renderCallback;
    }

    /**
     * Sets data for single section
     *
     * @param string $sectionName Name of section to which data applies
     * @param array $data Data that will be applied to section
     * @param bool $strict Flag that determines should application fail if section doesn't exists
     */
    public function setSectionData(string $sectionName, array $data, bool $strict = false): void
    {
        $section = $this->sections[$sectionName] ?? [];

        if (empty($section) && $strict) {
            throw new \RuntimeException("Invalid section '{$sectionName}'");
        }

        $this->sectionData[$sectionName] = $data;
    }

    /**
     * Print section contents
     *
     * @param string $sectionName Name of section that will be printed out
     * @param bool $strict Flag that determines should application fail if section doesn't have any contents
     */
    public function renderSection(string $sectionName, bool $strict = false): void
    {
        if (false === isset($this->sectionsContent[$sectionName]) && $strict) {
            throw new \RuntimeException("Invalid section '{$sectionName}'");
        }

        echo $this->sectionsContent[$sectionName] ?? '';
    }

    /**
     * Include partial into current template
     *
     * @param string $partialPath Path to file that is treated as partial include of an template
     * @param bool $requireOnce
     */
    public function partial(string $partialPath, bool $requireOnce = true): void
    {
        if (false === is_readable($partialPath)) {
            throw new \RuntimeException("Unable to read partial '{$partialPath}'");
        }

        $template = $this;

        $requireOnce ? require_once $partialPath : require $partialPath;
    }

    /**
     * Include multiple partials into current template
     *
     * @param array $paths Array of paths to files that are treated as partial include of an template
     */

    public function partials(array $paths): void
    {
        foreach ($paths as $path) {
            self::partial($path);
        }
    }

    /**
     * Set current template base
     *
     * @param string $baseTemplate Path to valid base template
     */
    public function setBase(string $baseTemplate): void
    {
        if (false === is_readable($baseTemplate)) {
            throw new \RuntimeException("Cannot read file '{$baseTemplate}'");
        }

        $this->baseTemplate = $baseTemplate;
    }

    /**
     *
     * Render single template into a string
     *
     * @param string $templatePath
     * @param array $data
     * @param bool $dataPerSection
     * @return string
     * @throws \ReflectionException
     */
    public function render(string $templatePath, array $data, bool $dataPerSection = false): string
    {
        if (false === is_readable($templatePath)) {
            throw new \RuntimeException("Unable to read template '{$templatePath}'");
        }

        $template = $this;

        require_once $templatePath;

        if (null === $this->baseTemplate) {
            throw new \RuntimeException('Base Template not defined');
        }

        ob_start();

        $sections = $this->sections;

        foreach ($sections as $sectionName => $renderCallbacks) {
            self::setSectionData($sectionName, $dataPerSection ? ($data[$sectionName] ?? []) : $data);

            self::executeRenderCallbacks($sectionName, $renderCallbacks);
        }

        require_once $this->baseTemplate;

        $html = ob_get_contents();

        ob_end_clean();

        return $html;
    }

    /**
     *
     * Execute all section callbacks with proper arguments passed
     *
     * @param string $sectionName
     * @param array $callbacks
     * @throws \ReflectionException
     */
    private function executeRenderCallbacks(string $sectionName, array $callbacks)
    {
        $content = '';

        foreach ($callbacks as $callback) {
            $content .= self::invokeSectionCallback($sectionName, $callback);
        }

        $this->sectionsContent[$sectionName] = $content;
    }

    /**
     *
     * Execute single section callback with proper arguments passed
     *
     * @param string $sectionName
     * @param callable $callback
     * @return string
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    private function invokeSectionCallback(string $sectionName, callable $callback): string
    {
        $reflection = new \ReflectionFunction($callback);

        $parameters = [];

        foreach ($reflection->getParameters() as $parameter) {
            $parameterName = $parameter->getName();

            if (false === isset($this->sectionData[$sectionName][$parameterName])) {
                throw new \RuntimeException("Missing parameter '{$parameterName}' for section '{$sectionName}' callback");
            }

            $parameters[] = $this->sectionData[$sectionName][$parameterName];
        }

        ob_start();

        $reflection->invokeArgs($parameters);

        $html = ob_get_contents();

        ob_clean();

        return $html;
    }
}