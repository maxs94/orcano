<?PHP 
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\DataObject\ScriptResultDataObject;
use App\Message\CheckNotification;
use App\Service\Scripts\ResultParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;

class ScriptRunnerService 
{
    private const PROCESS_MAX_RUNTIME_SECONDS = 15;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly ResultParserService $resultParserService,
        private readonly LoggerInterface $logger
    ) { }

    public function runScript(CheckNotification $message): ScriptResultDataObject
    {
        $startTime = microtime(true);

        $result = new ScriptResultDataObject();
        $result->setCheckResult(ScriptResultDataObject::RESULT_UNKNOWN);

        $scriptPath = $this->parameterBag->get('kernel.project_dir') . '/' . $message->getCheckScriptFilename();
        if (!file_exists($scriptPath)) {
            $errorMessage = sprintf('Script %s does not exist.', $scriptPath);
            $result->setNote($errorMessage);
            $this->logger->error($errorMessage);

            return $result;
        }

        $process = $this->runProcess($scriptPath, $message);
        $result->setExecutedCommand($process->getCommandLine());

        if (!$process->isSuccessful()) {
            $errorMessage = sprintf('Script %s failed with error: %s.', $scriptPath, $process->getErrorOutput());
            $result->setNote($errorMessage);
            $result->setCheckResult(ScriptResultDataObject::RESULT_ERROR);
            $result->setDurationMs($this->getScriptRuntimeInMs($startTime));
            $this->logger->error($errorMessage);

            return $result;
        }

        $result->setRawScriptOutput($process->getOutput());

        try {
            $jsonResponse = $this->resultParserService->extractJson($result->getRawScriptOutput());
            $result->setScriptOutput($jsonResponse);
        } catch (\Exception $e) {
            $errorMessage = sprintf('Script %s failed with error: %s.', $scriptPath, $e->getMessage());
            $result->setNote($errorMessage);
            $result->setCheckResult(ScriptResultDataObject::RESULT_ERROR);
            $result->setDurationMs($this->getScriptRuntimeInMs($startTime));
            $this->logger->error($errorMessage);
        }

        $result->setDurationMs($this->getScriptRuntimeInMs($startTime));

        return $result;
    }

    private function getScriptRuntimeInMs(float $startTimeMs): float 
    {
        return (microtime(true) - $startTimeMs) * 1000;
    }

    private function runProcess(string $scriptPath, CheckNotification $message): Process
    {
        $command = sprintf('%s \'%s\'',
            $scriptPath,
            $this->createJsonArguments($message),
        );

        $this->logger->notice(sprintf('CMD: %s', $command));

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(self::PROCESS_MAX_RUNTIME_SECONDS);
        $process->run();

        return $process;
    }

    private function createJsonArguments(CheckNotification $message): string
    {
        $arguments = [
            'hostname' => $message->getHostname(),
            'ipv4' => $message->getIpv4Address(),
            'ipv6' => $message->getIpv6Address()
        ];

        $config = $message->getConfig();
        if (isset($config['checkScriptParameter']) && !empty($config['checkScriptParameter'])) {
            $arguments = array_merge($arguments, $config['checkScriptParameter']);
        }

        return json_encode($arguments);
    }
    
}
