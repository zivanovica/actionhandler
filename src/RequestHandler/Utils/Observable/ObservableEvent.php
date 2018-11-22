<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 11/1/2018
 * Time: 4:35 AM
 */

namespace RequestHandler\Utils\Observable;

interface ObservableEvent
{
    /** @var int */
    public const EVENT_MODIFIED = 0;

    /** @var int */
    public const EVENT_ACCESSED = 1;
}