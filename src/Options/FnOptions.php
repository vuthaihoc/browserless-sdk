<?php

namespace DokLibs\Browserless\Options;

class FnOptions extends CommonOptions
{
    public static function code($code): static
    {
        $options = new self();
        $options->setOption('code', $code);
        return $options;
    }

    public static function codeV1($main_code): static
    {
        $code = "module.exports = async ({ page, context }) => {\n";
        $code .= $main_code;
        $code .= "\n}";
        return self::code($code);
    }

    public static function codeV2($main_code): static
    {
        $code = "export default async function ({ page, context }) {\n";
        $code .= $main_code;
        $code .= "\n}";
        return self::code($code);
    }

    public function context(array $context){
        return $this->setOption('context', $context);
    }

}