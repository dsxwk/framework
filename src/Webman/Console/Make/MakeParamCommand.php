<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Webman\Console\Make;

use Dsxwk\Framework\Webman\Console\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Webman\Console\Util;

#[AsCommand('make:param', 'Make param')]
class MakeParamCommand extends BaseCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Param name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $output->writeln("Make param $name");
        $suffix = config('app.param_suffix', '');

        if ($suffix && !strpos($name, $suffix)) {
            $name .= $suffix;
        }

        $name = str_replace('\\', '/', $name);
        if (!($pos = strrpos($name, '/'))) {
            $name      = ucfirst($name);
            $param_str = Util::guessPath(app_path(), 'param') ?: 'param';
            $file      = app_path() . DIRECTORY_SEPARATOR . $param_str . DIRECTORY_SEPARATOR . "$name.php";
            $namespace = $param_str === 'Param' ? 'App\Param' : 'app\param';
        } else {
            $name_str = substr($name, 0, $pos);
            if ($real_name_str = Util::guessPath(app_path(), $name_str)) {
                $name_str = $real_name_str;
            } else if ($real_section_name = Util::guessPath(app_path(), strstr($name_str, '/', true))) {
                $upper = strtolower($real_section_name[0]) !== $real_section_name[0];
            } else if ($real_base_param = Util::guessPath(app_path(), 'param')) {
                $upper = strtolower($real_base_param[0]) !== $real_base_param[0];
            }
            $upper = $upper ?? strtolower($name_str[0]) !== $name_str[0];
            if ($upper && !$real_name_str) {
                $name_str = preg_replace_callback(
                    '/\/([a-z])/',
                    function ($matches) {
                        return '/' . strtoupper($matches[1]);
                    },
                    ucfirst($name_str)
                );
            }
            $path      = "$name_str/" . ($upper ? 'Param' : 'param');
            $name      = ucfirst(substr($name, $pos + 1));
            $file      = app_path() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . "$name.php";
            $namespace = str_replace('/', '\\', ($upper ? 'App/' : 'app/') . $path);
        }

        $table      = str_replace('_param', '', Util::classToName($name));
        $database   = config(sprintf('database.connections.%s.database', config('database.default')));
        $properties = '';
        $format     = '    ';
        // MySQL 列信息
        foreach ($this->db::connection()->select(
            "select COLUMN_NAME,DATA_TYPE,COLUMN_KEY,COLUMN_COMMENT from INFORMATION_SCHEMA.COLUMNS where table_name = '$table' and table_schema = '$database' ORDER BY ordinal_position"
        ) as $item) {
            if ($item->COLUMN_KEY !== 'PRI') {
                $type       = $this->getType($item->DATA_TYPE) === 'integer' ? 'int' : $this->getType($item->DATA_TYPE);
                $properties .= "{$format}/**\n{$format} * {$item->COLUMN_COMMENT}\n{$format} * \n{$format} * @var {$type}\n{$format} */\n{$format}public {$type} \${$item->COLUMN_NAME};\n\n";
            }
        }

        if (is_file($file)) {
            $helper   = $this->getHelper('question');
            $question = new ConfirmationQuestion("$file already exists. Do you want to override it? (yes/no)", false);
            if (!$helper->ask($input, $output, $question)) {
                return self::SUCCESS;
            }
        }

        $this->createParam($name, $namespace, rtrim($properties, "\n"), $file);

        return self::SUCCESS;
    }

    /**
     * @param $name
     * @param $namespace
     * @param $properties
     * @param $file
     *
     * @return void
     */
    protected function createParam($name, $namespace, $properties, $file): void
    {
        $path = pathinfo($file, PATHINFO_DIRNAME);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $paramContent = <<<EOF
<?php

declare(strict_types=1);

namespace $namespace;

use Dsxwk\Framework\Param\BaseParam;

class $name extends BaseParam
{
$properties
}

EOF;
        file_put_contents($file, $paramContent);
    }

}
