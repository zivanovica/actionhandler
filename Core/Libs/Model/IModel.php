<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 12:42 PM
 */

namespace Core\Libs\Model;


interface IModel
{

    public function table(): string;

    public function primary(): string;

    public function fields(): array;
}