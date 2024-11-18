<?php

namespace App\Messenger\Handler;

use App\Entity\WeekScrap;
use App\Enum\WeekScrapStatusEnum;
use App\Messenger\Message\WeekScrapMessage;
use App\Repository\WeekScrapRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class WeekScrapHandler
{
    public function __construct(
        private readonly WeekScrapRepository $weekScrapRepository,
        private readonly KernelInterface $kernel,
    ) {
    }

    public function __invoke(WeekScrapMessage $message): void
    {
        $weekScrap = (new WeekScrap())->setStatus(WeekScrapStatusEnum::PENDING);
        $this->weekScrapRepository->save($weekScrap);

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:scrap:products',
        ]);
        $output = new NullOutput();

        try {
            $application->run($input, $output);
        } catch (\Exception $e) {
            $weekScrap->setStatus(WeekScrapStatusEnum::FAILED);
            $this->weekScrapRepository->save($weekScrap);

            throw $e;
        }

        $weekScrap->setStatus(WeekScrapStatusEnum::SUCCESS);
        $this->weekScrapRepository->save($weekScrap);
    }
}
