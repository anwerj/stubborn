<?php

namespace Stubborn\Lib\Ext;

use Stubborn\Lib\Error;

trait Actions
{
    protected function actions($pointer = 'actions')
    {
        $actions = $this->schema->root->data()[$pointer] ?? null;

        if ($actions === null)
        {
            Error::report('Actions not defined', [$this->schema->root->name()]);
        }

        return $actions;
    }

    public function actionIterator(string $method, array $except = []): Str
    {
        $output = [];

        foreach (array_diff($this->actions(), $except) as $action)
        {
            $output[] = $this->{$method}($action, []);
        }

        return $this->lines(implode('', $output));
    }

    public function actionPadding(int $preFixLength = 0)
    {
        $maxLength = $this->actionPaddingLength();

        $padding = intval(ceil(($maxLength+$preFixLength + 1)/4)) * 4;

        return $padding;
    }

    public function actionPaddingLength()
    {
        return max(array_map('strlen', $this->actions()));
    }
}
