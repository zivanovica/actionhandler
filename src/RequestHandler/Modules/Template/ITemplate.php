<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 10/2/2018
 * Time: 12:50 AM
 */

namespace RequestHandler\Modules\Template;

interface ITemplate
{
    /**
     * Set content of section, callback return value will be used as content
     *
     * @param string $sectionName Section that will contain provided content
     * @param callable $renderCallback Function whos retrun value will be used as section content
     */
    public function sectionContent(string $sectionName, callable $renderCallback): void;

    /**
     * Sets data for single section
     *
     * @param string $sectionName Name of section to which data applies
     * @param array $data Data that will be applied to section
     * @param bool $strict Flag that determines should application fail if section doesn't exists
     */
    public function setSectionData(string $sectionName, array $data, bool $strict = false): void;

    /**
     * Print section contents
     *
     * @param string $sectionName Name of section that will be printed out
     * @param bool $strict Flag that determines should application fail if section doesn't have any contents
     */
    public function renderSection(string $sectionName, bool $strict = false): void;

    /**
     * Include partial into current template
     *
     * @param string $partialPath Path to file that is treated as partial include of an template
     * @param bool $requireOnce
     */
    public function partial(string $partialPath, bool $requireOnce = true): void;

    /**
     * Include multiple partials into current template
     *
     * @param array $paths Array of paths to files that are treated as partial include of an template
     */

    public function partials(array $paths): void;
    /**
     * Set current template base
     *
     * @param string $baseTemplate Path to valid base template
     */
    public function setBase(string $baseTemplate): void;

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
    public function render(string $templatePath, array $data, bool $dataPerSection = false): string;
}