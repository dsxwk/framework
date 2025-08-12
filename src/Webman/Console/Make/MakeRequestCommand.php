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

#[AsCommand('make:request', 'Make request')]
class MakeRequestCommand extends BaseCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Request name');
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
        $output->writeln("Make request $name");
        $suffix = config('app.request_suffix', '');

        if ($suffix && !strpos($name, $suffix)) {
            $name .= $suffix;
        }

        $name = str_replace('\\', '/', $name);
        if (!($pos = strrpos($name, '/'))) {
            $name        = ucfirst($name);
            $request_str = Util::guessPath(app_path(), 'request') ?: 'request';
            $file        = app_path() . DIRECTORY_SEPARATOR . $request_str . DIRECTORY_SEPARATOR . "$name.php";
            $namespace   = $request_str === 'Request' ? 'App\Request' : 'app\request';
        } else {
            $name_str = substr($name, 0, $pos);
            if ($real_name_str = Util::guessPath(app_path(), $name_str)) {
                $name_str = $real_name_str;
            } else if ($real_section_name = Util::guessPath(app_path(), strstr($name_str, '/', true))) {
                $upper = strtolower($real_section_name[0]) !== $real_section_name[0];
            } else if ($real_base_request = Util::guessPath(app_path(), 'request')) {
                $upper = strtolower($real_base_request[0]) !== $real_base_request[0];
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
            $path      = "$name_str/" . ($upper ? 'Request' : 'request');
            $name      = ucfirst(substr($name, $pos + 1));
            $file      = app_path() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . "$name.php";
            $namespace = str_replace('/', '\\', ($upper ? 'App/' : 'app/') . $path);
        }

        if (is_file($file)) {
            $helper   = $this->getHelper('question');
            $question = new ConfirmationQuestion("$file already exists. Do you want to override it? (yes/no)", false);
            if (!$helper->ask($input, $output, $question)) {
                return self::SUCCESS;
            }
        }

        $this->createRequest($name, $namespace, $file);

        return self::SUCCESS;
    }

    /**
     * @param $name
     * @param $namespace
     * @param $file
     *
     * @return void
     */
    protected function createRequest($name, $namespace, $file): void
    {
        $path = pathinfo($file, PATHINFO_DIRNAME);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $requestContent = <<<EOF
<?php

declare(strict_types=1);

namespace $namespace;

use Dsxwk\Framework\Webman\Validate\Think\BaseRequest;

class $name extends BaseRequest
{
    /**
     * 自定义验证场景规则
     *
     * @return array
     */
    protected function sceneRules(): array
    {
        return [
            'create' => [],
        ];
    }

    /**
     * 验证字段描述
     *
     * @var array
     */
    protected \$field   = [];

    /**
     * 验证提示信息
     *
     * @var array
     */
    protected \$message = [];
}

EOF;
        file_put_contents($file, $requestContent);
    }

}
